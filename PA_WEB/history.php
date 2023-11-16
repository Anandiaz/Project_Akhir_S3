<?php 
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            <a href="#">Movies</a>
            <a href="#">History</a>
        </div>
        <div class="nav_button">
            <input type="button" class="register" value="REGISTER" onclick="window.location.href = 'register.php'">
            <input type="button" class="login" value="LOGIN" onclick="window.location.href = 'login.php'">
        </div>

    </nav>
    <div class="container">
    <div class="tabel">
        <h1>Data Film
            <p class="date" id="date">
                <?php
                date_default_timezone_set('Asia/Jakarta');
                echo date('l, d F Y, H:i:s T');
                ?>
            </p>

        </h1>
        <table>
            <thead>
                <tr>
                    <th>ID PESANAN</th>
                    <th>NAMA FILM</th>
                    <th>KURSI YANG DIPESAN</th>
                    <th>JAM TAYANG</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'include/koneksi.php';
                $sql = "SELECT r.id_pesan, f.judul, r.kursi, t.jam_tayang FROM reservations r INNER JOIN tayang t on t.id_tayang = r.id_tayang INNER JOIN film f on t.id_film = f.id_film";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_pesan'] . "</td>";
                    echo "<td>" . $row['judul'] . "</td>";
                    echo "<td>". $row['kursi'] ."</td>";
                    echo "<td>". $row['jam_tayang'] ."</td>";
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>
        <?php include "toggleButton.php" ?>
    </div>
    </div>
    <script src="script-index.js"></script>
</body>

</html>