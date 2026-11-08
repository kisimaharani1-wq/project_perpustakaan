<?php
include 'config/cek_login.php';
include 'config/koneksi.php';

// hanya boleh diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_buku.php");
    exit;
}

// 1. Ambil data form
$judul    = trim($_POST['judul']);
$kategori = trim($_POST['kategori']);
$penulis  = trim($_POST['penulis']);
$tahun    = (int) $_POST['tahun_terbit'];
$stok     = (int) $_POST['stok'];

$errors = [];

// 2. Validasi data teks dan angka
if ($judul == "" || $kategori == "" || $penulis == "") {
    $errors[] = "Judul, kategori, dan penulis wajib diisi.";
}
if ($tahun < 1000 || $tahun > (int) date("Y")) {
    $errors[] = "Tahun terbit tidak valid.";
}
if ($stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

// 3. Validasi file sampul (kalau ada yang diupload)
$file     = isset($_FILES['sampul']) ? $_FILES['sampul'] : null;
$ada_file = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;
$ekstensi = "";

if ($ada_file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Upload gagal (kode error: " . $file['error'] . ").";
    } else {
        // cek ekstensi
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
            $errors[] = "Sampul harus berformat JPG, JPEG, atau PNG.";
        }

        // cek ukuran (maks 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Ukuran sampul maksimal 2MB.";
        }

        // cek isi file benar-benar gambar (bukan file lain yang cuma diganti namanya)
        $info = @getimagesize($file['tmp_name']);
        if ($info === false || !in_array($info['mime'], ['image/jpeg', 'image/png'])) {
            $errors[] = "File yang diupload bukan gambar yang valid.";
        }
    }
}

// 4. Kalau semua valid: simpan file lalu simpan ke database
if (empty($errors)) {

    $nama_sampul = null;

    if ($ada_file) {
        // nama baru yang unik, supaya tidak menimpa file lain
        $nama_sampul = "cover_" . uniqid() . "." . $ekstensi;
        $tujuan = __DIR__ . "/uploads/" . $nama_sampul;

        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            $errors[] = "Gagal menyimpan file ke folder uploads.";
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul)
             VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssiis",
            $judul, $kategori, $penulis, $tahun, $stok, $nama_sampul);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php");
            exit;
        } else {
            // gagal simpan ke database: hapus file yang sudah terlanjur diupload
            if ($nama_sampul && file_exists($tujuan)) {
                unlink($tujuan);
            }
            $errors[] = "Gagal menyimpan data ke database.";
        }
    }
}

// Kalau sampai sini berarti ada error (kalau sukses sudah redirect di atas)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gagal Menambah Buku</title>
</head>
<body>
    <h2>Gagal menambah buku</h2>
    <ul>
        <?php foreach ($errors as $e) { ?>
            <li><?php echo htmlspecialchars($e); ?></li>
        <?php } ?>
    </ul>
    <a href="tambah_buku.php">&laquo; Kembali ke form</a>
</body>
</html>