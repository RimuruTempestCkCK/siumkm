<?php
session_start();
include 'koneksi.php';



// Ambil data semua UMKM
$data_umkm = mysqli_query($conn, "SELECT u.nama_lengkap, d.* FROM data_umkm d JOIN users u ON d.id_user = u.id_user ORDER BY u.nama_lengkap ASC");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Data UMKM</title>
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
                <b class="logo-abbr"><img src="images/logo.png" alt=""></b>
                <span class="logo-compact"><img src="./images/logo-compact.png" alt=""></span>
                <span class="brand-title"><img src="images/logo-text.png" alt=""></span>
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
                                    <li><a href="app-profile.php"><i class="icon-user"></i> <span>Profile</span></a></li>
                                    <hr class="my-2">
                                    <li><a href="page-login.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php include 'navbar-admin.php'; ?>

    <div class="content-body">
        <div class="container mt-4">
            <h3 class="mb-4">Laporan Data UMKM</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelaku</th>
                            <th>Nama UMKM</th>
                            <th>Jenis Usaha</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($data_umkm)) :
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($row['nama_umkm']) ?></td>
                            <td><?= htmlspecialchars($row['jenis_usaha']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['kontak']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($data_umkm) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data UMKM.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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

<?php if ($edit): ?>
<script>
    // Saat halaman selesai dimuat, buka modal edit
    document.addEventListener('DOMContentLoaded', function () {
        $('#modalUser ').modal('show');
    });
</script>
<?php endif; ?>

</body>
</html>
