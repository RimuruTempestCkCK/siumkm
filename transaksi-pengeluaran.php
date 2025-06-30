<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Proses simpan pengeluaran
if (isset($_POST['simpan_pengeluaran'])) {
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    if (!empty($jumlah) && !empty($tanggal) && is_numeric($jumlah) && $jumlah > 0) {
        $query = "INSERT INTO pengeluaran (id_user, jumlah, tanggal, keterangan)
                  VALUES ('$id_user', '$jumlah', '$tanggal', '$keterangan')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['success', 'Pengeluaran berhasil ditambahkan.'];
        } else {
            $_SESSION['alert'] = ['danger', 'Gagal menambahkan pengeluaran.'];
        }
    } else {
        $_SESSION['alert'] = ['warning', 'Masukkan jumlah yang valid dan tanggal.'];
    }

    header("Location: transaksi-pengeluaran.php");
    exit;
}

// Ambil data pengeluaran
$data_pengeluaran = mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id_user='$id_user' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Transaksi Pengeluaran - UMKM</title>
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

            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert'][0] ?> alert-dismissible fade show" role="alert">
                    <strong><?= ucfirst($_SESSION['alert'][0]) ?>!</strong> <?= $_SESSION['alert'][1] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <a href="cetak_pengeluaran.php" target="_blank" class="btn btn-danger mt-2 ml-2">🖨 Cetak Pengeluaran</a> 
            <button type="button" class="btn btn-danger mt-2 ml-2" data-toggle="modal" data-target="#modalInputPengeluaran">
                + Input Pengeluaran
            </button>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Daftar Pengeluaran</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($data_pengeluaran)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                        <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($data_pengeluaran) == 0): ?>
                                    <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Input Pengeluaran -->
            <div class="modal fade" id="modalInputPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalInputPengeluaranLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalInputPengeluaranLabel">Input Pengeluaran</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" step="0.01" name="jumlah" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="simpan_pengeluaran" class="btn btn-danger">Simpan</button>
                        </div>
                    </form>
                </div>
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
