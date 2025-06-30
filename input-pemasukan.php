<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Proses simpan pemasukan
if (isset($_POST['simpan_pemasukan'])) {
    $jumlah = trim($_POST['jumlah']);
    $tanggal = trim($_POST['tanggal']);
    $keterangan = trim($_POST['keterangan']);

    if (!empty($jumlah) && !empty($tanggal)) {
        $jumlah = mysqli_real_escape_string($conn, $jumlah);
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $keterangan = mysqli_real_escape_string($conn, $keterangan);

        $query = "INSERT INTO pemasukan (id_user, jumlah, tanggal, keterangan)
                  VALUES ('$id_user', '$jumlah', '$tanggal', '$keterangan')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['success', 'Pemasukan berhasil ditambahkan.'];
        } else {
            $_SESSION['alert'] = ['danger', 'Gagal menambahkan pemasukan.'];
        }
    } else {
        $_SESSION['alert'] = ['warning', 'Jumlah dan tanggal wajib diisi.'];
    }

    header("Location: input-pemasukan.php");
    exit;
}

// Ambil data pemasukan user ini
$data_pemasukan = mysqli_query($conn, "SELECT * FROM pemasukan WHERE id_user='$id_user' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Input Pemasukan - UMKM</title>
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
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
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
    <?php include 'navbar-user.php'; ?>

    <div class="content-body">
        <div class="container-fluid mt-3">

            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert'][0] ?> alert-dismissible fade show" role="alert">
                    <strong><?= ucfirst($_SESSION['alert'][0]) ?>!</strong> <?= $_SESSION['alert'][1] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Form Input Pemasukan</h4></div>
                        <div class="card-body">
                            <form method="POST">
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
                                <button type="submit" name="simpan_pemasukan" class="btn btn-success">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Opsional: Tabel Data Pemasukan -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Riwayat Pemasukan</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($data_pemasukan)) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                                <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <?php if (mysqli_num_rows($data_pemasukan) === 0): ?>
                                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php include 'footer.php'; ?>
</div>

<!-- Scripts -->
<script src="plugins/common/common.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>
</body>
</html>
