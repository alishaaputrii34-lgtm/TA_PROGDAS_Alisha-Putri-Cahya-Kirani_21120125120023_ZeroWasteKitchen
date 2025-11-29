<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login - Zerowaste</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://i.pinimg.com/1200x/63/af/23/63af23ba2b994ee54bb135ae3e1611bf.jpg');
            background-size: cover;
            background-position: center;
        }

        .container {
            width: 360px;
            max-width: calc(100% - 32px);
            background: rgba(255,255,255,0.95);
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.18);
        }

        h2 { text-align:center; margin:0 0 14px 0; color:#184b54; }

        .input-box { margin-bottom:12px; }
        .input-box label { display:block; margin-bottom:6px; font-weight:600; color:#333; }
        .input-box input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            margin-top:8px;
            border-radius: 10px;
            border: none;
            background: #4CAF50;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .error {
            background: #ffdddd;
            color: #d00;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-box">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Login</button>
    </form>
</div>

</body>
</html>
