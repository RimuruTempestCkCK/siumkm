<?php
session_start();
include 'koneksi.php';

$edit = false;
$username = "";
$nama = "";
$role = "user";
$id = "";

// Proses Tambah 
if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $role = $_POST['role'];
    $password = md5($_POST['password']);

    if (empty($_POST['id'])) {
        $query = "INSERT INTO users (username, password, nama, role) VALUES ('$username', '$password', '$nama', '$role')";
        $_SESSION['alert'] = ['success', 'User  berhasil ditambahkan.'];
    } else {
        $id = $_POST['id'];
        $query = "UPDATE users SET username='$username', nama='$nama', role='$role'" .
                 (!empty($_POST['password']) ? ", password='$password'" : "") .
                 " WHERE id='$id'";
        $_SESSION['alert'] = ['primary', 'User  berhasil diperbarui.'];
    }

    mysqli_query($conn, $query);
    header("Location: kelola-user.php");
    exit;
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus dulu data UMKM yang terkait (jika ada)
    mysqli_query($conn, "DELETE FROM data_umkm WHERE id_user='$id'");

    // Baru hapus user-nya
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");

    $_SESSION['alert'] = ['danger', 'User berhasil dihapus.'];
    header("Location: kelola-user.php");
    exit;
}


// Proses Edit (ambil data user berdasarkan ID)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $edit = true;
        $username = $row['username'];
        $nama = $row['nama'];
        $role = $row['role'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Kelola User - Admin</title>
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
        <div class="container-fluid mt-3">
            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert'][0] ?> alert-dismissible fade show" role="alert">
                    <strong><?= ucfirst($_SESSION['alert'][0]) ?>!</strong> <?= $_SESSION['alert'][1] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-6"></div>

                <div class="col-lg-12 mt-5">
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalUser ">Tambah User</button>

                    <h4>Daftar User</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>
                                    <td>$no</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['role']}</td>
                                    <td>
                                        <a href='kelola-user.php?edit={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='kelola-user.php?hapus={$row['id']}' onclick=\"return confirm('Hapus user ini?')\" class='btn btn-danger btn-sm'>Hapus</a>
                                    </td>
                                </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah/Edit User -->
                <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel">
                    <div class="modal-dialog" role="document">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUser Label"><?= $edit ? 'Edit User' : 'Tambah User' ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $edit ? $id : '' ?>">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= $username ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $nama ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Password <?= $edit ? '<small>(Kosongkan jika tidak diubah)</small>' : '' ?></label>
                                    <input type="password" name="password" class="form-control" <?= $edit ? '' : 'required' ?>>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="user" <?= $role == 'user' ? 'selected' : '' ?>>User </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="simpan" class="btn btn-success"><?= $edit ? 'Update' : 'Simpan' ?></button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
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

<?php if ($edit): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#modalUser').modal('show');
    });
</script>
<?php endif; ?>


</body>
</html>
