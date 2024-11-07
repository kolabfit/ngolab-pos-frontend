<?php
session_start();

// Hapus cookie auth_token dengan mengatur waktu kadaluwarsa ke masa lalu
if (isset($_COOKIE['auth_token'])) {
    unset($_COOKIE['auth_token']);
    setcookie('auth_token', '', time() - 3600, '/'); // Menghapus cookie
}

// Hancurkan session untuk memastikan pengguna benar-benar keluar
session_destroy();

// Alihkan pengguna ke halaman login atau halaman lain setelah logout
header('Location: login.php');
exit;
?>
