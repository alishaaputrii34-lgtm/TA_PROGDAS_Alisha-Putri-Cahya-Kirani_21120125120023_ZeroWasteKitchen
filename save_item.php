<?php
session_start();
require_once 'db.php';
require_once 'class/FoodItem.php'; //class OOP untuk objek item makanan


// Validasi login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}



// Untuk menerima req post supaya tidak bisa submit data tanpa melalui form
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: pantry.php");
    exit;
}

// Mengambil data form
$item     = trim($_POST['item'] ?? '');
$qty      = intval($_POST['qty'] ?? 0);
$exp      = $_POST['exp'] ?? null;
$unit     = trim($_POST['unit'] ?? '');
$category = trim($_POST['category'] ?? '');

// Di validasi disini, kalau user input nama item kosong dan qty 0 akan muncul alert
if ($item === '' || $qty <= 0) {
    echo "<script>
            alert('Nama item wajib diisi dan quantity harus lebih dari 0.');
            window.location='pantry.php';
          </script>";
    exit;
}

// Buat objek OOP karena FoodItem extends item (bagian ini jg untuk menyimpan data)
$food = new FoodItem($item, $qty, $exp, $category, $unit);

$stmt = $conn->prepare("
    INSERT INTO items (name, quantity, expiry_date, unit, category)
    VALUES (?, ?, ?, ?, ?)
");

// Jika tanggal kosong maka akan set null
$expValue = empty($food->exp) ? null : $food->exp;

$stmt->bind_param(
    "sisss",
    $food->name,
    $food->qty,
    $expValue,
    $food->unit,
    $food->category
);

// Eksekusi query
if ($stmt->execute()) {
    echo "<script>
            alert('Item berhasil disimpan!');
            window.location='pantry.php';
          </script>";
} else {
    error_log('DB Error: ' . $stmt->error);
    echo "<script>
            alert('Terjadi kesalahan saat menyimpan data.');
            window.location='pantry.php';
          </script>";
}

$stmt->close();
$conn->close();
?>
