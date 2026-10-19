<?php

declare(strict_types=1);

class User extends Model
{
    public function findByUsername(string $username): ?array
    {
        $statement = $this->db->prepare(
            'SELECT id, username, password, full_name, role FROM users WHERE username = :username LIMIT 1'
        );
        $statement->execute(['username' => $username]);

        $user = $statement->fetch();

        return $user !== false ? $user : null;
    }
}
