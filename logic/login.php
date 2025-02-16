<?php
session_start();

// Validasi jika pengguna sudah login berdasarkan cookie
require_once('loginvalidation.php');
Validation::isLogin($_COOKIE['auth_token'] ?? null, '../index.php', '../cashier/index.php', '../operational/index.php', '../unrole.php');

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // URL API Laravel untuk login
    $url = 'https://ngolab.id/api/users/login';

    // Data yang akan dikirim ke API dalam format JSON
    $data = [
        'email' => $email,
        'password' => $password,
    ];

    // Inisiasi cURL
    $ch = curl_init($url);

    // Set opsi cURL untuk mengirim request POST dengan JSON
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  
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
    if (isset($result['success']) && $result['success'] && isset($result['data']['token'])) {
        // Simpan token ke dalam cookie
        $token = $result['data']['token'];
        setcookie('auth_token', $token, time() + 28800, '/');

        // Simpan notifikasi sukses
        $_SESSION['success'] = "Login berhasil!";

        // Ambil role_id dari user untuk menentukan arahkan pengguna
        $role_id = $result['data']['user']['role_id'];
        if ($role_id == 1) {
            header('Location: ../index.php');
        } elseif ($role_id == 2) {
            header('Location: ../cashier/index.php');
        } elseif ($role_id == 3) {
            header('Location: ../operational/index.php');
        } else {
            header('Location: ../unrole.php');
        }
        exit;
    } else {
        // Simpan notifikasi error
        $_SESSION['error'] = "Login gagal: " . (isset($result['message']) ? $result['message'] : 'Email atau password salah.');

        // Tetap di halaman login dan tampilkan pesan error
        header('Location: login.php');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .animated-bg {
            background: linear-gradient(270deg, #1a1a2e, #090e1c, #ff7700, #000000);
            background-size: 800% 800%;
            animation: gradientBG 10s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
    </style>
</head>

<body class="animated-bg text-white min-h-screen flex flex-col">

    <nav class="bg-gray-900 py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="login.php" class="text-2xl font-bold">My App</a>
            <div>
                <ul class="flex space-x-4">
                    <li><a href="login.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">Login</a></li>
                    <li><a href="register.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex justify-center items-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-center text-3xl font-bold mb-6">Login</h2>

            <!-- Notifikasi -->
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="bg-green-500 text-white px-4 py-2 rounded-md mb-4">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])) : ?>
                <div class="bg-red-500 text-white px-4 py-2 rounded-md mb-4">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="block mb-1">E-Mail Address</label>
                    <input id="email" type="email" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="email" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1">Password</label>
                    <input id="password" type="password" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="password" required>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="mr-2">
                        <label for="remember" class="text-sm">Remember Me</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md transition">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
