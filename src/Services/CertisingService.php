<?php

namespace App\Services;

use App\Core\Certising;
use InvalidArgumentException;

class CertisingService
{
    private Certising $certising;

    public function __construct()
    {
        $this->certising = new Certising();
    }

    private function sanitizeSigners(array $signers): array
    {
        return array_map(function ($signer) {
            $cpf = isset($signer['cpf']) ? regex($signer['cpf'], '/\D/', '') : null;

            if (empty($cpf) || strlen($cpf) !== 11) {
                throw new InvalidArgumentException('CPF inválido para o signatário: ' . $signer['name']);
            }

            if (empty($signer['name']) || empty($signer['email'])) {
                throw new InvalidArgumentException('Nome e email são obrigatórios para o signatário: ' . $signer['name']);
            }

            return [
                "step" => 1,
                "title" => "Signer",
                "name" => $signer['name'],
                "email" => $signer['email'],
                "individualIdentificationCode" => $cpf
            ];
        }, $signers);
    }

    private function uploadDocument(string $token, string $file_path, string $file_name): mixed
    {
        return $this->certising->uploadDocument($token, $file_path, $file_name);
    }

    private function sendSignature(string $token, string $upload_id, string $document_name, array $sender, array $signers, array $electronic_signers)
    {
        return $this->certising->createDocument($token, $upload_id, $document_name, $sender, $signers, $electronic_signers);
    }

    public function createDocument(
        string $token,
        string $file_path,
        string $file_name,
        string $sender_name,
        string $sender_email,
        string $sender_cpf,
        array $signers = [],
        array $eletronic_signers = []
    ): mixed {
        $signers = $this->sanitizeSigners($signers);
        $eletronic_signers = $this->sanitizeSigners($eletronic_signers);

        $sender = [
            'name' => $sender_name,
            'email' => $sender_email,
            'individualIdentificationCode' => regex($sender_cpf, '/\D/', ''),
        ];

        $upload_id = $this->uploadDocument($token, $file_path, $file_name);

        return $this->sendSignature($token, $upload_id, $file_name, $sender, $signers, $eletronic_signers);
    }
}
