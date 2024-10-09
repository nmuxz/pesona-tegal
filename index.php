<?php
session_start();
if(isset($_SESSION['username'])) {
    header("Location: beranda.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Contoh sederhana validasi login (sesuaikan dengan database)
    if($username == "admin" && $password == "admin123") {
        $_SESSION['username'] = $username;
        header("Location: beranda.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pesona Tegal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login ke Pesona Tegal</h2>
    <?php if(isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>