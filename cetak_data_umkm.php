<?php
session_start();
include 'koneksi.php';

// Validasi login dan role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Info admin
$_SESSION['nama_admin'] = $_SESSION['nama_admin'] ?? 'Admin Dinas Koperasi';
$_SESSION['nip_admin'] = $_SESSION['nip_admin'] ?? '19781231 200501 1 001';

// Ambil data UMKM
$data_umkm = mysqli_query($conn, "
    SELECT u.nama AS nama_lengkap, d.*
    FROM data_umkm d
    JOIN users u ON d.id_user = u.id
    ORDER BY u.nama ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data UMKM</title>
    <link rel="icon" href="images/favicon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 40px;
        }

        .kop-laporan {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-laporan img {
            float: left;
            width: 70px;
            margin-right: 15px;
        }

        .kop-laporan h2, .kop-laporan h4, .kop-laporan p {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
        }

        .ttd {
            margin-top: 50px;
            width: 300px;
            float: right;
            text-align: center;
        }

        .ttd p {
            margin: 4px 0;
        }

        @media print {
            @page {
                margin: 20mm;
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

<h4 style="text-align:center; margin-bottom: 5px;">LAPORAN DATA UMKM</h4>
<p style="text-align:center;"><?= date('d F Y') ?></p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pemilik</th>
            <th>Nama Usaha</th>
            <th>Bidang Usaha</th>
            <th>Alamat</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data_umkm)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['nama_usaha']) ?></td>
                <td><?= htmlspecialchars($row['bidang_usaha']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
            </tr>
        <?php endwhile; ?>
        <?php if (mysqli_num_rows($data_umkm) == 0): ?>
            <tr><td colspan="6" style="text-align:center;">Tidak ada data UMKM.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="ttd">
    <p>Padang, <?= date('d F Y') ?></p>
    <p>Mengetahui,</p>
    <p><strong>Admin Sistem</strong></p>
    <br><br><br>
    <p style="text-decoration: underline; font-weight: bold;">
        <?= $_SESSION['nama_admin'] ?>
    </p>
    <p>NIP/NIK: <?= $_SESSION['nip_admin'] ?></p>
</div>

</body>
</html>
