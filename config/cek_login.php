<?php
session_start();

// kalau belum punya "gelang" (belum login), usir ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>