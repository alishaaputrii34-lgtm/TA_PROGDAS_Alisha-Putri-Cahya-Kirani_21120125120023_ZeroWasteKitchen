<?php
session_start();
include "db.php";

$error = "";

// Jika sudah login, langsung lempar ke index
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST["username"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);

    // Cek user
    $query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) == 1) {
        $_SESSION["username"] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if ($error != "") { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
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
