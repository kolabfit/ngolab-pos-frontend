<?php

class ApiClient
{
    private $baseUrl;

    public function __construct($baseUrl = 'https://ngolab.id')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    // Fungsi utama untuk melakukan permintaan API
    public function request($endpoint, $method = 'GET', $headers = [], $body = null)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        // Inisialisasi cURL
        $ch = curl_init($url);

        // Set method, headers, dan body jika diperlukan
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Set headers jika ada
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Set body untuk metode POST dan PUT
        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        // Eksekusi permintaan
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Menutup sesi cURL
        curl_close($ch);

        // Jika statusCode 200, return data JSON, jika tidak lempar exception
        if ($statusCode === 200) {
            return json_decode($response, true);  // Return parsed response
        } else {
            throw new Exception("API Request failed with status code: $statusCode", $statusCode);
        }
    }
}
?>
