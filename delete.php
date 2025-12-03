<?php
session_start();
include 'db.php';

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// CEK ID ITEM
if (!isset($_GET['id'])) {
    header("Location: pantry.php");
    exit;
}

$id = intval($_GET['id']); // sanitasi agar aman

// AMBIL DATA ITEM (untuk UNDO)
$get = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
if (!$get || mysqli_num_rows($get) == 0) {
    header("Location: pantry.php");
    exit;
}

$data = mysqli_fetch_assoc($get);

// STACK (STRUKTUR DATA UNTUK UNDO
if (!isset($_SESSION['hapus_stack']) || !is_array($_SESSION['hapus_stack'])) {
    $_SESSION['hapus_stack'] = [];
}

// Simpan data item
$backup = [
    'name'        => $data['name'],
    'quantity'    => $data['quantity'],
    'expiry_date' => $data['expiry_date'],
    'unit'        => $data['unit'],
    'category'    => $data['category']
];

// push ke stack (LIFO)
$_SESSION['hapus_stack'][] = $backup;

// HAPUS DATA DARI DATABASE
mysqli_query($conn, "DELETE FROM items WHERE id = $id");

// KEMBALI KE PANTRY
header("Location: pantry.php");
exit;
?>
