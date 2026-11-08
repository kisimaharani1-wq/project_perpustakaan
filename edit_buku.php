<?php
include 'config/cek_login.php';
include 'config/koneksi.php';

// ambil id dari link (GET) dan pastikan angka
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// ambil data buku yang mau diedit
$stmt = mysqli_prepare($conn, "SELECT * FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$buku  = mysqli_fetch_assoc($hasil);

// kalau id tidak ada di database, kembali ke dashboard
if (!$buku) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Mini Digital Library</title>
</head>
<body>
    <h1>Edit Buku</h1>

    <form action="proses_edit.php" method="post" enctype="multipart/form-data">
        <!-- id dikirim diam-diam supaya proses_edit tahu buku mana -->
        <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">

        <p>Judul Buku:<br>
            <input type="text" name="judul" required maxlength="150"
                   value="<?php echo htmlspecialchars($buku['judul'], ENT_QUOTES); ?>">
        </p>
        <p>Kategori:<br>
            <input type="text" name="kategori" required maxlength="50"
                   value="<?php echo htmlspecialchars($buku['kategori'], ENT_QUOTES); ?>">
        </p>
        <p>Penulis:<br>
            <input type="text" name="penulis" required maxlength="100"
                   value="<?php echo htmlspecialchars($buku['penulis'], ENT_QUOTES); ?>">
        </p>
        <p>Tahun Terbit:<br>
            <input type="number" name="tahun_terbit" required min="1000" max="<?php echo date('Y'); ?>"
                   value="<?php echo $buku['tahun_terbit']; ?>">
        </p>
        <p>Stok (0 = Dipinjam, lebih dari 0 = Tersedia):<br>
            <input type="number" name="stok" required min="0"
                   value="<?php echo $buku['stok']; ?>">
        </p>

        <p>Sampul Saat Ini:<br>
            <?php if (!empty($buku['sampul'])) { ?>
                <img src="uploads/<?php echo htmlspecialchars($buku['sampul']); ?>" width="100">
            <?php } else { ?>
                (belum ada sampul)
            <?php } ?>
        </p>
        <p>Ganti Sampul (.jpg / .jpeg / .png, maks 2MB):<br>
            <input type="file" name="sampul" accept=".jpg,.jpeg,.png"><br>
            <small>Kosongkan kalau tidak ingin mengganti sampul.</small>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>