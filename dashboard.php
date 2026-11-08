<?php
include 'config/cek_login.php';
include 'config/koneksi.php';

$judul_halaman = "Dashboard";

// ambil semua buku sekaligus ke dalam array
$hasil       = mysqli_query($conn, "SELECT * FROM buku ORDER BY id ASC");
$daftar_buku = mysqli_fetch_all($hasil, MYSQLI_ASSOC);

// hitung ringkasan untuk kartu statistik
$total    = count($daftar_buku);
$tersedia = 0;
foreach ($daftar_buku as $b) {
    if ($b['stok'] > 0) {
        $tersedia++;
    }
}
$dipinjam = $total - $tersedia;

// pesan sekali tampil (diisi halaman proses, mulai langkah 5b)
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

include 'includes/header.php';
?>

<?php if ($flash) { ?>
    <div class="alert alert-<?php echo htmlspecialchars($flash['tipe']); ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash['pesan']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php } ?>

<div class="hero mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <p class="hero-kicker mb-1">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <h1 class="h2 mb-1">Dashboard Perpustakaan</h1>
        <p class="mb-0">Kelola koleksi buku dengan mudah.</p>
    </div>
    <a href="tambah_buku.php" class="btn btn-orange btn-lg">
        <i class="bi bi-plus-lg"></i> Tambah Buku
    </a>
</div>

<!-- Kartu statistik -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card card-soft p-3 d-flex flex-row align-items-center gap-3">
            <div class="stat-icon"><i class="bi bi-journal-bookmark"></i></div>
            <div>
                <div class="text-soft small">Total Buku</div>
                <div class="h3 mb-0"><?php echo $total; ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft p-3 d-flex flex-row align-items-center gap-3">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="text-soft small">Tersedia</div>
                <div class="h3 mb-0"><?php echo $tersedia; ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft p-3 d-flex flex-row align-items-center gap-3">
            <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="text-soft small">Dipinjam</div>
                <div class="h3 mb-0"><?php echo $dipinjam; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel buku -->
<div class="card card-soft">
    <div class="p-3 border-bottom">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="cari" class="form-control" placeholder="Cari judul, penulis, atau kategori...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-perpus" id="tabel-buku">
            <thead>
                <tr>
                    <th class="ps-3">No</th>
                    <th>Sampul</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total == 0) { ?>
                    <tr>
                        <td colspan="9" class="text-center text-soft py-5">
                            Belum ada data buku. Klik "Tambah Buku" untuk memulai.
                        </td>
                    </tr>
                <?php } ?>

                <?php
                $no = 1;
                foreach ($daftar_buku as $b) {

                    // logika bisnis: tentukan label status dari stok
                    if ($b['stok'] > 0) {
                        $status = "Tersedia";
                        $kelas  = "badge-tersedia";
                    } else {
                        $status = "Dipinjam";
                        $kelas  = "badge-dipinjam";
                    }

                    // bahan pencarian (huruf kecil semua)
                    $bahan_cari = strtolower($b['judul'] . ' ' . $b['penulis'] . ' ' . $b['kategori']);
                ?>
                <tr class="baris-buku" data-cari="<?php echo htmlspecialchars($bahan_cari, ENT_QUOTES); ?>">
                    <td class="ps-3"><?php echo $no++; ?></td>
                    <td>
                        <?php if (!empty($b['sampul'])) { ?>
                            <img src="uploads/<?php echo htmlspecialchars($b['sampul']); ?>"
                                 class="cover-thumb" alt="Sampul <?php echo htmlspecialchars($b['judul'], ENT_QUOTES); ?>">
                        <?php } else { ?>
                            <div class="cover-placeholder"><i class="bi bi-image"></i></div>
                        <?php } ?>
                    </td>
                    <td class="fw-semibold"><?php echo htmlspecialchars($b['judul']); ?></td>
                    <td><?php echo htmlspecialchars($b['kategori']); ?></td>
                    <td><?php echo htmlspecialchars($b['penulis']); ?></td>
                    <td><?php echo $b['tahun_terbit']; ?></td>
                    <td><?php echo $b['stok']; ?></td>
                    <td><span class="badge rounded-pill <?php echo $kelas; ?>"><?php echo $status; ?></span></td>
                    <td class="text-end pe-3 text-nowrap">
                        <a href="edit_buku.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-cream border">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <a href="hapus_buku.php?id=<?php echo $b['id']; ?>"
                           class="btn btn-sm btn-orange btn-hapus"
                           data-judul="<?php echo htmlspecialchars($b['judul'], ENT_QUOTES); ?>">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>