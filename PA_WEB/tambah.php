<?php
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// mengecek apakah ada data yang dikirim melalui form
if (isset($_POST['submit'])) {
    // menyimpan data yang dikirim melalui form ke variabel
    $judul = $_POST['judul'];
    $gambar = $_FILES['gambar']['name'];
    $rating = $_POST['rating'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if (!empty($judul) && !empty($gambar) && !empty($rating)) {
        $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
        if ($ekstensi == "jpg" || $ekstensi == "jpeg" || $ekstensi == "png") {
            $path = "img/" . $gambar;

            if (move_uploaded_file($tmp, $path)) {
                $film_query = "INSERT INTO film (id_film, judul, gambar, id_rating) VALUES ('', '$judul', '$gambar', '$rating')";
                $film_result = mysqli_query($conn, $film_query);

                if ($film_result) {
                    echo "<script>
                    alert('Data film berhasil ditambahkan');
                    </script>";
                } else {
                    echo "<script>
                    alert('Data film ggagal ditambahkan');
                    </script>";
                }
            } else {
                echo "<script>
                alert('Gambar gagal diupload');
                </script>";
            }
        } else {
            echo "<script>
            alert('Gambar harus berformat jpg, jpeg, atau png');
            </script>";
        }
    } else {
        echo "<script>
        alert('Semua data harus diisi');
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWEB</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/admin.css">

</head>

<body>
    <nav class="navbar">
        <span class="nav_logo">
            HASANI
        </span>
        <div class="nav_item">
            <a href="index.php">Home</a>
            <a href="kursi.php">Kursi</a>
            <a href="tambah.php">Tambah Film</a>
            <a href="jam.php">Tambah Jam</a>
            <a href="lihat.php">Dashboard</a>
        </div>
        <div class="nav_button">
            <?php
            if (isset($_SESSION['login'])) {
            ?>
                <input type="button" class="register" value="LOGOUT" onclick="window.location.href = 'logout.php'">
            <?php
            } elseif (!isset($_SESSION['login'])) {
            ?>
                <input type="button" class="register" value="REGISTER" onclick="window.location.href = 'register.php'">
                <input type="button" class="login" value="LOGIN" onclick="window.location.href = 'login.php'">
            <?php
            }
            ?>
        </div>

    </nav>

    <div class="container">
        <div class="title">
            Input Data Film
        </div>

        <div class="form">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form_group">
                    <label for="judul">Judul Film</label>
                    <input type="text" name="judul" id="judul" placeholder="Masukkan judul film">
                </div>
                <div class="form_group">
                    <label for="rating">Rating Film:</label>
                    <select id="rating" name="rating">
                        <option value="1">SU</option>
                        <option value="2">R</option>
                        <option value="3">D</option>
                    </select>
                </div>
                <div class="form_group">
                    <label for="gambar">Gambar</label>
                    <input type="file" name="gambar" id="gambar">
                </div>
                <div class="form_group">
                    <input type="submit" name="submit" value="SUBMIT">
                </div>
            </form>
        </div>
    </div>
    <?php include "toggleButton.php" ?>
    <script src="script-index.js"></script>
</body>

</html>