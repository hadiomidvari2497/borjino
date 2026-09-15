<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class PersonRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(string $search = '', ?string $type = null): array
    {
        $sql = "SELECT id, first_name, last_name, legal_name, person_type, national_id, phone,
                       birth_or_establishment_date, address, secondary_address, created_at
                FROM persons";
        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = "(first_name LIKE :search OR last_name LIKE :search OR legal_name LIKE :search
                         OR national_id LIKE :search OR phone LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if (in_array($type, ['individual', 'legal'], true)) {
            $where[] = 'person_type = :person_type';
            $params['person_type'] = $type;
        }
        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM persons WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $person = $statement->fetch();
        return $person ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO persons
             (first_name, last_name, legal_name, person_type, national_id, phone,
              birth_or_establishment_date, address, secondary_address)
             VALUES (:first_name, :last_name, :legal_name, :person_type, :national_id, :phone,
                     :birth_or_establishment_date, :address, :secondary_address)'
        );
        $statement->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE persons SET
                first_name = :first_name,
                last_name = :last_name,
                legal_name = :legal_name,
                person_type = :person_type,
                national_id = :national_id,
                phone = :phone,
                birth_or_establishment_date = :birth_or_establishment_date,
                address = :address,
                secondary_address = :secondary_address
             WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM persons WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
