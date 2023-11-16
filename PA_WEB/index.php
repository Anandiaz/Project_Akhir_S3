<?php
session_start();
require 'include/koneksi.php';


$query = mysqli_query($conn, "SELECT * FROM film");

if (mysqli_num_rows($query) > 0) {
    $film_data = array();

    while ($film_row = mysqli_fetch_assoc($query)) {
        $film_data[] = $film_row;
    }
    $_SESSION['film_titles'] = array_column($film_data, 'judul');
} else {

    echo "Tidak ada data film";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWEB</title>
    <link rel="stylesheet" href="./styles/style.css">

</head>

<body>
    <nav class="navbar">
        <span class="nav_logo">
            HASANI
        </span>
        <div class="nav_item">
            <?php
            if (isset($_SESSION['login']) && $_SESSION['role'] == 'admin') {
            ?>
                <a href="index.php">Home</a>
                <a href="kursi.php">Kursi</a>
                <a href="tambah.php">Tambah Film</a>
                <a href="jam.php">Tambah Jam</a>
                <a href="lihat.php">Dashboard</a>
            <?php
            } else {
            ?>
                <a href="index.php">Home</a>
                <a href="#">Movies</a>
                <a href="history.php">History</a>
            <?php
            }
            ?>

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

    <div class="heroes">
        <img src="./img/heores.png" alt="">

    </div>

    <section>
        <div class="container">
            <div class="title">
                Movie This Week
            </div>

            <div class="film test">
                <div class="row">

                    <?php foreach ($film_data as $film) : ?>
                        <div class="col">
                            <a href="kursi.php?id=<?= $film['id_film'] ?>">
                                <img src="./img/<?= $film['gambar'] ?>" alt="">
                            </a>
                            <p><?= $film['judul'] ?></p>
                        </div>
                    <?php endforeach ?>
                </div>

            </div>

        </div>
        <?php include "toggleButton.php" ?>
    </section>
    <script src="script-index.js"></script>
</body>

</html>