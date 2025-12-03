<?php
session_start();
include 'db.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil daftar item pantry
$pantry = [];
$getPantry = mysqli_query($conn, "SELECT name FROM items");

while ($row = mysqli_fetch_assoc($getPantry)) {
    $pantry[] = strtolower($row['name']);
}

// Ambil semua resep
$recipes = mysqli_query($conn, "SELECT * FROM recipes");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resep Otomatis</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-image: url('https://i.pinimg.com/1200x/63/af/23/63af23ba2b994ee54bb135ae3e1611bf.jpg');
        background-size: cover;
        padding: 40px;
        margin: 0;
    }

    .page-box {
        background: rgba(255,255,255,0.94);
        padding: 35px;
        border-radius: 18px;
        max-width: 950px;
        margin: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    }

    .logout {
        float: right;
        color: red;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
    }

    h2 {
        margin-top: 0;
        color: #567d5b;
        font-size: 26px;
    }

    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background: #567d5b;
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .recipe-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        border-left: 6px solid #567d5b;
    }

    .recipe-title {
        font-size: 20px;
        font-weight: 600;
        color: #567d5b;
    }

    .badge-ready {
        background: #4CAF50;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }

    .badge-partial {
        background: #ff9800;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }

    .badge-missing {
        background: #d9534f;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
</style>
</head>

<body>

<div class="page-box">

    <a href="logout.php" class="logout">Logout</a>
    <h2>Resep Otomatis</h2>

    <a href="pantry.php" class="btn-back">⬅ Kembali</a>

    <?php if (mysqli_num_rows($recipes) == 0): ?>
        <p><i>Tidak ada resep tersedia.</i></p>
    <?php endif; ?>

    <?php while ($r = mysqli_fetch_assoc($recipes)): ?>

        <?php
            $recipe_id = (int)$r['id'];

            // Ambil bahan resep
            $ingredientsQuery = mysqli_query(
                $conn,
                "SELECT ingredient FROM recipe_ingredients WHERE recipe_id = $recipe_id"
            );

            $ingredients = [];
            while ($i = mysqli_fetch_assoc($ingredientsQuery)) {
                $ingredients[] = strtolower($i['ingredient']);
            }

            // Hitung bahan yang cocok & hilang
            $available = array_intersect($ingredients, $pantry);
            $missing   = array_diff($ingredients, $pantry);

            // Menentukan badge status ketersediaan ingridients
            if (count($missing) == 0) {
                $status = "<span class='badge-ready'>Bisa dimasak!</span>";
            } elseif (count($available) >= count($ingredients) / 2) {
                $status = "<span class='badge-partial'>Bahan hampir lengkap</span>";
            } else {
                $status = "<span class='badge-missing'>Bahan tidak cukup</span>";
            }
        ?>

        <div class="recipe-card">

            <div class="recipe-title">
                <?= htmlspecialchars($r['name']); ?> <?= $status ?>
            </div>

            <h4>Bahan:</h4>
            <ul>
                <?php foreach ($ingredients as $ing): ?>
                    <?php if (in_array($ing, $pantry)): ?>
                        <li>✔ <?= htmlspecialchars($ing) ?></li>
                    <?php else: ?>
                        <li>✘ <?= htmlspecialchars($ing) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <h4>Cara Memasak:</h4>
            <pre style="white-space: pre-wrap; font-family: Poppins;">
<?= htmlspecialchars($r['steps']); ?>
            </pre>

        </div>

    <?php endwhile; ?>

</div>

</body>
</html>
