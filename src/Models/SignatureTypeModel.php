<?php

namespace App\Models;

use App\Core\Model;

class SignatureTypeModel extends Model
{
    protected string $table = "signature_types";
    protected array $columns = [
        "id",
        "description"
    ];

    public function all(): array
    {
        $sql = "SELECT id, description FROM {$this->table} ORDER BY description ASC";

        return $this->select($sql);
    }
}
