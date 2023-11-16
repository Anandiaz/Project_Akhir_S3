<?php
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location:login.php");
    exit;
}

$film_id = $_GET['id'];
$stmt = "SELECT * FROM film WHERE id_film = $film_id";
$result = mysqli_fetch_assoc(mysqli_query($conn, $stmt));

$film_title = $result["judul"];
$kursi_lama = "";
// untuk checkbox
$checked_arr = array();
if (isset($_POST["cek"])) {

    // check values
    $fetchLang = mysqli_query($conn, "SELECT * FROM tayang where id_film = '$film_id'");
    if (mysqli_num_rows($fetchLang) > 0) {
        $result = mysqli_fetch_assoc($fetchLang);
        $checked_arr = explode(",", $result['kursi']);
    }
    $kursi_lama = $result['kursi'];
}


$tayang = "SELECT * FROM tayang where id_film = '$film_id'";
$data = mysqli_query($conn, $tayang);
while ($row = mysqli_fetch_assoc($data)) {
    $jam[] = $row;
}

if (isset($_POST["submit"])) {
    $id_tayang = $_POST["jam"];
    $id_user = $_SESSION["id"];
    $kursi = implode(",", $_POST["kursi"]);

    $cek_kursi = mysqli_query($conn, "SELECT * FROM tayang where id_film = '$film_id'");
    $kursi_lama = mysqli_fetch_assoc($cek_kursi);
    if($kursi_lama['kursi'] === ""){
        $kursi_gabungan = $kursi;
        
    }
    else {
        $kursi_gabungan  = $kursi_lama['kursi'] .','. $kursi;
    }

    $ubah_tayang = "UPDATE tayang SET kursi = '$kursi_gabungan' WHERE id_tayang = '$id_tayang'";
    $result_tayang = mysqli_query($conn, $ubah_tayang);
    $tambah_booking = "INSERT INTO reservations (id_pesan, id_tayang, id_user, kursi) VALUES ('', '$id_tayang', '$id_user', '$kursi') ";
    $result_booking = mysqli_query($conn, $tambah_booking);

    if ($result_booking && $result_tayang) {
        echo "
        <script>
        alert('Berhasil membeli tiket!$tanggal');
        document.location.href='index.php';
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
    <link rel="stylesheet" href="./styles/kursi.css">

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

    <section>
        <div class="container">
            <h1><?= $film_title ?></h1>
        </div>

        <div class="tengah">
            <div class="screen">
                <h3>Screen</h3>
            </div>
        </div>

        <div class="tengah">
            <ul class="status">
                <li>
                    <div class="tersedia"></div>
                    <p>available</p>
                </li>
                <li></li>
                <li>
                    <div class="terjual"></div>
                    <p>unavailable</p>
                </li>
            </ul>
        </div>


        <div class="container">
            <div class="bioskop">
                <div class="denah">
                    <form method="post" action="">
                        <div class="form_group">
                            <label for="jam">Judul Film:</label>
                            <select id="jam" name="jam">
                                <?php foreach ($jam as $jm) : ?>
                                    <option value="<?= $jm['id_tayang'] ?>"><?= $jm['jam_tayang'] ?></option>
                                <?php endforeach ?>
                                <input type="submit" name="cek" value="CEK KURSI">
                            </select>

                        </div>
                        <!-- angka -->
                        <div class="row">
                            <div class="kosong"></div>
                            <div class="kosong">1</div>
                            <div class="kosong">2</div>
                            <div class="kosong">3</div>
                            <div class="kosong">4</div>
                            <div class="kosong">5</div>
                            <div class="kosong">6</div>
                            <div class="kosong">7</div>
                            <div class="kosong"></div>
                            <div class="kosong">8</div>
                            <div class="kosong">9</div>
                            <div class="kosong">10</div>
                            <div class="kosong">11</div>
                            <div class="kosong">12</div>
                            <div class="kosong">13</div>
                            <div class="kosong">14</div>
                            <div class="kosong"></div>
                        </div>
                        <!-- A -->
                        <div class="row">
                            <div class="kosong">A</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $a1 = array("A1", "A2", "A3", "A4", "A5", "A6", "A7", "A8", "A9", "A10", "A11", "A12", "A13", "A14");
                            foreach ($a1 as $A1) :

                                $checked = "";
                                if (in_array($A1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $A1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">A</div>
                        </div>
                        <!-- B -->
                        <div class="row">
                            <div class="kosong">B</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $b1 = array("B1", "B2", "B3", "B4", "B5", "B6", "B7", "B8", "B9", "B10", "B11", "B12", "B13", "B14");
                            foreach ($b1 as $B1) :

                                $checked = "";
                                if (in_array($B1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $B1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">B</div>
                        </div>
                        <!-- C -->
                        <div class="row">
                            <div class="kosong">C</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $c1 = array("C1", "C2", "C3", "C4", "C5", "C6", "C7", "C8", "C9", "C10", "C11", "C12", "C13", "C14");
                            foreach ($c1 as $C1) :

                                $checked = "";
                                if (in_array($C1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $C1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">C</div>
                        </div>

                        <!-- D -->
                        <div class="row">
                            <div class="kosong">D</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $d1 = array("D1", "D2", "D3", "D4", "D5", "D6", "D7", "D8", "D9", "D10", "D11", "D12", "D13", "D14");
                            foreach ($d1 as $D1) :

                                $checked = "";
                                if (in_array($D1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $D1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">D</div>
                        </div>

                        <!-- E -->
                        <div class="row">
                            <div class="kosong">E</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $d1 = array("E1", "E2", "E3", "E4", "E5", "E6", "E7", "E8", "E9", "E10", "E11", "E12", "E13", "E14");
                            foreach ($d1 as $D1) :

                                $checked = "";
                                if (in_array($D1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $D1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">E</div>
                        </div>

                        <!-- F -->
                        <div class="row">
                            <div class="kosong">F</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $f1 = array("F1", "F2", "F3", "F4", "F5", "F6", "F7", "F8", "F9", "F10", "F11", "F12", "F13", "F14");
                            foreach ($f1 as $F1) :

                                $checked = "";
                                if (in_array($F1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $F1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">F</div>
                        </div>

                        <!-- G -->
                        <div class="row">
                            <div class="kosong">G</div>
                            <?php
                            $count = 0;
                            $ulang = 0;
                            $g1 = array("G1", "G2", "G3", "G4", "G5", "G6", "G7", "G8", "G9", "G10", "G11", "G12", "G13", "G14");
                            foreach ($g1 as $G1) :

                                $checked = "";
                                if (in_array($G1, $checked_arr)) {
                                    $checked = 'style="background-color: #831010; cursor:default" disabled';
                                }
                                $count++;
                            ?>
                                <input type="checkbox" <?= $checked ?> class="kursi" name="kursi[]" value="<?= $G1 ?>" id="myCheckbox">
                                <?php if ($count % 7 == 0 && $ulang < 1) : ?>
                                    <span class="kosong"></span>
                                <?php $ulang++;
                                endif ?>
                            <?php endforeach ?>
                            <div class="kosong">G</div>
                        </div>
                        <!-- angka -->
                        <div class="row">
                            <div class="kosong"></div>
                            <div class="kosong">1</div>
                            <div class="kosong">2</div>
                            <div class="kosong">3</div>
                            <div class="kosong">4</div>
                            <div class="kosong">5</div>
                            <div class="kosong">6</div>
                            <div class="kosong">7</div>
                            <div class="kosong"></div>
                            <div class="kosong">8</div>
                            <div class="kosong">9</div>
                            <div class="kosong">10</div>
                            <div class="kosong">11</div>
                            <div class="kosong">12</div>
                            <div class="kosong">13</div>
                            <div class="kosong">14</div>
                            <div class="kosong"></div>
                        </div>

                        <input type="submit" name="submit" style="margin-bottom: 50px;" value="Book Selected Seats">
                    </form>


                </div>
            </div>

        </div>
        <?php include "toggleButton.php" ?>
    </section>
    <script src="script-index.js"></script>
</body>

</html>