<?php

namespace App\Models;

use App\Core\Model;

class DocumentModel extends Model
{
    protected string $table = "documents";
    protected array $columns = [
        "id",
        "user_id",
        "certisign_document_id",
        "file_name",
        "sign_url",
        "status_id",
        "created_at",
        "updated_at",
    ];

    private function buildOrderBy(array $orders, array $columns): string
    {
        $db_columns = [
            'file_name'  => 'documents.file_name',
            'created_at' => 'documents.created_at',
            'updated_at' => 'documents.updated_at',
            'status'     => 'document_status.description',
        ];

        $order_by = [];

        foreach ($orders as $order) {
            $column_index = (int) $order['column'];

            $column_name = $columns[$column_index]['data'] ?? null;

            if (!isset($db_columns[$column_name])) {
                continue;
            }

            $direction = strtoupper($order['dir']) === 'ASC' ? 'ASC' : 'DESC';

            $order_by[] = "{$db_columns[$column_name]} {$direction}";
        }

        return !empty($order_by) ? 'ORDER BY ' . implode(', ', $order_by) : 'ORDER BY documents.created_at DESC';
    }

    public function datatable(int $user_id, array $orders, array $columns, ?int $start = null, ?int $length = null, ?string $search = null): array
    {
        $filter = '';
        $limit_offset = '';
        $where = [$user_id];

        if (!empty($search)) {
            $filter = ' AND documents.file_name LIKE ? ';
            $where[] = '%' . trim($search) . '%';
        }

        $order_by = $this->buildOrderBy($orders, $columns);

        if ($start !== null && $length !== null) {
            $limit_offset = ' LIMIT ? OFFSET ? ';

            $where[] = $length;
            $where[] = $start;
        }

        $sql = "SELECT
                    documents.id,
                    documents.file_name,
                    TO_CHAR(documents.created_at, 'DD/MM/YYYY HH24:MI') AS created_at,
                    TO_CHAR(documents.updated_at, 'DD/MM/YYYY HH24:MI') AS updated_at,
                    document_status.description AS status
                FROM
                    documents
                INNER JOIN document_status ON
                    document_status.id = documents.status_id
                WHERE
                    documents.user_id = ?
                    {$filter}
                {$order_by}
                {$limit_offset}";

        return $this->select($sql, $where);
    }

    public function findDocumentById(int $document_id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";

        return $this->select($sql, [$document_id], false);
    }

    public function create(array $data): int
    {
        return $this->insert($data);
    }

    public function destroy(int $document_id, int $user_id): bool
    {
        $result = $this->delete(["id" => $document_id, "user_id" => $user_id]);

        return $result > 0;
    }
}
