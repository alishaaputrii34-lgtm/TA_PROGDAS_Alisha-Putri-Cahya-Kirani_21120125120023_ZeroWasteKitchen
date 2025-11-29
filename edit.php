<?php
include 'config.php';

// Ambil ID dari URL (dengan validasi)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika ID tidak valid
if ($id <= 0) {
    header("Location: pantry.php");
    exit;
}

// Ambil data item berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM items WHERE id=$id");
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
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

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.pinimg.com/1200x/63/af/23/63af23ba2b994ee54bb135ae3e1611bf.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .overlay {
            background: rgba(255, 255, 255, 0.45);
            width: 100%;
            min-height: 100vh;
            padding: 60px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-box {
            background: rgba(255, 255, 255, 0.92);
            width: 430px;
            padding: 32px;
            border-radius: 22px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            color: #184b54;
            margin-top: 0;
            margin-bottom: 25px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bbb;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 18px;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: #2e86de;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
        }

        button:hover {
            background: #2169ac;
            transform: translateY(-1px);
        }

        .back-btn {
            display: block;
            margin-top: 15px;
            text-align: center;
            padding: 10px;
            background: #184b54;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
        }

        .back-btn:hover { background: #0f343a; }

        .photo-preview {
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #444;
        }

        .photo-preview img {
            width: 100%;
            border-radius: 10px;
            margin-top: 6px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.25);
        }
    </style>
</head>
<body>

<div class="overlay">
    <div class="edit-box">
        <h2>Edit Item Pantry</h2>

        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">

            <label>Nama Item:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" required>

            <label>Quantity:</label>
            <input type="number" name="qty" value="<?= intval($data['quantity']); ?>" required>

            <label>Tanggal Kedaluwarsa:</label>
            <input type="date" name="exp" value="<?= $data['expiry_date']; ?>" required>

            <button type="submit">Update Item</button>
        </form>

        <a class="back-btn" href="pantry.php">Kembali ke Pantry</a>
    </div>
</div>

</body>
</html>
