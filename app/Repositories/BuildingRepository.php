<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class BuildingRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(string $search = ''): array
    {
        if ($search === '') {
            return $this->pdo->query(
                'SELECT id, name, postal_code, building_type, construction_date, parking_count, storage_count, province, city, address
                 FROM buildings ORDER BY id DESC'
            )->fetchAll();
        }

        $statement = $this->pdo->prepare(
            'SELECT id, name, postal_code, building_type, construction_date, parking_count, storage_count, province, city, address
             FROM buildings
             WHERE name LIKE :search OR postal_code LIKE :search OR city LIKE :search
             ORDER BY id DESC'
        );
        $statement->execute(['search' => '%' . $search . '%']);

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM buildings WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $building = $statement->fetch();

        return $building ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO buildings
             (name, postal_code, building_type, construction_date, parking_count, storage_count, province, city, address)
             VALUES (:name, :postal_code, :building_type, :construction_date, :parking_count, :storage_count, :province, :city, :address)'
        );
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE buildings SET
                name = :name,
                postal_code = :postal_code,
                building_type = :building_type,
                construction_date = :construction_date,
                parking_count = :parking_count,
                storage_count = :storage_count,
                province = :province,
                city = :city,
                address = :address
             WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM buildings WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
