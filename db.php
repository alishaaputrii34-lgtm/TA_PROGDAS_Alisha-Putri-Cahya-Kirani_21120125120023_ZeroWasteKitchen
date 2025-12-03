<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "zerowaste_db";

// buat koneksi ke MySQL 
$conn = mysqli_connect($host, $user, $pass, $db);

// Jika gagal, hentikan program dan tampilkan pesan
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
