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
    <title>Beranda - Pesona Tegal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Selamat datang di Pesona Tegal</h2>
    <p>Jelajahi berbagai destinasi wisata menarik di Tegal.</p>
    
    <nav>
        <ul>
            <li><a href="wisata.php">Wisata</a></li>
            <li><a href="kuliner.php">Kuliner</a></li>
            <li><a href="oleholeh.php">Oleh-oleh</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
