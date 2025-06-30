<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil data
$query_modal = mysqli_query($conn, "SELECT SUM(jumlah_modal) AS total_modal FROM modal WHERE id_user = '$id_user'");
$total_modal = mysqli_fetch_assoc($query_modal)['total_modal'] ?? 0;

$query_pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_pemasukan FROM pemasukan WHERE id_user = '$id_user'");
$total_pemasukan = mysqli_fetch_assoc($query_pemasukan)['total_pemasukan'] ?? 0;

$query_pengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) AS total_pengeluaran FROM pengeluaran WHERE id_user = '$id_user'");
$total_pengeluaran = mysqli_fetch_assoc($query_pengeluaran)['total_pengeluaran'] ?? 0;

// Hitung keuangan
$total_aset = $total_modal + $total_pemasukan;
$total_liabilitas = $total_pengeluaran;
$total_ekuitas = $total_aset - $total_liabilitas;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Posisi Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .kop-laporan {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .kop-laporan img {
            float: left;
            width: 70px;
            margin-right: 15px;
        }

        .kop-laporan div {
            display: inline-block;
            width: calc(100% - 90px);
        }

        h2, h4, p {
            margin: 0;
            padding: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .ttd {
            margin-top: 60px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .ttd div {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="kop-laporan clearfix">
    <img src="images/logo.png" alt="Logo">
    <div>
        <h2>PLUT KUMKM SUMATERA BARAT</h2>
        <p>Transito, Jl. Hiu I, Ulak Karang Selatan, Kecamatan Padang Utara, 
        <br>
        Kota Padang Sumatera Barat, 25134</p>
        <p>Telp. 082140006621| plutsumbar@gmail.com</p>
    </div>
    <div style="clear: both;"></div>
</div>

<h4 class="text-center">LAPORAN POSISI KEUANGAN (NERACA SEDERHANA)</h4>
<p class="text-center"><?= date('d F Y') ?></p>

<table>
    <tr>
        <th colspan="2">Aset</th>
    </tr>
    <tr>
        <td>Modal Awal</td>
        <td>Rp <?= number_format($total_modal, 2, ',', '.') ?></td>
    </tr>
    <tr>
        <td>Pemasukan</td>
        <td>Rp <?= number_format($total_pemasukan, 2, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Total Aset</th>
        <th>Rp <?= number_format($total_aset, 2, ',', '.') ?></th>
    </tr>

    <tr>
        <th colspan="2">Liabilitas</th>
    </tr>
    <tr>
        <td>Pengeluaran</td>
        <td>Rp <?= number_format($total_liabilitas, 2, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Total Liabilitas</th>
        <th>Rp <?= number_format($total_liabilitas, 2, ',', '.') ?></th>
    </tr>

    <tr>
        <th colspan="2">Ekuitas</th>
    </tr>
    <tr>
        <td>Ekuitas (Aset - Liabilitas)</td>
        <td><strong>Rp <?= number_format($total_ekuitas, 2, ',', '.') ?></strong></td>
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
