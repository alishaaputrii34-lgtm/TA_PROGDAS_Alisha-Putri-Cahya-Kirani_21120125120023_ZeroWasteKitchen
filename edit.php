<?php
require_once 'db.php';

// Ambil ID dari URL (aman)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika ID tidak valid
if ($id <= 0) {
    header("Location: pantry.php");
    exit;
}

// ============================
//  PREPARED STATEMENT
// ============================
$stmt = mysqli_prepare($conn, 
"SELECT id, name, quantity, expiry_date FROM items WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

// Jika item tidak ditemukan
if (!$data) {
    header("Location: pantry.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item Pantry</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="overlay">
    <div class="edit-box">
        <h2>Edit Item Pantry</h2>

        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">

            <label>Nama Item:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" required>

            <label>Quantity:</label>
            <input type="number" name="qty" value="<?= intval($data['quantity']); ?>" required>

            <label>Tanggal Kedaluwarsa:</label>
            <input type="date" name="exp" value="<?= htmlspecialchars($data['expiry_date']); ?>" required>

            <button type="submit">Update Item</button>
        </form>

        <a class="back-btn" href="pantry.php">Kembali ke Pantry</a>
    </div>
</div>

</body>
</html>
