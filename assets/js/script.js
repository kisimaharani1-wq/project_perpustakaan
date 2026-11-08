document.addEventListener('DOMContentLoaded', function () {

    // 1. Popup konfirmasi hapus (SweetAlert2)
    document.querySelectorAll('.btn-hapus').forEach(function (tombol) {
        tombol.addEventListener('click', function (e) {
            e.preventDefault();               // tahan dulu, jangan langsung pindah halaman
            const tujuan = this.getAttribute('href');
            const judul  = this.dataset.judul;

            Swal.fire({
                title: 'Hapus buku ini?',
                text: '"' + judul + '" akan dihapus beserta file sampulnya.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-orange ms-2',
                    cancelButton: 'btn btn-cream border'
                }
            }).then(function (hasil) {
                if (hasil.isConfirmed) {
                    window.location.href = tujuan;   // lanjut ke hapus_buku.php
                }
            });
        });
    });

    // 2. Pencarian buku langsung (tanpa reload)
    const kotakCari = document.getElementById('cari');
    if (kotakCari) {
        kotakCari.addEventListener('input', function () {
            const kata = this.value.toLowerCase().trim();
            document.querySelectorAll('#tabel-buku tbody tr.baris-buku').forEach(function (baris) {
                const cocok = baris.dataset.cari.includes(kata);
                baris.style.display = cocok ? '' : 'none';
            });
        });
    }

});