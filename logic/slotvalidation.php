<?php

class Slotvalidation
{
    public static function isfillslot(
        $auth_token
    ) {
        $url = 'https://ngolab.id/api/cashier-slots/check';

        // Inisiasi cURL
        $ch = curl_init($url);

        // Set opsi cURL untuk mengirim request POST dengan JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set header untuk memberitahu bahwa kita mengirimkan JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $auth_token
        ]);

        // Eksekusi cURL dan ambil respons dari API
        $response = curl_exec($ch);
        // Decode response dari JSON ke array PHP
        $result = json_decode($response, true);
        curl_close($ch);

        if ($result['data']['status'] == true) {
            header('Location: ../cashier/index.php');
            exit;
        }

    }

    public static function isnotfillslot(
        $auth_token
    ) {
        $url = 'https://ngolab.id/api/cashier-slots/check';

        // Inisiasi cURL
        $ch = curl_init($url);

        // Set opsi cURL untuk mengirim request POST dengan JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set header untuk memberitahu bahwa kita mengirimkan JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $auth_token
        ]);

        // Eksekusi cURL dan ambil respons dari API
        $response = curl_exec($ch);
        // Decode response dari JSON ke array PHP
        $result = json_decode($response, true);
        curl_close($ch);

        if ($result['data']['status'] == false) {
            header('Location: ../cashier/slotkasir.php');
            exit;
        }

    }

}

?>