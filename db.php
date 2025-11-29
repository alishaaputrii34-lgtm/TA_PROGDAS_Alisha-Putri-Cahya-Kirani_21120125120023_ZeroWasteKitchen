<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "zerowaste_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Optional: tampilkan pesan jika berhasil
// echo "Koneksi berhasil!";
?>
