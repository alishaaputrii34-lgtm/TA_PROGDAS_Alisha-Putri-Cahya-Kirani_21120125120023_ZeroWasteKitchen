<?php
session_start();
include 'config.php';
require_once "class/FoodItem.php";   // ← Tambah OOP

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Ambil dan sanitasi input
$item = trim($_POST['item'] ?? '');
$qty  = intval($_POST['qty'] ?? 0);
$exp  = $_POST['exp'] ?? null;
$unit = $_POST['unit'] ?? '';
$category = $_POST['category'] ?? '';
$photoName = '';

// === IMPLEMENTASI OOP ===
$foodObj = new FoodItem($item, $qty, $exp, $category, $unit);
// Bisa digunakan misalnya: $foodObj->isExpired();

// Upload foto
if (!empty($_FILES['photo']['name'])) {
    if (!is_dir('uploads')) mkdir('uploads', 0777, true);

    $tmp = $_FILES['photo']['tmp_name'];
    $fname = time() . '_' . basename($_FILES['photo']['name']);
    $path = 'uploads/' . $fname;

    if (move_uploaded_file($tmp, $path)) {
        $photoName = mysqli_real_escape_string($conn, $fname);
    }
}

// Query simpan data
$expValue = (empty($exp)) ? "NULL" : "'" . mysqli_real_escape_string($conn, $exp) . "'";

$sql = "INSERT INTO items (name, quantity, expiry_date, unit, category, photo)
        VALUES (
            '" . mysqli_real_escape_string($conn, $foodObj->name) . "', 
            {$foodObj->qty}, 
            $expValue,
            '" . mysqli_real_escape_string($conn, $foodObj->unit) . "',
            '" . mysqli_real_escape_string($conn, $foodObj->category) . "',
            '$photoName'
        )";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Item berhasil disimpan!'); window.location='pantry.php';</script>";
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>
