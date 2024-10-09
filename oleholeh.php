<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oleh-oleh Tegal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Oleh-oleh Khas Tegal</h2>
    <p>Dapatkan oleh-oleh khas Tegal di sini.</p>
    
    <nav>
        <ul>
            <li><a href="beranda.php">Beranda</a></li>
            <li><a href="wisata.php">Wisata</a></li>
            <li><a href="kuliner.php">Kuliner</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
