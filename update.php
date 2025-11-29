<?php
include 'config.php';

// --- VALIDASI ID ---
if (!isset($_POST['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_POST['id']); // sanitize ID agar aman

// --- AMBIL DATA LAMA ---
$getOld = mysqli_query($conn, "SELECT photo FROM items WHERE id=$id");
$oldData = mysqli_fetch_assoc($getOld);

if (!$oldData) {
    die("Data tidak ditemukan.");
}

$oldPhoto = $oldData['photo'];

// --- AMBIL INPUT ---
$name = trim($_POST['name']);
$qty  = intval($_POST['qty']);
$exp  = $_POST['exp'];

// --- VALIDASI INPUT WAJIB ---
if ($name === "" || empty($qty) || empty($exp)) {
    die("Input tidak boleh kosong.");
}

// --- PROSES FOTO ---
$photoName = $oldPhoto;  // default: foto lama

if (!empty($_FILES['photo']['name'])) {

    // --- VALIDASI TIPE FILE ---
    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("Format foto tidak diizinkan.");
    }

    // --- VALIDASI UKURAN (max 2MB) ---
    if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
        die("Ukuran foto terlalu besar (maks 2MB).");
    }

    // --- RENAME FILE ---
    $folder = "uploads/";
    $photoName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['photo']['name']);

    // --- UPLOAD ---
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $folder . $photoName)) {

        // Hapus foto lama hanya jika ada
        if (!empty($oldPhoto) && file_exists("uploads/" . $oldPhoto)) {
            unlink("uploads/" . $oldPhoto);
        }

    } else {
        die("Gagal upload foto.");
    }
}

// --- UPDATE DATA ---
$sql = "UPDATE items SET
            name = '$name',
            quantity = '$qty',
            expiry_date = '$exp',
            photo = '$photoName'
        WHERE id = $id";

if (!mysqli_query($conn, $sql)) {
    die("Gagal update: " . mysqli_error($conn));
}

// --- BALIK KE HALAMAN UTAMA ---
header("Location: pantry.php");
exit;
?>
