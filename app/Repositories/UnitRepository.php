<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UnitRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(?int $buildingId = null, ?int $blockId = null, string $search = '', ?string $status = null, ?string $financialStatus = null): array
    {
        $sql = 'SELECT u.*, b.name AS building_name, bl.name AS block_name, bl.block_number
                FROM units u
                INNER JOIN buildings b ON b.id = u.building_id
                INNER JOIN blocks bl ON bl.id = u.block_id AND bl.building_id = u.building_id';
        $where = [];
        $params = [];
        if ($buildingId) { $where[] = 'u.building_id = :building_id'; $params['building_id'] = $buildingId; }
        if ($blockId) { $where[] = 'u.block_id = :block_id'; $params['block_id'] = $blockId; }
        if ($search !== '') {
            $where[] = '(u.unit_number LIKE :search OR u.postal_code LIKE :search OR bl.name LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        if ($status !== null && in_array($status, ['sold','rented','vacant','under_repair'], true)) { $where[] = 'u.status = :status'; $params['status'] = $status; }
        if ($financialStatus !== null && in_array($financialStatus, ['debtor','creditor','settled'], true)) { $where[] = 'u.financial_status = :financial_status'; $params['financial_status'] = $financialStatus; }
        if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
        $sql .= ' ORDER BY b.name, bl.block_number, u.floor_number, u.unit_number';
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT u.*, b.name AS building_name, bl.name AS block_name, bl.floor_count
            FROM units u INNER JOIN buildings b ON b.id = u.building_id
            INNER JOIN blocks bl ON bl.id = u.block_id AND bl.building_id = u.building_id
            WHERE u.id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare('INSERT INTO units
            (building_id, block_id, unit_number, postal_code, floor_number, area_sqm, status, financial_status, direction, notes)
            VALUES (:building_id, :block_id, :unit_number, :postal_code, :floor_number, :area_sqm, :status, :financial_status, :direction, :notes)');
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare('UPDATE units SET building_id = :building_id, block_id = :block_id,
            unit_number = :unit_number, postal_code = :postal_code, floor_number = :floor_number,
            area_sqm = :area_sqm, status = :status, financial_status = :financial_status,
            direction = :direction, notes = :notes WHERE id = :id');
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM units WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function countByBlock(int $blockId): int
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM units WHERE block_id = :block_id');
        $statement->execute(['block_id' => $blockId]);
        return (int) $statement->fetchColumn();
    }

    public function generateForBlock(int $buildingId, int $blockId, int $unitsPerFloor): int
    {
        $statement = $this->pdo->prepare('SELECT floor_count FROM blocks WHERE id = :id AND building_id = :building_id LIMIT 1');
        $statement->execute(['id' => $blockId, 'building_id' => $buildingId]);
        $floorCount = (int) $statement->fetchColumn();
        if ($floorCount < 1) { throw new \RuntimeException('بلوک معتبر نیست.'); }

        $insert = $this->pdo->prepare('INSERT IGNORE INTO units
            (building_id, block_id, unit_number, floor_number, area_sqm, status, financial_status)
            VALUES (:building_id, :block_id, :unit_number, :floor_number, 0, \'vacant\', \'settled\')');
        $created = 0;
        for ($floor = 1; $floor <= $floorCount; $floor++) {
            for ($sequence = 1; $sequence <= $unitsPerFloor; $sequence++) {
                $insert->execute([
                    'building_id' => $buildingId,
                    'block_id' => $blockId,
                    'unit_number' => (string) ($floor * 100 + $sequence),
                    'floor_number' => $floor,
                ]);
                $created += $insert->rowCount();
            }
        }
        return $created;
    }
}
