<?php
session_start();
include "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Set session user lengkap
    $_SESSION['user_id'] = $user['id']; // ini penting!
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // Redirect berdasarkan role
    if ($user['role'] === 'admin') {
        header("Location: dashboard-admin.php");
    } else {
        header("Location: dashboard-user.php");
    }
    exit;
    }
}

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>LOGIN - SISTEM INFORMASI KEUANGAN UMKM</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="h-100">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="#"><h4>SISTEM INFORMASI KEUANGAN UMKM</h4></a>

                                <?php if ($pesan): ?>
                                    <div class="alert alert-danger mt-3"><?php echo $pesan; ?></div>
                                <?php endif; ?>

                                <form class="mt-5 mb-5 login-input" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <button type="submit" class="btn login-form__btn submit w-100">LOGIN</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
</body>
</html>
