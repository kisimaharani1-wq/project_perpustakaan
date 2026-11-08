<?php
include 'config/cek_login.php';
include 'config/koneksi.php';

// hanya boleh diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

// 1. Ambil data form
$id       = (int) $_POST['id'];
$judul    = trim($_POST['judul']);
$kategori = trim($_POST['kategori']);
$penulis  = trim($_POST['penulis']);
$tahun    = (int) $_POST['tahun_terbit'];
$stok     = (int) $_POST['stok'];

$errors = [];

// 2. Pastikan bukunya ada, sekaligus ambil nama sampul lama
$stmt = mysqli_prepare($conn, "SELECT sampul FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$lama) {
    header("Location: dashboard.php");
    exit;
}
$sampul_lama = $lama['sampul'];

// 3. Validasi data teks dan angka
if ($judul == "" || $kategori == "" || $penulis == "") {
    $errors[] = "Judul, kategori, dan penulis wajib diisi.";
}
if ($tahun < 1000 || $tahun > (int) date("Y")) {
    $errors[] = "Tahun terbit tidak valid.";
}
if ($stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

// 4. Validasi file sampul baru (kalau ada yang diupload)
$file     = isset($_FILES['sampul']) ? $_FILES['sampul'] : null;
$ada_file = $file && $file['error'] !== UPLOAD_ERR_NO_FILE;
$ekstensi = "";

if ($ada_file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Upload gagal (kode error: " . $file['error'] . ").";
    } else {
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
            $errors[] = "Sampul harus berformat JPG, JPEG, atau PNG.";
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Ukuran sampul maksimal 2MB.";
        }
        $info = @getimagesize($file['tmp_name']);
        if ($info === false || !in_array($info['mime'], ['image/jpeg', 'image/png'])) {
            $errors[] = "File yang diupload bukan gambar yang valid.";
        }
    }
}

// 5. Kalau semua valid: simpan file baru, update database, buang file lama
if (empty($errors)) {

    $sampul_baru = $sampul_lama;   // default: tetap pakai sampul lama
    $tujuan_baru = null;

    if ($ada_file) {
        $sampul_baru = "cover_" . uniqid() . "." . $ekstensi;
        $tujuan_baru = __DIR__ . "/uploads/" . $sampul_baru;

        if (!move_uploaded_file($file['tmp_name'], $tujuan_baru)) {
            $errors[] = "Gagal menyimpan file ke folder uploads.";
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "UPDATE buku
                SET judul = ?, kategori = ?, penulis = ?, tahun_terbit = ?, stok = ?, sampul = ?
              WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssiisi",
            $judul, $kategori, $penulis, $tahun, $stok, $sampul_baru, $id);

        if (mysqli_stmt_execute($stmt)) {

            // database sudah aman, baru hapus file lama (hanya kalau diganti)
            if ($ada_file && !empty($sampul_lama)) {
                $file_lama = __DIR__ . "/uploads/" . basename($sampul_lama);
                if (file_exists($file_lama)) {
                    unlink($file_lama);
                }
            }

            header("Location: dashboard.php");
            exit;

        } else {
            // database gagal: buang file baru yang terlanjur tersimpan
            if ($tujuan_baru && file_exists($tujuan_baru)) {
                unlink($tujuan_baru);
            }
            $errors[] = "Gagal memperbarui data di database.";
        }
    }
}

// Kalau sampai sini berarti ada error
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gagal Mengedit Buku</title>
</head>
<body>
    <h2>Gagal mengedit buku</h2>
    <ul>
        <?php foreach ($errors as $e) { ?>
            <li><?php echo htmlspecialchars($e); ?></li>
        <?php } ?>
    </ul>
    <a href="edit_buku.php?id=<?php echo $id; ?>">&laquo; Kembali ke form edit</a>
</body>
</html>