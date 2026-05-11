<?php

namespace App\Core;

use Exception;
use JsonException;

class Http
{
    private string $api_base_url;

    public function __construct(string $api_base_url)
    {
        if (empty($api_base_url)) {
            throw new Exception('API base URL não pode ser vazia.');
        }

        $this->api_base_url = rtrim($api_base_url, '/');
    }

    public function sendRequest(string $url, string $method = 'GET', array $headers = [], mixed $body = null): mixed
    {
        $url = $this->buildUrl($url);
        $headers = $this->buildHeaders($headers);

        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 60,
        ];

        if ($body !== null) {

            if (is_array($body) || is_object($body)) {
                $body = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            }

            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            throw new Exception(
                "cURL Error: {$error} (HTTP Status Code: {$status_code})"
            );
        }

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status_code >= 400) {
            throw new Exception(
                "HTTP Error: {$status_code} | Response: {$response}"
            );
        }

        if ($response === '') {
            return null;
        }

        return $this->decodeResponse($response);
    }

    private function buildUrl(string $url): string
    {
        return $this->api_base_url . '/' . ltrim($url, '/');
    }

    private function buildHeaders(array $headers): array
    {
        $normalized_headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        foreach ($headers as $key => $value) {
            if (is_int($key)) {
                $normalized_headers[] = $value;
                continue;
            }

            $normalized_headers[] = trim($key) . ': ' . trim((string) $value);
        }

        return $normalized_headers;
    }

    private function decodeResponse(string $body): mixed
    {
        try {
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new Exception('JSON Error: ' . $e->getMessage());
        }
    }
}
