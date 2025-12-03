<?php
session_start();
include 'db.php';

// CEK LOGIN

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// CEK APAKAH STACK PENGHAPUSAN ADA
if (empty($_SESSION['hapus_stack'])) {
    echo "<script>
            alert('Tidak ada aksi yang dapat di-undo.');
            window.location='pantry.php';
          </script>";
    exit;
}
 // POP DATA TERAKHIR DARI STACK (LIFO)
$lastItem = array_pop($_SESSION['hapus_stack']);

$name = mysqli_real_escape_string($conn, $lastItem['name']);
$quantity = intval($lastItem['quantity']);
$unit = mysqli_real_escape_string($conn, $lastItem['unit']);
$category = mysqli_real_escape_string($conn, $lastItem['category']);

// expiry bisa kosong → NULL
if (empty($lastItem['expiry_date'])) {
    $expiry = "NULL";
} else {
    $expiry = "'" . mysqli_real_escape_string($conn, $lastItem['expiry_date']) . "'";
}

// INSERT KEMBALI KE DATABASE

$insert = "
    INSERT INTO items (name, quantity, expiry_date, unit, category)
    VALUES ('$name', $quantity, $expiry, '$unit', '$category')
";

mysqli_query($conn, $insert);

echo "<script>
        alert('Undo berhasil! Item berhasil dikembalikan.');
        window.location='pantry.php';
      </script>";
exit;
?>
