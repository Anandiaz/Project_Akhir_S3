<?php
require 'include/koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>
        alert('Semua data harus diisi');
        </script>";
    } else {
        $cek = mysqli_query($conn, "SELECT username FROM akun_user WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>
            alert('Username sudah terdaftar, silahkan gunakan username lain');
            </script>";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = mysqli_query($conn, "INSERT INTO akun_user (id, username, email, password) VALUES(NULL , '$username', '$email', '$password')");
            if ($query) {
                echo "<script>
                alert('Registrasi berhasil, silahkan login');
                window.location.href = 'login.php';
                </script>";
            } else {
                echo "<script>
                alert('Registrasi gagal, silahkan coba lagi');
                </script>";
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/login.css">
</head>

<body>
    <nav class="navbar">
        <span class="nav_logo">
            HASANI
        </span>
        <div class="nav_item">
            <a href="index.php">Home</a>
            <a href="#">Movies</a>
            <a href="#">History</a>
        </div>
        <div class="nav_button">
            <input type="button" class="register" value="REGISTER" onclick="window.location.href = 'register.php'">
            <input type="button" class="login" value="LOGIN" onclick="window.location.href = 'login.php'">
        </div>

    </nav>
    <div class="container">
        <div class="login-bd">
            <div class="center">
                <h1>WELCOME BACK</h1>
            </div>
            <div class="center">
                <form action="" method="post" class="form-login">
                    <div class="form-group">
                        <label for="pass">Username</label>
                        <input type="text" class="form-control " id="username" name="username" placeholder="Enter your username">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control " id="password" name="password" placeholder="Enter your Password">
                    </div>
                    <div class="form-group">
                        <a href="register.php">
                            <button type="submit" name="register" class="btn" id="register">REGISTER</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php include "toggleButton.php" ?>
    </div>
    <script src="script-index.js"></script>
</body>

</html>