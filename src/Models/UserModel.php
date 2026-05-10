<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected string $table = "users";
    protected array $columns = ["id", "name", "cpf", "email", "password", "token"];

    public function userExists(string $email, ?string $cpf = null): bool
    {
        $user = $this->findUserByEmail($email);

        if ($cpf !== null) {
            $user = $this->findUserByCpf($cpf);
        }

        return $user !== false;
    }

    public function findUserById(int $user_id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";

        return $this->select($sql, [$user_id], false);
    }

    public function findUserByCpf(string $cpf): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE cpf = ?";

        return $this->select($sql, [$cpf], false);
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
