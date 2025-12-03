<?php
include 'db.php';

// VALIDASI ID
if (!isset($_POST['id'])) {
    die("ID tidak ditemukan.");
}

$idItem = intval($_POST['id']); // memastikan ID angka

// AMBIL DATA LAMA UNTUK DICEK
$cekItem = $conn->prepare("SELECT * FROM items WHERE id = ?");
$cekItem->bind_param("i", $idItem);
$cekItem->execute();

$hasilLama = $cekItem->get_result();
$dataLama = $hasilLama->fetch_assoc();

if (!$dataLama) {
    die("Data tidak ditemukan.");
}

// INPUT BARU DARI FORM
$namaBaru = trim($_POST['name']);
$qtyBaru  = intval($_POST['qty']);
$expBaru  = $_POST['exp'];

// VALIDASI WAJIB
if ($namaBaru === "" || $qtyBaru <= 0 || $expBaru === "") {
    die("Input tidak boleh kosong atau tidak valid.");
}

// UPDATE PAKE PREPARED STATEMENT
$updateItem = $conn->prepare("
    UPDATE items 
    SET name = ?, quantity = ?, expiry_date = ?
    WHERE id = ?
");

$updateItem->bind_param("sisi", $namaBaru, $qtyBaru, $expBaru, $idItem);

if (!$updateItem->execute()) {
    die("Gagal update: " . $conn->error);
}

// KEMBALI KE HALAMAN PANTRY
header("Location: pantry.php");
exit;
