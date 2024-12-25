<?php
session_start();
include 'config.php'; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
    <script src="admin_script.js"></script>
</body>
</html>
