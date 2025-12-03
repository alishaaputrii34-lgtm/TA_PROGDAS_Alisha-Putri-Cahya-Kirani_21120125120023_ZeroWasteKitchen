<?php
session_start();
$_SESSION["last_page"] = "login.php";
include "db.php";

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZeroWaste Kitchen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-box">

    <a href="logout.php" class="logout">Logout</a>
    <h2>ZeroWaste Kitchen</h2>


    <!-- form tambah item -->
    <form action="save_item.php" method="POST">

        <label>Nama Item:</label>
        <input type="text" name="item" required>

        <label>Quantity:</label>
        <input type="number" name="qty" required>

        <label>Tanggal Kedaluwarsa:</label>
        <input type="date" name="exp" required>

        <label>Satuan:</label>
        <select name="unit" required>
            <option value="pcs">pcs</option>
            <option value="pack">pack</option>
            <option value="gram">gram</option>
            <option value="kg">kg</option>
            <option value="liter">liter</option>
            <option value="ml">ml</option>
        </select>

        <label>Kategori:</label>
        <select name="category" required>
            <option value="sembako">Sembako</option>
            <option value="bumbu">Bumbu Dapur</option>
            <option value="minuman">Minuman</option>
            <option value="snack">Snack</option>
            <option value="bahan masak">Bahan Masak</option>
            <option value="lainnya">Lainnya</option>
        </select>

        <button type="submit" class="btn-save">Simpan</button>
    </form>

    <a href="pantry.php" class="link-btn">Lihat Daftar Item Pantry</a>

</div>

</body>
</html>
