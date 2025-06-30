<?php
session_start();
include 'koneksi.php';

// Jika form login dikirim, proses login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Redirect berdasarkan role
        if ($data['role'] == 'admin') {
            header("Location: dashboard-admin.php");
        } else {
            header("Location: dashboard-user.php");
        }
        exit;
    } else {
        $_SESSION['alert'] = ['danger', 'Username atau password salah'];
        header("Location: login.php");
        exit;
    }
}

// --------------------------------------------
// Mulai dari sini adalah halaman input-modal.php
// --------------------------------------------

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // sebaiknya arahkan ke login
    exit;
}

$id_user = $_SESSION['user_id'];

// Proses simpan modal
if (isset($_POST['simpan_modal'])) {
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah_modal']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $query = "INSERT INTO modal (id_user, jumlah_modal, tanggal, keterangan)
              VALUES ('$id_user', '$jumlah', '$tanggal', '$keterangan')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['success', 'Data modal berhasil disimpan.'];
    } else {
        $_SESSION['alert'] = ['danger', 'Gagal menyimpan data modal.'];
    }

    header("Location: input-modal.php");
    exit;
}

// Ambil data modal user
$data_modal = mysqli_query($conn, "SELECT * FROM modal WHERE id_user = '$id_user' ORDER BY tanggal DESC");

// Hitung total modal
$total_modal = mysqli_query($conn, "SELECT SUM(jumlah_modal) AS total FROM modal WHERE id_user = '$id_user'");
$total_modal_row = mysqli_fetch_assoc($total_modal);
$jumlah_total_modal = $total_modal_row['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Input Modal - UMKM</title>
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
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <h4 class="card-title d-inline">Data Modal</h4>
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalInput">+ Tambah Modal</button>
            <br><br>
            <hr>
            <h4 class="mt-4">Riwayat Modal</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jumlah Modal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($data_modal) > 0):
                            while ($row = mysqli_fetch_assoc($data_modal)):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td>Rp <?= number_format($row['jumlah_modal'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr><td colspan="4" class="text-center">Belum ada data modal.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total Modal Saat Ini</th>
                            <th colspan="2">Rp <?= number_format($jumlah_total_modal, 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Modal Input Modal -->
            <div class="modal fade" id="modalInput" tabindex="-1" role="dialog" aria-labelledby="modalInputLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalInputLabel">Input Modal Baru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Jumlah Modal</label>
                                <input type="number" step="0.01" name="jumlah_modal" class="form-control" required>
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
                            <button type="submit" name="simpan_modal" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
