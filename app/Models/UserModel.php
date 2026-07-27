<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): array|false
    {
        return $this->queryOne(
            'SELECT id, name, username, password, role FROM users WHERE username = ? AND is_active = 1 LIMIT 1',
            [$username]
        );
    }

    public function findById(int $id): array|false
    {
        return $this->queryOne(
            'SELECT id, name, username, role FROM users WHERE id = ? LIMIT 1',
            [$id]
        );
    }
}
