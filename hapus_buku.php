<?php
include 'config/cek_login.php';
include 'config/koneksi.php';

// ambil id dari link (GET) dan pastikan angka
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// 1. Ambil nama file sampul dulu (sebelum barisnya hilang)
$stmt = mysqli_prepare($conn, "SELECT sampul FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// 2. Kalau bukunya ada, hapus dari database
if ($buku) {
    $stmt = mysqli_prepare($conn, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        // 3. Database sudah bersih, baru hapus file gambarnya
        if (!empty($buku['sampul'])) {
            $file = __DIR__ . "/uploads/" . basename($buku['sampul']);
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}

// 4. Kembali ke dashboard
header("Location: dashboard.php");
exit;
?>