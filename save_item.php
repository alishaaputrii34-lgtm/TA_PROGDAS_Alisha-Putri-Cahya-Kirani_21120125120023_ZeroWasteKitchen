<?php
session_start();
require_once 'db.php';
require_once 'class/FoodItem.php';

// CEK LOGIN YANG BENAR
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


// ==========================
// CEK METODE HARUS POST
// ==========================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: pantry.php");
    exit;
}

// ==========================
// AMBIL DATA FORM
// ==========================
$item     = trim($_POST['item'] ?? '');
$qty      = intval($_POST['qty'] ?? 0);
$exp      = $_POST['exp'] ?? null;
$unit     = trim($_POST['unit'] ?? '');
$category = trim($_POST['category'] ?? '');

// ==========================
// VALIDASI DASAR
// ==========================
if ($item === '' || $qty <= 0) {
    echo "<script>
            alert('Nama item wajib diisi dan quantity harus lebih dari 0.');
            window.location='pantry.php';
          </script>";
    exit;
}

// Buat objek OOP
$food = new FoodItem($item, $qty, $exp, $category, $unit);

// ==========================
// PREPARED STATEMENT
// ==========================
$stmt = $conn->prepare("
    INSERT INTO items (name, quantity, expiry_date, unit, category)
    VALUES (?, ?, ?, ?, ?)
");

// Jika tanggal kosong → set null
$expValue = empty($food->exp) ? null : $food->exp;

$stmt->bind_param(
    "sisss",
    $food->name,
    $food->qty,
    $expValue,
    $food->unit,
    $food->category
);

// ==========================
// EKSEKUSI QUERY
// ==========================
if ($stmt->execute()) {
    echo "<script>
            alert('Item berhasil disimpan!');
            window.location='pantry.php';
          </script>";
} else {
    // Jangan tampilkan detail error ke user
    error_log('DB Error: ' . $stmt->error);
    echo "<script>
            alert('Terjadi kesalahan saat menyimpan data.');
            window.location='pantry.php';
          </script>";
}

$stmt->close();
$conn->close();
?>
