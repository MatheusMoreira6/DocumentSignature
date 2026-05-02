<?php

namespace Core;

use Exception;
use PDO;

class Model
{
    protected PDO $db;

    protected string $table = '';
    protected array $columns = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function checkTable(): void
    {
        if (empty($this->table)) {
            throw new Exception("Tabela inválida");
        }
    }

    private function filterColumns(array $data): array
    {
        if (empty($data)) {
            throw new Exception("Argumentos inválidos");
        }

        $allowed = array_flip($this->columns);
        $filtered = array_intersect_key($data, $allowed);

        if (empty($filtered)) {
            throw new Exception("Colunas não mapeadas");
        }

        return $filtered;
    }

    public function select(string $sql, array $where = [], bool $fetch_all = true): array|false
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($where);

        return $fetch_all ? $stmt->fetchAll() : $stmt->fetch();
    }

    public function insert(array $data = []): int
    {
        $this->checkTable();

        $data = $this->filterColumns($data);

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s) RETURNING id",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        $result = $stmt->fetch();

        if (!$result || !isset($result['id'])) {
            throw new Exception("Falha ao inserir registro");
        }

        return (int) $result['id'];
    }

    public function update(array $data = [], array $where = []): int
    {
        $this->checkTable();

        $data = $this->filterColumns($data);
        $where = $this->filterColumns($where);

        $set_clause = [];
        foreach ($data as $column => $value) {
            $set_clause[] = "$column = ?";
        }

        $where_clause = [];
        foreach ($where as $column => $value) {
            $where_clause[] = "$column = ?";
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $this->table,
            implode(', ', $set_clause),
            implode(' AND ', $where_clause)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge(array_values($data), array_values($where)));

        return $stmt->rowCount();
    }

    public function delete(array $where = []): int
    {
        $this->checkTable();

        $where = $this->filterColumns($where);

        $where_clause = [];
        foreach ($where as $column => $value) {
            $where_clause[] = "$column = ?";
        }

        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $this->table,
            implode(' AND ', $where_clause)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($where));

        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->db->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->db->commit();
    }

    public function rollBack(): bool
    {
        return $this->db->rollBack();
    }
}
