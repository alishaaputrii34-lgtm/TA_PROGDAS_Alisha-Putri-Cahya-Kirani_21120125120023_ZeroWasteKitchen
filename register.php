<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah dipakai'); window.location='register.php';</script>";
        exit;
    }

    mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - ZeroWaste Kitchen</title>
</head>
<body>
<h2>Register Akun</h2>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Daftar</button>
</form>

</body>
</html>
