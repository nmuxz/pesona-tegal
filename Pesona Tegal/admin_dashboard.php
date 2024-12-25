<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <h1>Selamat Datang, Admin</h1>
    <nav>
        <a href="admin_add.php">Tambah Data</a>
        <a href="admin_list.php?kategori=wisata">Kelola Wisata</a>
        <a href="admin_list.php?kategori=kuliner">Kelola Kuliner</a>
        <a href="admin_list.php?kategori=oleh_oleh">Kelola Oleh-oleh</a>
        <a href="admin_view.php">view</a>
        <a href="admin_logout.php">Logout</a>
    </nav>
    <script src="admin_script.js"></script>
</body>
</html>
