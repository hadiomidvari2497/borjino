<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class BuildingPersonnelRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(?int $buildingId = null, ?int $roleId = null, string $search = ''): array
    {
        $sql = 'SELECT bp.id, bp.building_id, bp.person_id, bp.role_id, bp.created_at,
                       b.name AS building_name,
                       pr.title AS role_title, pr.code AS role_code,
                       p.person_type, p.first_name, p.last_name, p.legal_name, p.national_id, p.phone
                FROM building_personnel bp
                INNER JOIN buildings b ON b.id = bp.building_id
                INNER JOIN persons p ON p.id = bp.person_id
                INNER JOIN personnel_roles pr ON pr.id = bp.role_id';
        $where = [];
        $params = [];

        if ($buildingId !== null && $buildingId > 0) {
            $where[] = 'bp.building_id = :building_id';
            $params['building_id'] = $buildingId;
        }
        if ($roleId !== null && $roleId > 0) {
            $where[] = 'bp.role_id = :role_id';
            $params['role_id'] = $roleId;
        }
        if ($search !== '') {
            $where[] = '(p.first_name LIKE :search OR p.last_name LIKE :search OR p.legal_name LIKE :search OR p.national_id LIKE :search OR p.phone LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY b.name, pr.title, p.last_name, p.first_name, bp.id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function roles(): array
    {
        return $this->pdo->query('SELECT id, code, title FROM personnel_roles ORDER BY id')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT bp.*, b.name AS building_name,
                    pr.title AS role_title, pr.code AS role_code,
                    p.person_type, p.first_name, p.last_name, p.legal_name, p.national_id, p.phone
             FROM building_personnel bp
             INNER JOIN buildings b ON b.id = bp.building_id
             INNER JOIN persons p ON p.id = bp.person_id
             INNER JOIN personnel_roles pr ON pr.id = bp.role_id
             WHERE bp.id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();
        return $item ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO building_personnel (building_id, person_id, role_id)
             VALUES (:building_id, :person_id, :role_id)'
        );
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE building_personnel
             SET building_id = :building_id, person_id = :person_id, role_id = :role_id
             WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM building_personnel WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
