<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Proses simpan produk baru
if (isset($_POST['simpan_produk'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);

    $insert = "INSERT INTO produk (nama_produk, harga_beli, harga_jual, stok, satuan, id_user)
               VALUES ('$nama_produk', '$harga_beli', '$harga_jual', '$stok', '$satuan', '$id_user')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['alert'] = ['success', 'Produk baru berhasil ditambahkan.'];
    } else {
        $_SESSION['alert'] = ['danger', 'Gagal menambahkan produk: ' . mysqli_error($conn)];
    }

    header("Location: transaksi-pemasukan.php");
    exit;
}

// Proses simpan pemasukan
if (isset($_POST['simpan_pemasukan'])) {
    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $jumlah_produk = mysqli_real_escape_string($conn, $_POST['jumlah_produk']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Ambil harga jual dari produk
    $get_produk = mysqli_query($conn, "SELECT harga_jual, stok FROM produk WHERE id_produk='$id_produk'");
    $row_produk = mysqli_fetch_assoc($get_produk);

    if ($row_produk) {
        $harga_jual = $row_produk['harga_jual'];
        $stok_saat_ini = $row_produk['stok'];

        // Cek apakah stok mencukupi
        if ($stok_saat_ini < $jumlah_produk) {
            $_SESSION['alert'] = ['danger', 'Stok produk tidak mencukupi.'];
        } else {
            $jumlah = $harga_jual * $jumlah_produk;

            // Simpan pemasukan
            $query = "INSERT INTO pemasukan (id_user, id_produk, jumlah, tanggal, keterangan)
                      VALUES ('$id_user', '$id_produk', '$jumlah', '$tanggal', '$keterangan')";

            // Update stok
            $update_stok = "UPDATE produk SET stok = stok - $jumlah_produk WHERE id_produk = '$id_produk'";

            if (mysqli_query($conn, $query) && mysqli_query($conn, $update_stok)) {
                $_SESSION['alert'] = ['success', 'Pemasukan berhasil ditambahkan.'];
            } else {
                $_SESSION['alert'] = ['danger', 'Gagal menambahkan pemasukan.'];
            }
        }
    } else {
        $_SESSION['alert'] = ['danger', 'Produk tidak ditemukan.'];
    }

    header("Location: transaksi-pemasukan.php");
    exit;
}

// Ambil data pemasukan user ini
$data_pemasukan = mysqli_query($conn, "
    SELECT pemasukan.*, produk.nama_produk 
    FROM pemasukan 
    LEFT JOIN produk ON pemasukan.id_produk = produk.id_produk 
    WHERE pemasukan.id_user = '$id_user' AND produk.id_user = '$id_user'
    ORDER BY pemasukan.tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Transaksi Pemasukan - UMKM</title>
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

            <a href="cetak_pemasukan.php" target="_blank" class="btn btn-primary mt-2">🖨 Cetak Pemasukan</a>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalTambahProduk">+ Tambah Produk Baru</button>
            <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#modalInputPemasukan">+ Input Pemasukan</button>

            <br><br>
            <div class="row">
                <div class="col-lg-6"></div>

                <!-- Modal Tambah Produk -->
                <div class="modal fade" id="modalTambahProduk" tabindex="-1" role="dialog" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahProdukLabel">Tambah Produk Baru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga Beli</label>
                                    <input type="number" step="0.01" name="harga_beli" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga Jual</label>
                                    <input type="number" step="0.01" name="harga_jual" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Stok Awal</label>
                                    <input type="number" name="stok" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" name="satuan" class="form-control" placeholder="misal: pcs, botol" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" name="simpan_produk" class="btn btn-primary">Simpan Produk</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Input Pemasukan -->
                <div class="modal fade" id="modalInputPemasukan" tabindex="-1" role="dialog" aria-labelledby="modalInputPemasukanLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalInputPemasukanLabel">Input Pemasukan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Produk</label>
                                    <select name="id_produk" class="form-control" required>
                                        <?php
                                        $produk = mysqli_query($conn, "SELECT * FROM produk WHERE id_user = '$id_user'");
                                        if (mysqli_num_rows($produk) == 0):
                                        ?>
                                            <option value="">Belum ada produk. Tambahkan produk terlebih dahulu.</option>
                                        <?php else: ?>
                                            <option value="">-- Pilih Produk --</option>
                                            <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                                                <option value="<?= $p['id_produk'] ?>">
                                                    <?= $p['nama_produk'] ?> (Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?>)
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Produk Terjual</label>
                                    <input type="number" name="jumlah_produk" class="form-control" required>
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
                                <button type="submit" name="simpan_pemasukan" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Daftar Pemasukan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $total = 0;
                                while ($row = mysqli_fetch_assoc($data_pemasukan)) :
                                    $total += $row['jumlah'];
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk'] ?? '-') ?></td>
                                        <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th colspan="2">Rp <?= number_format($total, 2, ',', '.') ?></th>
                                </tr>
                                <?php if ($no === 1): ?>
                                    <tr><td colspan="5" class="text-center">Tidak ada data pemasukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Stok Tersisa -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Stok Produk Tersisa</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $produk_stok = mysqli_query($conn, "SELECT nama_produk, stok, satuan FROM produk WHERE id_user = '$id_user'");
                                while ($p = mysqli_fetch_assoc($produk_stok)):
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                                        <td><?= number_format($p['stok']) ?></td>
                                        <td><?= htmlspecialchars($p['satuan']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($produk_stok) == 0): ?>
                                    <tr><td colspan="4" class="text-center">Belum ada produk terdaftar.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

</body>
</html>
