<?php
session_start();
include 'koneksi.php';

// Ambil ID user dari session
$id_user = $_SESSION['user_id'] ?? null;

// Cek apakah data UMKM sudah diisi
$cek_data_umkm = mysqli_query($conn, "SELECT * FROM data_umkm WHERE id_user = '$id_user'");
$data_umkm = mysqli_fetch_assoc($cek_data_umkm);

// Dapatkan nama file sekarang
$current_file = basename($_SERVER['PHP_SELF']);

// Jika data UMKM belum diisi dan file bukan input-data-umkm.php, arahkan
// if (!$data_umkm && !in_array($current_file, ['input-data-umkm.php'])) {
//     header("Location: input-data-umkm.php'?warning=1");
//     exit;
// }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="SI Keuangan UMKM" />
  
    <title>SISTEM INFORMASI KEUANGAN UMKM</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
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
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
             
                <div class="header-right">
                    <ul class="clearfix">
                        
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="images/user/1.png" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <!-- <li>
                                            <a href="app-profile.php"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li> -->
                                        <!-- <li>
                                            <a href="javascript:void()">
                                                <i class="icon-envelope-open"></i> <span>Inbox</span> <div class="badge gradient-3 badge-pill gradient-1">3</div>
                                            </a>
                                        </li> -->
                                        
                                        <hr class="my-2">
                                        <!-- <li>
                                            <a href="page-lock.php"><i class="icon-lock"></i> <span>Lock Screen</span></a>
                                        </li> -->
                                        <li><a href="index.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->
        
         <!--**********************************
            Sidebar start
        ***********************************-->
        
        <?php include 'navbar-user.php'; ?>

         <!--**********************************
            Sidebar end
        ***********************************-->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="container-fluid mt-3">
                
               <?php if (!$data_umkm): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> Anda belum mengisi data UMKM. Silakan lengkapi terlebih dahulu 
                <a href="input-data-umkm.php" class="alert-link">di sini</a> untuk melanjutkan penggunaan sistem.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                </div>
                <?php endif; ?>
    
                <div class="alert alert-primary shadow-sm p-4">
                <h4 class="mb-2">Selamat Datang di Sistem Informasi Keuangan UMKM</h4>
                <p class="mb-0">Sistem ini membantu Anda dalam mencatat pemasukan dan pengeluaran, serta menyajikan laporan keuangan seperti Laba Rugi dan Posisi Keuangan secara otomatis.
                 <br>Silakan lengkapi data UMKM Anda terlebih dahulu untuk mulai menggunakan seluruh fitur sistem.
                </p>
            </div>

            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        
        
        <!--**********************************
            Footer start
        ***********************************-->
        <?php include 'footer.php'; ?>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
    <!-- Datamap -->
    <script src="./plugins/d3v3/index.js"></script>
    <script src="./plugins/topojson/topojson.min.js"></script>
    <script src="./plugins/datamaps/datamaps.world.min.js"></script>
    <!-- Morrisjs -->
    <script src="./plugins/raphael/raphael.min.js"></script>
    <script src="./plugins/morris/morris.min.js"></script>
    <!-- Pignose Calender -->
    <script src="./plugins/moment/moment.min.js"></script>
    <script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
    <!-- ChartistJS -->
    <script src="./plugins/chartist/js/chartist.min.js"></script>
    <script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>



    <script src="./js/dashboard/dashboard-1.js"></script>

</body>

</php>
