<?php
class SelfServiceMiddleware {
    /**
     * Fungsi ini memeriksa jika pengguna (dengan auth_token yang valid) 
     * memilih slot Self Service (selected_slot == "5"), maka redirect ke transaksi.php.
     */
    public static function restrictListTransaksiIndexForSelfService($auth_token) {
        // Pastikan auth_token tidak kosong, jika kosong redirect ke halaman login
        if (!$auth_token) {
            header("Location: ../logic/login.php");
            exit;
        }
        
        // Periksa nilai cookie selected_slot
        if (isset($_COOKIE['selected_slot']) && $_COOKIE['selected_slot'] === '5') {
            header("Location: transaksi.php");
            exit;
        }
    }
}
?>
