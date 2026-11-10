# Mini Digital Library 📚

Aplikasi web sederhana berbasis **PHP Native** dan **MySQL** yang dirancang untuk mengelola inventaris koleksi buku perpustakaan digital secara efisien. Proyek ini dilengkapi dengan fitur autentikasi aman, manajemen CRUD buku, unggah sampul, serta antarmuka yang modern dan responsif menggunakan Bootstrap 5.


## 🛠️ Fitur Utama

* **Sistem Autentikasi Pengguna**: Login aman menggunakan `password_verify`, pengelolaan sesi, dan fitur *Remember Me* berbasis Cookie.


* **Dashboard Statistik**: Menampilkan ringkasan total buku, jumlah buku yang tersedia, serta buku yang sedang dipinjam.


* **Manajemen Buku (CRUD)**:
* **Tambah Buku**: Menambahkan data buku lengkap dengan validasi input, pencegahan SQL Injection menggunakan *Prepared Statements*, dan unggah file gambar sampul.


* **Edit Buku**: Memperbarui informasi buku serta mengganti file sampul lama secara otomatis.


* **Hapus Buku**: Menghapus data dari database sekaligus membersihkan file sampul terkait dari server.




* **Pencarian Real-Time**: Fitur pencarian instan pada tabel dashboard berdasarkan judul, penulis, atau kategori.


* **Keamanan Unggahan Berkas**: Validasi ketat ekstensi file (`.jpg`, `.jpeg`, `.png`), batasan ukuran maksimal (2 MB), serta pemeriksaan MIME type menggunakan fungsi `getimagesize()`.


## 💻 Tech Stack

* **Bahasa Pemrograman**: PHP (Native)


* **Database**: MySQL / MariaDB


* **Frontend Framework**: Bootstrap 5.3 & Bootstrap Icons


* **Font**: Google Fonts (Lora & Nunito)


## 📂 Struktur Direktori Proyek

```text
mini-digital-library/
├── config/
│   ├── koneksi.php       # Konfigurasi koneksi database
│   └── cek_login.php     # Pengecekan sesi login pengguna
├── includes/
│   ├── header.php        # Bagian atas halaman (layout)
│   └── footer.php        # Bagian bawah halaman (layout)
├── assets/
│   └── css/
│       └── style.css     # Kustomisasi gaya tampilan
├── uploads/              # Folder penyimpanan file sampul buku
├── index.php             # Halaman utama / Form login[cite: 4]
├── proses_login.php      # Skrip memproses autentikasi login[cite: 7]
├── logout.php            # Skrip mengakhiri sesi[cite: 5]
├── dashboard.php         # Halaman utama setelah login (Statistik & Tabel)[cite: 1]
├── tambah_buku.php       # Form tambah buku[cite: 9]
├── proses_tambah.php     # Skrip memproses penyimpanan buku baru[cite: 8]
├── edit_buku.php         # Form edit buku[cite: 2]
├── proses_edit.php       # Skrip memproses pembaruan data buku[cite: 6]
└── hapus_buku.php        # Skrip menghapus data buku & file sampul[cite: 3]


## 🚀 Panduan Instalasi & Menjalankan Proyek

1. **Clone atau Unduh Repository ini**
Letakkan folder proyek ke dalam direktori server lokal Anda (misalnya `htdocs` di XAMPP).
2. **Konfigurasi Database**
* Nyalakan layanan Apache dan MySQL melalui XAMPP Control Panel.
* Buat database baru di phpMyAdmin, contohnya: `db_perpus`.
* Buat tabel `users` (untuk akun login) dan tabel `buku` dengan kolom-kolom yang sesuai dengan struktur program (seperti `id`, `judul`, `kategori`, `penulis`, `tahun_terbit`, `stok`, dan `sampul`).


3. **Sesuaikan Koneksi**
* Periksa kembali file konfigurasi koneksi pada `config/koneksi.php` untuk memastikan nama host, username, password, dan nama database sudah sesuai dengan pengaturan lokal Anda.


4. **Jalankan Aplikasi**
* Buka browser dan akses URL berikut:
```text
http://localhost/mini-digital-library/


## 🎥 Video Tutorial Terkait

Tonton video demonstrasi dan pembuatan proyek ini melalui tautan YouTube berikut:
👉 [Tonton Video YouTube](https://youtu.be/Rrd0XVzJkI4)