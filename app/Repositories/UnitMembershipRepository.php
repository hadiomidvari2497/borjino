<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UnitMembershipRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function allForUnit(int $unitId): array
    {
        $statement = $this->pdo->prepare(
            "SELECT m.*, p.person_type, p.first_name, p.last_name, p.legal_name, p.national_id, p.phone
             FROM unit_memberships m
             INNER JOIN persons p ON p.id = m.person_id
             WHERE m.unit_id = :unit_id
             ORDER BY m.is_current DESC, m.membership_type, m.start_date DESC, m.id DESC"
        );
        $statement->execute(['unit_id' => $unitId]);
        return $statement->fetchAll();
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO unit_memberships
             (unit_id, person_id, membership_type, start_date, end_date, is_current, notes)
             VALUES (:unit_id, :person_id, :membership_type, :start_date, :end_date, :is_current, :notes)'
        );
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE unit_memberships SET person_id = :person_id, membership_type = :membership_type,
             start_date = :start_date, end_date = :end_date, is_current = :is_current, notes = :notes
             WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM unit_memberships WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row ?: null;
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM unit_memberships WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
