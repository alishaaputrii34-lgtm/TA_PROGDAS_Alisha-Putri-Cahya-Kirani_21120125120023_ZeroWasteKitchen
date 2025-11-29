<?php
session_start();
$_SESSION['last_page'] = 'index.php';
include 'config.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Load Notification
$dataNotif = null;
$notifQuery = mysqli_query($conn, "SELECT * FROM notifications ORDER BY id DESC LIMIT 1");
if ($notifQuery && mysqli_num_rows($notifQuery) > 0) {
    $dataNotif = mysqli_fetch_assoc($notifQuery);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZeroWaste Kitchen</title>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.pinimg.com/1200x/63/af/23/63af23ba2b994ee54bb135ae3e1611bf.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 40px;
        }

        .page-box {
            background: rgba(255,255,255,0.92);
            padding: 35px;
            border-radius: 20px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }

        h2 {
            color: #567d5b;
            margin-top: 0;
            font-size: 26px;
        }

        .logout {
            float: right;
            color: red;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
        }

        .logout:hover { text-decoration: underline; }

        .notif {
            background: #ffdddd;
            border: 1px solid red;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #b00000;
            font-weight: 600;
        }

        form {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        label {
            font-weight: 500;
        }

        input, select {
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #bbb;
            margin-top: 6px;
            margin-bottom: 14px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 12px;
            background: #567d5b;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-size: 15px;
        }

        button:hover { background: #43a047; }

        /* BUTTON LIHAT PANTRY */
        .link-btn {
            background: #184b54;
            padding: 12px 20px;
            display: block;
            width: fit-content;
            max-width: 300px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            margin: 20px auto 0; /* CENTER */
            text-align: center;
        }

        .link-btn:hover { background: #0f343a; }

        .undo-btn {
            background: #ff9800;
            padding: 10px 18px;
            text-decoration: none;
            color: white;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="page-box">

    <a href="logout.php" class="logout">Logout</a>
    <h2>ZeroWaste Kitchen</h2>

    <!-- NOTIFICATION -->
    <?php if ($dataNotif): ?>
        <div class="notif">⚠ <?= $dataNotif['message']; ?></div>
    <?php endif; ?>

    <!-- FORM INPUT ITEM -->
    <form action="save_item.php" method="POST" enctype="multipart/form-data">

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


        <button type="submit">Simpan</button>
    </form>

    <!-- BUTTON KE HALAMAN PANTRY -->
    <a href="pantry.php" class="link-btn">📦 Lihat Daftar Item Pantry</a>

</div>

</body>
</html>
