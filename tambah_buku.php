<?php include 'config/cek_login.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Mini Digital Library</title>
</head>
<body>
    <h1>Tambah Buku</h1>

    <form action="proses_tambah.php" method="post" enctype="multipart/form-data">
        <p>Judul Buku:<br>
            <input type="text" name="judul" required maxlength="150">
        </p>
        <p>Kategori:<br>
            <input type="text" name="kategori" required maxlength="50">
        </p>
        <p>Penulis:<br>
            <input type="text" name="penulis" required maxlength="100">
        </p>
        <p>Tahun Terbit:<br>
            <input type="number" name="tahun_terbit" required min="1000" max="<?php echo date('Y'); ?>">
        </p>
        <p>Stok (0 = Dipinjam, lebih dari 0 = Tersedia):<br>
            <input type="number" name="stok" required min="0" value="1">
        </p>
        <p>Sampul Buku (.jpg / .jpeg / .png, maks 2MB):<br>
            <input type="file" name="sampul" accept=".jpg,.jpeg,.png">
        </p>

        <button type="submit">Simpan</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>