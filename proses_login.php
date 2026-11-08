<?php
session_start();
include 'config/koneksi.php';

// halaman ini hanya boleh diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

// cari user di database (prepared statement = aman dari SQL Injection)
$stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$user  = mysqli_fetch_assoc($hasil);

// cocokkan password dengan hash di database
if ($user && password_verify($password, $user['password'])) {

    // berhasil: kasih "gelang" (session)
    session_regenerate_id(true);
    $_SESSION['login']    = true;
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];

    // fitur Remember Me (cookie berlaku 7 hari)
    if (isset($_POST['remember'])) {
        setcookie("remember_username", $user['username'], time() + (7 * 24 * 60 * 60), "/", "", false, true);
    } else {
        // tidak dicentang: hapus cookie lama kalau ada
        setcookie("remember_username", "", time() - 3600, "/");
    }

    header("Location: dashboard.php");
    exit;

} else {
    // gagal
    header("Location: index.php?error=1");
    exit;
}
?>