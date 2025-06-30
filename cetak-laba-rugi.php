<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

$query_pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) as total_pemasukan FROM pemasukan WHERE id_user = '$id_user'");
$total_pemasukan = mysqli_fetch_assoc($query_pemasukan)['total_pemasukan'] ?? 0;

$query_pengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) as total_pengeluaran FROM pengeluaran WHERE id_user = '$id_user'");
$total_pengeluaran = mysqli_fetch_assoc($query_pengeluaran)['total_pengeluaran'] ?? 0;

$laba_rugi = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Laba Rugi</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        .kop { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .kop h2, .kop h4, .kop p { margin: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 10px; text-align: left; }
        .ttd { margin-top: 50px; width: 100%; display: flex; justify-content: flex-end; }
        .ttd div { text-align: center; width: 250px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="kop">
        <h2>PLUT KUMKM SUMATERA BARAT</h2>
        <p>Transito, Jl. Hiu I, Ulak Karang Selatan, Kecamatan Padang Utara, 
        <br>
        Kota Padang Sumatera Barat, 25134</p>
        <p>Telp. 082140006621| plutsumbar@gmail.com</p>
    </div>

    <h3 style="text-align:center;">LAPORAN LABA RUGI</h3>
    <p style="text-align:center;"><?= date('d F Y') ?></p>

    <table>
        <tr>
            <th>Total Pemasukan</th>
            <td>Rp <?= number_format($total_pemasukan, 2, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Total Pengeluaran</th>
            <td>Rp <?= number_format($total_pengeluaran, 2, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Laba / Rugi</th>
            <td><strong>Rp <?= number_format($laba_rugi, 2, ',', '.') ?> (<?= $laba_rugi >= 0 ? 'Laba' : 'Rugi' ?>)</strong></td>
        </tr>
    </table>

    <div class="ttd">
        <div>
            <p>Padang, <?= date('d F Y') ?></p>
            <p>Mengetahui,</p>
            <p><strong>Admin Sistem</strong></p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">
                <?= $_SESSION['nama_admin'] ?? '________________' ?>
            </p>
            <p>NIP/NIK: <?= $_SESSION['nip_admin'] ?? '______________' ?></p>
        </div>
    </div>
</body>
</html>
