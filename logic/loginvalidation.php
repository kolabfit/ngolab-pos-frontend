<?php
class Validation
{
    public static function validateLoginAdmin($auth_token, $exiturl)
    {
        if (isset($auth_token) == false || $auth_token == null) {
            header('Location: ' . $exiturl);
        } else {
            $url = 'http://127.0.0.1:8000/api/users';

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

            if ($result['success'] != true || $result['data']['role_id'] != 1) {
                header('Location: ' . $exiturl);
                exit;
            }
        }
    }

    public static function validateLoginCashier($auth_token, $exiturl)
    {
        if (isset($auth_token) == false || $auth_token == null) {
            header('Location: ' . $exiturl);
        } else {
            $url = 'http://127.0.0.1:8000/api/users';

            // Inisiasi cURL
            $ch = curl_init($url);

            // Set opsi cURL untuk mengirim request POST dengan JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set header untuk memberitahu bahwa kita mengirimkan JSON
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: ' . $_COOKIE['auth_token']
            ]);

            // Eksekusi cURL dan ambil respons dari API
            $response = curl_exec($ch);
            // Decode response dari JSON ke array PHP
            $result = json_decode($response, true);
            curl_close($ch);

            if ($result['success'] != true || $result['data']['role_id'] != 2) {
                header('Location: ' . $exiturl);
                exit;
            }
        }
    }

    public static function isLogin($auth_token, $adminUrl, $cashierUrl, $operationalUrl, $unroleUrl)
    {
        if (isset($auth_token) == true &&  $auth_token !== null) {
            $url = 'http://127.0.0.1:8000/api/users';

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
            print_r($result['role_id']);

            if ($result['success'] == true && $result['data']['role_id'] == 1) {
                header('Location: ' . $adminUrl);
                exit;
            } else if ($result['success'] == true && $result['data']['role_id'] == 2) {
                header('Location: ' . $cashierUrl);
                exit;
            } else if ($result['success'] == true && $result['data']['role_id'] == 3) {
                header('Location: ' . $operationalUrl);
                exit;
            } else {
                header('Location: ' . $unroleUrl);
            }
        }
    }

    public static function getLoginUser($auth_token){
        $url = 'http://127.0.0.1:8000/api/users';

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

        return $result;
    }

    public static function logout($auth_token){
        $url = 'http://127.0.0.1:8000/api/users/logout';

        // Inisiasi cURL
        $ch = curl_init($url);

        // Set opsi cURL untuk mengirim request POST dengan JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

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

        return $result;
    }
}
