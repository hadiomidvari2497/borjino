<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class BlockRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(?int $buildingId = null, string $search = ''): array
    {
        $sql = 'SELECT b.id, b.building_id, b.block_number, b.name, b.floor_count, b.created_at,
                       bd.name AS building_name, COUNT(u.id) AS units_count
                FROM blocks b
                INNER JOIN buildings bd ON bd.id = b.building_id
                LEFT JOIN units u ON u.block_id = b.id';
        $where = [];
        $params = [];
        if ($buildingId !== null && $buildingId > 0) {
            $where[] = 'b.building_id = :building_id';
            $params['building_id'] = $buildingId;
        }
        if ($search !== '') {
            $where[] = '(b.name LIKE :search OR CAST(b.block_number AS CHAR) LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' GROUP BY b.id, b.building_id, b.block_number, b.name, b.floor_count, b.created_at, bd.name
                  ORDER BY bd.name, b.block_number';
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT b.*, bd.name AS building_name FROM blocks b
             INNER JOIN buildings bd ON bd.id = b.building_id
             WHERE b.id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $block = $statement->fetch();
        return $block ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO blocks (building_id, block_number, name, floor_count)
             VALUES (:building_id, :block_number, :name, :floor_count)'
        );
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE blocks SET building_id = :building_id, block_number = :block_number,
             name = :name, floor_count = :floor_count WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM blocks WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
