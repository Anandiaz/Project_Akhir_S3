<?php
require 'include/koneksi.php';

session_start();
if (isset($_SESSION["login"])) {
    header("Location:index.php");
}

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $cek = mysqli_query($conn, "SELECT * FROM akun_user WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $row = mysqli_fetch_assoc($cek);
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["role"] = $row["role"];
            $_SESSION["id"] = $row["id"];
            if ($row["role"] == "admin") {
                echo "<script>
            alert('Login berhasil');
            window.location.href = 'lihat.php'
            </script>";
            } else {
                echo "<script>
            alert('Login berhasil');
            window.location.href = 'index.php'
            </script>";
            }
            exit;
        } else {
            echo "<script> 
            alert('Password salah, silahkan coba lagi');
            </script>";
        }
    } else {
        echo "<script> 
        alert ('Email tidak terdaftar, silahkan registrasi terlebih dahulu');
        </script>";
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
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control " id="password" name="password" placeholder="Enter your Password">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn" id="masuk">LOGIN</button>
                    </div>
                    or
                    <div class="form-group">
                        <a href="register.php">
                            <button type="button" class="btn" id="daftar">REGISTER</button>
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