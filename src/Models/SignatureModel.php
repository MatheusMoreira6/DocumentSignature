<?php

namespace App\Models;

use App\Core\Model;

class SignatureModel extends Model
{
    protected string $table = "signatures";
    protected array $columns = [
        "id",
        "document_id",
        "name",
        "email",
        "cpf",
        "type_signature_id",
        "status_id",
        "step",
        "certisign_signer_uid",
        "signed_at",
        "created_at",
        "updated_at"
    ];

    public function create(array $data): int
    {
        return $this->insert($data);
    }

    public function destroy(int $document_id): bool
    {
        $result = $this->delete(['document_id' => $document_id]);

        return $result > 0;
    }
}
