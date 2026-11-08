<?php
session_start();

// kosongkan data session
$_SESSION = [];

// hapus cookie session di browser
if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
}

// hancurkan session di server
session_destroy();

// hapus cookie Remember Me
setcookie("remember_username", "", time() - 3600, "/");

// kembali ke halaman login
header("Location: index.php");
exit;
?>