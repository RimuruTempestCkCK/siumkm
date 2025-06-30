<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Cek apakah user sudah pernah input data UMKM
$cek = mysqli_query($conn, "SELECT * FROM data_umkm WHERE id_user = '$id_user'");
$data_existing = mysqli_fetch_assoc($cek);

// Proses simpan data baru
if (isset($_POST['simpan_umkm'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_usaha']);
    $bidang = mysqli_real_escape_string($conn, $_POST['bidang_usaha']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if ($data_existing) {
        // Update
        $query = "UPDATE data_umkm SET nama_usaha='$nama', bidang_usaha='$bidang', alamat='$alamat', deskripsi='$deskripsi' WHERE id_user='$id_user'";
        $msg = 'Data UMKM berhasil diperbarui.';
    } else {
        // Insert
        $query = "INSERT INTO data_umkm (id_user, nama_usaha, bidang_usaha, alamat, deskripsi)
                  VALUES ('$id_user', '$nama', '$bidang', '$alamat', '$deskripsi')";
        $msg = 'Data UMKM berhasil disimpan.';
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['success', $msg];
    } else {
        $_SESSION['alert'] = ['danger', 'Gagal menyimpan data UMKM.'];
    }

    header("Location: input-data-umkm.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Input Data UMKM</title>
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
                                    <!-- <li><a href="app-profile.php"><i class="icon-user"></i> <span>Profile</span></a></li> -->
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
            <h4 class="mb-3">Formulir Data UMKM</h4>

            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert'][0] ?> alert-dismissible fade show">
                    <strong><?= ucfirst($_SESSION['alert'][0]) ?>!</strong> <?= $_SESSION['alert'][1] ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Usaha</label>
                            <input type="text" name="nama_usaha" class="form-control" required
                                   value="<?= isset($data_existing['nama_usaha']) ? htmlspecialchars($data_existing['nama_usaha']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" class="form-control"
                                   value="<?= isset($data_existing['bidang_usaha']) ? htmlspecialchars($data_existing['bidang_usaha']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"><?= isset($data_existing['alamat']) ? htmlspecialchars($data_existing['alamat']) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= isset($data_existing['deskripsi']) ? htmlspecialchars($data_existing['deskripsi']) : '' ?></textarea>
                        </div>
                        <button type="submit" name="simpan_umkm" class="btn btn-primary">Simpan</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
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
