<?php
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$stmt = "SELECT * FROM film";
$data = mysqli_query($conn, $stmt);
while ($row = mysqli_fetch_assoc($data)) {
    $film[] = $row;
}


if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $jam = $_POST['tayang'];

    if (empty($jam)) {
        echo "<script>
            alert('Mohon isi jam tayang');
            window.location.href = 'jam.php'; // Ganti 'namafile.php' dengan nama file Anda
            </script>";
        exit(); // Hentikan eksekusi script jika error
    }

    $sql = "INSERT INTO tayang (id_tayang, id_film, jam_tayang) VALUES ('','$judul', '$jam')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>
        alert('Data film berhasil ditambahkan');
        </script>";
    } else {
        echo "<script>
        alert('Data film gagagal ditambahkan');
        </script>";
    }
}
?>



<!DOCTYPE html>
<html>

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
                    <label for="judul">Judul Film:</label>
                    <select id="judul" name="judul">
                        <?php foreach ($film as $flm) : ?>
                            <option value="<?= $flm['id_film'] ?>"><?= $flm['judul'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form_group">
                    <label for="tayang">Jam Tayang</label>
                    <input type="datetime-local" name="tayang" id="tayang" min="" placeholder="Masukkan jam tayang">
                </div>

                <div class="form_group">
                    <input type="submit" name="submit" value="SUBMIT">
                </div>
            </form>
            <?php include "toggleButton.php" ?>
        </div>
    </div>
    <script src="script-index.js"></script>
</body>

<script>
    // Dapatkan elemen input datetime
    var datetimeInput = document.getElementById('tayang');

    // Dapatkan waktu saat ini
    var currentTime = new Date().toISOString().slice(0, 16);

    // Atur nilai minimum untuk memastikan waktu yang sudah lewat tidak dapat dipilih
    datetimeInput.min = currentTime;
</script>


</html>