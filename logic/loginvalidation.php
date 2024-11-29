<?php
Class Validation{
    public static function validateLogin($auth_token, $exiturl = 'login.php'){
        if (isset($auth_token) == false || $auth_token == null) {
            header('Location: ../logic/login.php');
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
        
            if ($result['success'] != true && $result['role_id'] == 1) {
                header($exiturl);
                exit;
            }
        }
    }
}
?>