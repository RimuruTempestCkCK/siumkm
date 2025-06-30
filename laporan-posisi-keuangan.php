<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil total modal awal
$query_modal = mysqli_query($conn, "SELECT SUM(jumlah_modal) AS total_modal FROM modal WHERE id_user = '$id_user'");
$total_modal = mysqli_fetch_assoc($query_modal)['total_modal'] ?? 0;

// Ambil total pemasukan
$query_pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_pemasukan FROM pemasukan WHERE id_user = '$id_user'");
$total_pemasukan = mysqli_fetch_assoc($query_pemasukan)['total_pemasukan'] ?? 0;

// Ambil total pengeluaran
$query_pengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) AS total_pengeluaran FROM pengeluaran WHERE id_user = '$id_user'");
$total_pengeluaran = mysqli_fetch_assoc($query_pengeluaran)['total_pengeluaran'] ?? 0;

// Hitung posisi keuangan
$total_aset = $total_modal + $total_pemasukan;
$total_liabilitas = $total_pengeluaran;
$total_ekuitas = $total_aset - $total_liabilitas;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Posisi Keuangan - UMKM</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>
</div>

<div id="main-wrapper">
    <div class="nav-header">
        <div class="brand-logo">
            <a href="index.php">
                <b class="logo-abbr"><img src="images/logo.png" alt=""> </b>
                <span class="logo-compact"><img src="./images/logo-compact.png" alt=""></span>
                <span class="brand-title">
                    <span class="brand-title text-white" style="font-size: 18px;">SI Keuangan UMKM</span>
                </span>
            </a>
        </div>
    </div>

    <div class="header">
        <div class="header-content clearfix">
            <div class="nav-control">
                <div class="hamburger"><span class="toggle-icon"><i class="icon-menu"></i></span></div>
            </div>
            <div class="header-right">
                <ul class="clearfix">
                    <li class="icons dropdown">
                        <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                            <span class="activity active"></span>
                            <img src="images/user/1.png" height="40" width="40" alt="">
                        </div>
                        <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                            <div class="dropdown-content-body">
                                <ul>
                                    <hr class="my-2">
                                    <li><a href="index.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php include 'navbar-user.php'; ?>

    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title">Laporan Posisi Keuangan (Neraca Sederhana)</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr class="table-primary">
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

                        <tr class="table-warning">
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

                        <tr class="table-success">
                            <th colspan="2">Ekuitas</th>
                        </tr>
                        <tr>
                            <td>Ekuitas (Aset - Liabilitas)</td>
                            <td><strong>Rp <?= number_format($total_ekuitas, 2, ',', '.') ?></strong></td>
                        </tr>
                    </table>
                    <a href="cetak_posisi_keuangan.php" target="_blank" class="btn btn-primary">🖨 Cetak Laporan</a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8"></div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</div>

<!-- Script bawaan -->
<script src="plugins/common/common.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>
<script src="./plugins/chart.js/Chart.bundle.min.js"></script>
<script src="./plugins/circle-progress/circle-progress.min.js"></script>
<script src="./plugins/d3v3/index.js"></script>
<script src="./plugins/topojson/topojson.min.js"></script>
<script src="./plugins/datamaps/datamaps.world.min.js"></script>
<script src="./plugins/raphael/raphael.min.js"></script>
<script src="./plugins/morris/morris.min.js"></script>
<script src="./plugins/moment/moment.min.js"></script>
<script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
<script src="./plugins/chartist/js/chartist.min.js"></script>
<script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>
<script src="./js/dashboard/dashboard-1.js"></script>

</body>
</html>
