<?php

namespace App\Core;

use Exception;

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
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HEADER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 60,
        ];

        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = is_array($body) || is_object($body)
                ? json_encode($body, JSON_UNESCAPED_UNICODE)
                : $body;
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        if ($response === false) {
            throw new Exception("cURL Error: {$error} (HTTP Status Code: {$status_code})");
        }

        $body_content = substr($response, $header_size);

        return $this->decodeResponse($body_content);
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
        $decoded = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        throw new Exception("JSON Error: " . json_last_error_msg());
    }
}
