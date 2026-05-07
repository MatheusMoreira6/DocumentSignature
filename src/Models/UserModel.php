<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected string $table = "users";
    protected array $columns = ["id", "name", "email", "password", "token"];

    public function userExists(string $email): bool
    {
        $user = $this->findUserByEmail($email);

        return $user !== false;
    }

    public function findUserByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";

        return $this->select($sql, [$email], false);
    }

    public function create(array $data): int
    {
        return $this->insert($data);
    }
}
