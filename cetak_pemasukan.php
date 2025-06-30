<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil data pemasukan user + join ke tabel produk
$query = "
    SELECT pemasukan.*, produk.nama_produk 
    FROM pemasukan 
    LEFT JOIN produk ON pemasukan.id_produk = produk.id_produk 
    WHERE pemasukan.id_user = '$id_user' 
    ORDER BY pemasukan.tanggal ASC
";

$data_pemasukan = mysqli_query($conn, $query);

if (!$data_pemasukan) {
    die("Query gagal: " . mysqli_error($conn));
}


$nama_admin = $_SESSION['nama_admin'] ?? '________________';
$nip_admin = $_SESSION['nip_admin'] ?? '______________';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Pemasukan - UMKM</title>
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

        .text-center {
            text-align: center;
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
<p class="text-center">Tanggal Cetak: <?= date('d F Y') ?></p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $total = 0;
        while ($row = mysqli_fetch_assoc($data_pemasukan)) :
            $total += $row['jumlah'];
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td><?= htmlspecialchars($row['nama_produk'] ?? '-') ?></td>
                <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
        <?php endwhile; ?>

        <?php if ($no === 1): ?>
            <tr><td colspan="5" class="text-center">Tidak ada data pemasukan.</td></tr>
        <?php else: ?>
            <tr>
                <th colspan="3">Total</th>
                <th colspan="2">Rp <?= number_format($total, 2, ',', '.') ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="ttd">
    <div>
        <p>Padang, <?= date('d F Y') ?></p>
        <p>Mengetahui,</p>
        <p><strong>Admin Sistem</strong></p>
        <br><br><br>
        <p style="text-decoration: underline; font-weight: bold;"><?= $nama_admin ?></p>
        <p>NIP/NIK: <?= $nip_admin ?></p>
    </div>
</div>

</body>
</html>
