<?php
session_start();
include 'config.php';

// --- CEK LOGIN ---
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// --- CEK ID ---
if (!isset($_GET['id'])) {
    header("Location: pantry.php");
    exit;
}

$id = intval($_GET['id']); // sanitasi ID

// --- AMBIL DATA SEBELUM HAPUS ---
$query = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: pantry.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// --- SIAPKAN STACK UNDO ---
if (!isset($_SESSION['hapus_stack']) || !is_array($_SESSION['hapus_stack'])) {
    $_SESSION['hapus_stack'] = [];
}

// hanya simpan kolom yang dipakai insert ulang (tanpa id)
$saveData = [
    'name'        => $data['name'],
    'quantity'    => $data['quantity'],
    'expiry_date' => $data['expiry_date'],
    'unit'        => $data['unit'],
    'category'    => $data['category'],
    'photo'       => $data['photo']
];

// push ke stack (LIFO)
$_SESSION['hapus_stack'][] = $saveData;

// --- HAPUS FOTO SEBELUM DELETE DATABASE ---
if (!empty($data['photo'])) {
    $filePath = "uploads/" . $data['photo'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// --- DELETE DATA ---
mysqli_query($conn, "DELETE FROM items WHERE id = $id");

// --- KEMBALI KE PANTRY ---
header("Location: pantry.php");
exit;
?>
