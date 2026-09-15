<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ContractRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(?int $unitId = null, ?string $type = null, string $search = ''): array
    {
        $sql = 'SELECT c.*, u.unit_number, b.name AS building_name, bl.name AS block_name, bl.block_number,
                       p.person_type, p.first_name, p.last_name, p.legal_name, p.national_id
                FROM contracts c
                INNER JOIN units u ON u.id = c.unit_id
                INNER JOIN blocks bl ON bl.id = u.block_id AND bl.building_id = u.building_id
                INNER JOIN buildings b ON b.id = u.building_id
                INNER JOIN persons p ON p.id = c.party_person_id';
        $where = [];
        $params = [];
        if ($unitId !== null && $unitId > 0) { $where[] = 'c.unit_id = :unit_id'; $params['unit_id'] = $unitId; }
        if (in_array($type, ['rental', 'sale'], true)) { $where[] = 'c.contract_type = :contract_type'; $params['contract_type'] = $type; }
        if ($search !== '') {
            $where[] = '(u.unit_number LIKE :search OR b.name LIKE :search OR p.first_name LIKE :search OR p.last_name LIKE :search OR p.legal_name LIKE :search OR p.national_id LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        if ($where !== []) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY c.contract_date DESC, c.id DESC';
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT c.*, u.unit_number, u.building_id, b.name AS building_name,
                bl.name AS block_name, bl.block_number, p.person_type, p.first_name, p.last_name, p.legal_name
            FROM contracts c
            INNER JOIN units u ON u.id = c.unit_id
            INNER JOIN blocks bl ON bl.id = u.block_id AND bl.building_id = u.building_id
            INNER JOIN buildings b ON b.id = u.building_id
            INNER JOIN persons p ON p.id = c.party_person_id
            WHERE c.id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare('INSERT INTO contracts
            (unit_id, contract_type, party_person_id, deposit_amount, monthly_rent, sale_amount, contract_date, end_date)
            VALUES (:unit_id, :contract_type, :party_person_id, :deposit_amount, :monthly_rent, :sale_amount, :contract_date, :end_date)');
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare('UPDATE contracts SET unit_id = :unit_id, contract_type = :contract_type,
            party_person_id = :party_person_id, deposit_amount = :deposit_amount, monthly_rent = :monthly_rent,
            sale_amount = :sale_amount, contract_date = :contract_date, end_date = :end_date WHERE id = :id');
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM contracts WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
