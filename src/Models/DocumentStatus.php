<?php

namespace App\Models;

use App\Core\Model;

class DocumentStatusModel extends Model
{
    protected string $table = "document_status";
    protected array $columns = ["id", "description"];

    public function all(): array
    {
        $sql = "SELECT id, description FROM {$this->table} ORDER BY description ASC";

        return $this->select($sql);
    }
}
