<?php
session_start();

// kalau sudah login, tidak perlu lihat form login lagi
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

// ambil username dari cookie Remember Me (kalau ada)
$username_cookie = isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : "";
$ada_error = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Digital Library</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="login-wrap p-3">
    <div class="card card-soft login-card p-4">

        <div class="text-center mb-4">
            <div class="login-icon mx-auto mb-3"><i class="bi bi-book-half"></i></div>
            <h1 class="h3 mb-1">Mini Digital Library</h1>
            <p class="text-soft mb-0">Silakan login untuk mengelola koleksi buku</p>
        </div>

        <?php if ($ada_error) { ?>
            <div class="alert alert-danger py-2" role="alert">
                <i class="bi bi-exclamation-circle"></i> Username atau password salah!
            </div>
        <?php } ?>

        <form action="proses_login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" required
                           value="<?php echo htmlspecialchars($username_cookie); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                       <?php if ($username_cookie != "") { echo "checked"; } ?>>
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-orange w-100 py-2">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>

    </div>
</div>

</body>
</html>