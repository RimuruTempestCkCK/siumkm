<?php
include 'koneksi.php';

// Proses Tambah
if (isset($_POST['tambah_user'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $insert = mysqli_query($conn, "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')");
    if ($insert) {
        echo "<script>alert('User berhasil ditambahkan'); location.href='kelola_data_user.php';</script>";
    }
}

// Proses Edit
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $update = mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username', password='$password', role='$role' WHERE id=$id");
    } else {
        $update = mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username', role='$role' WHERE id=$id");
    }

    if ($update) {
        echo "<script>alert('User berhasil diubah'); location.href='kelola_data_user.php';</script>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    if ($delete) {
        echo "<script>alert('User berhasil dihapus'); location.href='kelola_data_user.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />
  
    <title>Admin - SISTEM INFORMASI KEUANGAN UMKM</title>
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
        
        <?php include 'sidebar.php'; ?>

         <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            
            <!-- row -->

            <div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data User</h4>
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">+ Tambah User</button>
            <div class="table-responsive">
                <table class="table table-bordered verticle-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <span class="label gradient-1 btn-rounded text-white px-2 py-1"><?= htmlspecialchars($row['role']) ?></span>
                            </td>
                            <td>
                                <span>
                                    <a href="#" data-toggle="modal" data-target="#modalEdit<?= $row['id'] ?>" data-placement="top" title="Edit">
                                        <i class="fa fa-pencil color-muted m-r-5"></i>
                                    </a>
                                    <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus user ini?')" data-toggle="tooltip" data-placement="top" title="Hapus">
                                        <i class="fa fa-close color-danger"></i>
                                    </a>
                                </span>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Edit User</h5></div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Password (isi jika ingin ubah)</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select name="role" class="form-control">
                                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="owner" <?= $row['role'] == 'owner' ? 'selected' : '' ?>>Owner</option>
                                                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit_user" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah User</h5></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah_user" class="btn btn-primary">Tambah User</button>
                </div>
            </div>
        </form>
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

    <script src="./plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="./plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="./plugins/tables/js/datatable-init/datatable-basic.min.js"></script>

</body>

</html>