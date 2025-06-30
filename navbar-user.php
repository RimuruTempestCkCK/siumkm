<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$id_user = $_SESSION['user_id'] ?? null;

// Cek apakah user sudah isi data UMKM
$cek_data_umkm = mysqli_query($conn, "SELECT * FROM data_umkm WHERE id_user = '$id_user'");
$data_umkm = mysqli_fetch_assoc($cek_data_umkm);
?>

<div class="nk-sidebar">           
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <!-- Dashboard -->
            <li>
                <a href="dashboard-user.php" aria-expanded="false">
                    <i class="icon-home menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
            </li>

            <?php if ($data_umkm): ?>
                <!-- Jika sudah isi data UMKM, tampilkan semua menu -->
                <li class="nav-label">Pelaku UMKM</li>
                <li>
                    <a href="input-modal.php" aria-expanded="false">
                        <i class="icon-wallet menu-icon"></i><span class="nav-text">Input Modal</span>
                    </a>
                </li>
                <li>
                    <a href="transaksi-pemasukan.php" aria-expanded="false">
                        <i class="icon-arrow-up menu-icon"></i><span class="nav-text">Transaksi Pemasukan</span>
                    </a>
                </li>
                <li>
                    <a href="transaksi-pengeluaran.php" aria-expanded="false">
                        <i class="icon-arrow-down menu-icon"></i><span class="nav-text">Transaksi Pengeluaran</span>
                    </a>
                </li>
                <li>
                    <a href="laporan-labarugi.php" aria-expanded="false">
                        <i class="icon-pie-chart menu-icon"></i><span class="nav-text">Laporan Laba Rugi</span>
                    </a>
                </li>
                <li>
                    <a href="laporan-posisi-keuangan.php" aria-expanded="false">
                        <i class="icon-docs menu-icon"></i><span class="nav-text">Laporan Posisi Keuangan</span>
                    </a>
                </li>
            <?php else: ?>
                <!-- Jika belum isi data UMKM, tampilkan menu wajib isi -->
                <li class="nav-label text-danger">Data UMKM Wajib Diisi</li>
                <li>
                    <a href="input-data-umkm.php" aria-expanded="false">
                        <i class="icon-note menu-icon text-danger"></i>
                        <span class="nav-text text-danger font-weight-bold">Lengkapi Data UMKM</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
