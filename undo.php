<?php
session_start();
include 'config.php';

// cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// cek stack
if (!isset($_SESSION['hapus_stack']) || empty($_SESSION['hapus_stack'])) {
    echo "<script>alert('Tidak ada aksi yang dapat di-undo.'); window.location='pantry.php';</script>";
    exit;
}

// ambil aksi terakhir (pop)
$last = array_pop($_SESSION['hapus_stack']);

// sanitasi
$name = mysqli_real_escape_string($conn, $last['name']);
$qty  = intval($last['quantity']);
$exp  = ($last['expiry_date'] === null || $last['expiry_date'] === '') ? "NULL" : "'" . mysqli_real_escape_string($conn, $last['expiry_date']) . "'";
$unit = mysqli_real_escape_string($conn, $last['unit']);
$cat  = mysqli_real_escape_string($conn, $last['category']);
$photo = mysqli_real_escape_string($conn, $last['photo']);

// masukkan kembali ke DB (tanpa id agar auto_increment bekerja)
$sql = "INSERT INTO items (name, quantity, expiry_date, unit, category, photo)
        VALUES ('$name', $qty, $exp, '$unit', '$cat', '$photo')";
mysqli_query($conn, $sql);

// update session stack sudah dilakukan oleh array_pop (pop terjadi di memory)
// simpan lagi session (untuk keamanan)
$_SESSION['hapus_stack'] = isset($_SESSION['hapus_stack']) ? $_SESSION['hapus_stack'] : [];

echo "<script>alert('Undo berhasil! Item dikembalikan.'); window.location='pantry.php';</script>";
exit;
?>
