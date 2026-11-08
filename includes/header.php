<?php
// $judul_halaman diisi oleh halaman yang memanggil
if (!isset($judul_halaman)) {
    $judul_halaman = "Mini Digital Library";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($judul_halaman); ?> - Mini Digital Library</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-sage shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-book-half"></i> Mini Digital Library
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="nav-user d-none d-sm-inline">
                <i class="bi bi-person-circle"></i>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <a href="logout.php" class="btn btn-sm btn-cream">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">