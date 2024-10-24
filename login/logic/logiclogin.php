<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // URL API Laravel
    $url = 'http://127.0.0.1:8000/api/users/login';

    // Data yang akan dikirim ke API dalam format JSON
    $data = [
        'email' => $email,
        'password' => $password,
    ];

    // Inisiasi cURL
    $ch = curl_init($url);

    // Set opsi cURL untuk mengirim request POST dengan JSON
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Kirim data dalam format JSON
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Set header untuk memberitahu bahwa kita mengirimkan JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
    ]);

    // Eksekusi cURL dan ambil respons dari API
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode response dari JSON ke array PHP
    $result = json_decode($response, true);

    // Cek apakah login berhasil
    if ($result['success'] && isset($result['data']['token'])) {
        // Simpan token ke dalam cookie (berlaku 1 jam)
        $token = $result['data']['token'];
        setcookie('auth_token', $token, time() + 3600, '/');

        // Cek role user, jika role_id null anggap member
        $role = isset($result['data']['user']['role_id']);

        // Arahkan pengguna sesuai role
        if ($role = 1) {
            header('Location: ../../admin/index.html');
        } else {
            header('Location: member/dashboard.php');
        }
        exit;
    } else {
        // Login gagal
        echo "Login gagal: " . $result['message'];
    }
}
?>
