<?php
session_start();
include 'config.php';
require_once 'class/FoodItem.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$_SESSION['last_page'] = 'pantry.php';

// Ambil data pantry
$data = mysqli_query($conn, "SELECT * FROM items ORDER BY expiry_date ASC");

// Notifikasi
date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d');
$notif = [];
$notifQuery = mysqli_query($conn, "SELECT name, expiry_date FROM items");
while ($r = mysqli_fetch_assoc($notifQuery)) {
    $name = $r['name'];
    $exp  = $r['expiry_date'];
    $diff = (strtotime($exp) - strtotime($today)) / 86400;

    if ($diff < 0) $notif[] = "❗ <b>{$name}</b> sudah kadaluwarsa!";
    elseif ($diff == 0) $notif[] = "⚠ <b>{$name}</b> kadaluwarsa hari ini!";
    elseif ($diff == 1) $notif[] = "⚠ <b>{$name}</b> akan kadaluarsa besok!";
    elseif ($diff <= 3) $notif[] = "⚠ <b>{$name}</b> akan kadaluarsa dalam {$diff} hari!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pantry - ZeroWaste Kitchen</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: url('https://i.pinimg.com/1200x/63/af/23/63af23ba2b994ee54bb135ae3e1611bf.jpg');
    background-size: cover;
    background-position: center;
}

.container {
    max-width: 1100px;
    margin: 40px auto;
    background: rgba(255,255,255,0.92);
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

h2 {
    margin-top: 0;
    color: #2f5b4b;
    font-weight: 700;
    font-size: 28px;
}

.top-btns {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    transition: 0.2s;
}

.btn:hover { opacity: 0.85; }

.btn-add { background: #2f5b4b; }
.btn-recipe { background: #ff8c42; }
.btn-logout { background: #e74c3c; margin-left: auto; }
.btn-undo { background: #f1c40f; color: #5a3e00; }

.notif-box {
    background: #ffe9e9;
    border-left: 6px solid #d9534f;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 10px;
}

.table-box { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 15px;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: #2f5b4b;
    color: white;
    padding: 10px;
    font-size: 14px;
}

td {
    padding: 12px;
    background: white;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

.expired { background: #ffb3b3 !important; color: #7a0000; font-weight: 600; }
.soon { background: #fff5b3 !important; color: #6d5a00; font-weight: 600; }

.action-edit {
    background: #3498db;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}

.action-delete {
    background: #e74c3c;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}
</style>
</head>
<body>
<div class="container">

<h2>Daftar Pantry</h2>

<div class="top-btns">
    <a class="btn btn-undo" href="undo.php">Undo</a>
    <a class="btn btn-add" href="index.php">+ Tambah Item Pantry</a>
    
    <a class="btn btn-logout" href="logout.php">Logout</a>
</div>

<?php if (!empty($notif)): ?>
<div class="notif-box">
    <?php foreach ($notif as $n): ?>
        <div><?= $n ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="table-box">
<table>
    <tr>
        <th>Nama Item</th>
        <th>Quantity</th>
        <th>Kedaluwarsa</th>
        <th>Satuan</th>
        <th>Kategori</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($data)): ?>
    <?php
        $obj = new FoodItem(
            $row['name'],
            $row['quantity'],
            $row['expiry_date'],
            $row['category'],
            $row['unit']
        );
        $left = $obj->daysLeft();
    ?>

    <tr class="<?= $obj->isExpired() ? 'expired' : ($left <= 7 ? 'soon' : '') ?>">
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= intval($row['quantity']) ?></td>
        <td><?= htmlspecialchars($row['expiry_date']) ?></td>
        <td><?= htmlspecialchars($row['unit']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>


        <td>
            <a class="action-edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
            <a class="action-delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus item ini?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>
</div>

<div style="text-align:center; margin-top:20px;">
    <a href="recipe.php" class="btn btn-add" style="font-size:15px; padding:10px 20px;">Lihat Menu Resep</a>
</div>

</div>
<div style="text-align:center; margin-top:20px;">
</div>
</div>
</div>
</body>
</html>
