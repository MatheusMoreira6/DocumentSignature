<?php

namespace App\Core;

use Exception;

class Certising
{
    private Http $http;

    public function __construct()
    {
        $this->http = new Http(CERTISING_API_BASE_URL);
    }

    public function uploadDocument(string $token, string $file_path, ?string $file_name = null): mixed
    {
        if (!is_readable($file_path)) {
            throw new Exception('Arquivo não encontrado ou não é legível: ' . $file_path);
        }

        $content = file_get_contents($file_path);

        if ($content === false) {
            throw new Exception('Não foi possível ler o arquivo: ' . $file_path);
        }

        $bytes = array_values(unpack('C*', $content));

        $payload = [
            'fileName' => $file_name ?: basename($file_path),
            'bytes' => $bytes,
        ];

        $response = $this->http->sendRequest(
            'document/upload',
            'POST',
            ['token' => $token],
            $payload
        );

        if (!isset($response['uploadId'])) {
            throw new Exception('Resposta de upload inválida: ' . json_encode($response));
        }

        return $response['uploadId'];
    }

    public function createDocument(
        string $token,
        string $upload_id,
        string $document_name,
        array $sender,
        array $signers = [],
        array $electronic_signers = [],
    ): mixed {
        $data = [
            // 'typeId' => 1,
            'document' => [
                'name' => $document_name,
                'upload' => [
                    'id' => $upload_id,
                    'name' => $document_name,
                ],
            ],
            'sender' => $sender,
            'signers' => $signers,
            'electronicSigners' => $electronic_signers,
            // 'serverSigners' => [
            //     [
            //         'step' => 2,
            //         'certificateId' => 72
            //     ]
            // ],
        ];

        $payload = array_filter($data, function ($value) {
            return $value !== [] && $value !== null;
        });

        return $this->http->sendRequest(
            'document/create',
            'POST',
            ['token' => $token],
            $payload
        );
    }

    public function deleteDocument(string $token, string $document_id): mixed
    {
        $query = http_build_query(['id' => $document_id]);

        return $this->http->sendRequest(
            'document/delete?' . $query,
            'DELETE',
            ['token' => $token]
        );
    }

    public function downloadDocument(string $token, string $document_id, bool $include_original = true): mixed
    {
        $query = http_build_query([
            'key' => $document_id,
            'includeOriginal' => $include_original ? 'true' : 'false'
        ]);

        $response = $this->http->sendRequest(
            'document/package?' . $query,
            'GET',
            ['token' => $token]
        );

        return [
            'name' => $response['name'],
            'mimeType' => $response['mimeType'],
            'content' => pack('C*', ...$response['bytes'])
        ];
    }
}
