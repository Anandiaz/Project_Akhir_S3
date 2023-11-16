<?php
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWEB</title>
    <link rel="stylesheet" href="./styles/admin.css">
    <link rel="stylesheet" href="./styles/style.css">

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
                    <th>Judul Film</th>
                    <th>Rating</th>
                    <th>Poster Film</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'include/koneksi.php';
                $sql = "SELECT * FROM film left join rating on film.id_rating = rating.id_rating";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['judul'] . "</td>";
                    echo "<td>" . $row['nama_rating'] . "</td>";
                    echo "<td><img src='img/" . $row['gambar'] . "' alt='Referensi' width='100'></td>";
                    echo "<td class='action-buttons'>
                                <span class='update-button' onclick='window.location.href=\"update.php?id=" . $row['id_film'] . "\"'>Update</span>
                                <span class='delete-button' onclick='window.location.href=\"hapus.php?id=" . $row['id_film'] . "\"'>Hapus</span>
                            </td>";
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>
        <?php include "toggleButton.php" ?>
    </div>
    <script src="script-index.js"></script>
</body>
<script>
    function updateTime() {
        var date = new Date();
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZoneName: 'short'
        };
        document.getElementById('date').innerHTML = date.toLocaleDateString('id-ID', options);
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>

</html>