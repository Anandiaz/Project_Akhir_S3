<?php
session_start();
require 'include/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$sql = "DELETE FROM film WHERE id_film = $id";
if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Berhasil Menghapus Film');
            window.location.href = 'lihat.php';
            </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
