<?php
// URL API untuk menambah role baru
$apiUrl = 'http://127.0.0.1:8000/api/roles';

// Inisialisasi pesan respons
$responseMessage = '';

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleName = $_POST['name'];

    // Token admin, pastikan mengganti dengan token yang valid
    $token = 'your_admin_token_here';

    // Data yang dikirim ke API
    $data = json_encode(['name' => $roleName]);

    // Inisialisasi cURL
    $ch = curl_init($apiUrl);

    // Setel opsi cURL untuk mengirim permintaan POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Eksekusi permintaan cURL dan ambil respons
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Tutup cURL
    curl_close($ch);

    // Decode respons JSON
    $responseData = json_decode($response, true);

    // Tentukan pesan respons berdasarkan status
    if ($httpCode === 201 && isset($responseData['success']) && $responseData['success'] === true) {
        $responseMessage = '<div class="alert alert-success mt-3">' . htmlspecialchars($responseData['message']) . '</div>';
    } else {
        $error = isset($responseData['message']) ? $responseData['message'] : 'Gagal menambah role.';
        $responseMessage = '<div class="alert alert-danger mt-3">' . htmlspecialchars($error) . '</div>';
    }
}
?>

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah Role Baru</h4>
        </div>
        <div class="card-body">
            <!-- Tombol kembali ke halaman utama role -->
            <div class="d-flex mb-3">
                <a href="manageRoles.php" class="btn btn-secondary shadow btn-xs sharp me-2"><i
                        class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <!-- Tampilkan pesan respons -->
            <?= $responseMessage ?>

            <!-- Form tambah role baru -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="roleName" class="form-label">Nama Role</label>
                    <input type="text" id="roleName" name="name" class="form-control" placeholder="Masukkan nama role"
                        required>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Role</button>
            </form>
        </div>
    </div>
</div>