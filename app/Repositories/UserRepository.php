<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findActiveByUsername(string $username): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, person_id, username, password_hash, phone, last_login_at, is_active, is_system_admin
             FROM users WHERE username = :username AND is_active = 1 LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function updateLastLogin(int $userId): void
    {
        $statement = $this->pdo->prepare('UPDATE users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id');
        $statement->execute(['id' => $userId]);
    }

    public function hasGroup(int $userId, string $groupName): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT 1 FROM user_groups ug
             INNER JOIN permission_groups pg ON pg.id = ug.group_id
             WHERE ug.user_id = :user_id AND pg.name = :group_name LIMIT 1'
        );
        $statement->execute(['user_id' => $userId, 'group_name' => $groupName]);

        return (bool) $statement->fetchColumn();
    }

    public function hasPermission(int $userId, string $resource, string $action): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM user_groups ug
             INNER JOIN group_permissions gp ON gp.group_id = ug.group_id
             INNER JOIN permissions p ON p.id = gp.permission_id
             WHERE ug.user_id = :user_id AND p.resource = :resource AND p.action = :action
             LIMIT 1'
        );
        $statement->execute([
            'user_id' => $userId,
            'resource' => $resource,
            'action' => $action,
        ]);

        return (bool) $statement->fetchColumn();
    }
}
