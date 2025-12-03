<?php
session_start();
include 'db.php';
require_once 'class/FoodItem.php'; //buat hitung expiry, sisa hari, dll

// Cek login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
//setelah user berhasil login ulang lg, user dibalikin ke pantry.php
$_SESSION['last_page'] = 'pantry.php'; 

// Hitung notifikasi expired
function generateExpiryNotifications($conn) {
    date_default_timezone_set('Asia/Jakarta');
    $today = date('d-m-y');

// Struktur Data Queue untuk notifikasi
    $queue = [];
    $query = mysqli_query($conn, "SELECT name, expiry_date FROM items"); //query ke db buat ambil seluruh item yang ada di pantry.
    if (!$query) return $queue;
        while ($item = mysqli_fetch_assoc($query)) {
                $name = $item['name'];
                $exp  = $item['expiry_date'];

        // Hitung selisih hari
        $daysLeft = (strtotime($exp) - strtotime($today)) / 86400;

        if ($daysLeft < 0) {
            $queue[] = "❗ <b>{$name}</b> sudah kadaluarsa!";
        } elseif ($daysLeft == 0) {
            $queue[] = "⚠ <b>{$name}</b> kadaluarsa hari ini!";
        } elseif ($daysLeft == 1) {
            $queue[] = "⚠ <b>{$name}</b> akan kadaluarsa besok!";
        } elseif ($daysLeft <= 3) {
            $queue[] = "⚠ <b>{$name}</b> akan kadaluarsa dalam {$daysLeft} hari!";
        }
    }

    return $queue;
}

// Ambil data pantry
$items = mysqli_query($conn, "SELECT * FROM items ORDER BY expiry_date ASC");

// fungsi notifikasi yg akan ditampilkan di atas tabel
$notif = generateExpiryNotifications($conn);

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
    position: relative;
    max-width:1000px;
    margin: 40px auto;
    background: rgba(255,255,255,0.93);
    padding: 28px 30px 36px 30px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.logout-text {
    position: absolute;
    top: 18px;
    right: 20px;
    color: #e74c3c;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

h2 {
    margin: 0;
    color: #567d5b;
    font-weight: 700;
    font-size: 28px;
    display: inline-block;
}

.top-btns {
    display: flex;
    gap: 12px;
    margin-top: 14px;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    transition: 0.12s;
    display: inline-block;
}

.btn:hover { opacity: 0.92; }

.btn-add { background: #567d5b; }
.btn-undo { background: #fff3b0 ; color: #5a3e00; }

.notif-box {
    background: #fff0f0;
    border-left: 6px solid #d9534f;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 10px;
}

.table-box { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 8px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

th {
    background: #567d5b;
    color: white;
    padding: 12px;
    font-size: 14px;
    text-align: center;
}

td {
    padding: 12px;
    background: transparent;
    border-bottom: 1px solid #eee;
    text-align: center;
    color: #111;
    font-size: 14px;
}

.expired td {
    background-color: #ffb3b3 !important;
    color: #111 !important;
    font-weight: 600;
}

.soon td {
    background-color: #fff3b0 !important;
    color: #111 !important;
    font-weight: 600;
}

.action-edit {
    background: #4d9dfd;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}

.action-delete {
    background: #ff6b6b;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}

@media (max-width: 600px) {
    .top-btns { flex-direction: column; align-items: stretch; }
    .logout-text { top: 12px; right: 12px; }
    th, td { font-size: 13px; padding: 10px; }
}

</style>
</head>
<body>

<div class="container">

    <h2>Daftar Pantry</h2>
    <a href="logout.php" class="logout-text">Logout</a>

    <div class="top-btns">
        <a href="undo.php" class="btn btn-undo">Undo</a>
        <a href="index.php" class="btn btn-add">+ Tambah Item Pantry</a>
    </div>

    <!-- Notifikasi Kadaluarsa -->
    <?php if (!empty($notif)): ?>
        <div class="notif-box">
            <?php foreach ($notif as $n): ?>
                <div><?= $n; ?></div>
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
            <th>Aksi</th>
        </tr>

        <?php 
        if ($items && mysqli_num_rows($items) > 0):
            while ($row = mysqli_fetch_assoc($items)): 
                // OOP = obj FoodItem
                $obj = new FoodItem(
                    $row['name'],
                    $row['quantity'],
                    $row['expiry_date'],
                    $row['category'],
                    $row['unit']
                );

                $sisa = $obj->daysLeft();

                // nentuin warna baris
                $classRow = '';
                if ($obj->isExpired() || $sisa <= 3) {
                    $classRow = 'expired';
                } elseif ($sisa >= 4 && $sisa <= 6) {
                    $classRow = 'soon';
                }
        ?>
            <tr class="<?= $classRow ?>">
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= intval($row['quantity']); ?></td>
                <td><?= $row['expiry_date']; ?></td>
                <td><?= htmlspecialchars($row['unit']); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>

                <td>
                    <a class="action-edit" href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                    <a class="action-delete" onclick="return confirm('Hapus item ini?')" 
                       href="delete.php?id=<?= $row['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php 
            endwhile;
        endif;
        ?>
    </table>
    </div>

    <div style="text-align:center; margin-top:18px;">
        <a href="recipe.php" class="btn btn-add" style="font-size:15px; padding:10px 20px;">📖 Lihat Menu Resep</a>
    </div>

</div>

</body>
</html>
