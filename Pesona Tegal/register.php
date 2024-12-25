<?php

session_start();
include "config.php"; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $origin = $_POST['origin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validasi password
    if ($password !== $confirmPassword) {
        die('Password dan konfirmasi password tidak cocok');
    }
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Cek apakah email sudah terdaftar
    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result->num_rows > 0) {
        die("Email sudah terdaftar.");
    }
    // Masukkan data pengguna ke database
    $query = "INSERT INTO users (name, origin, email, password) VALUES ('$name', '$origin', '$email', '$hashedPassword')";
    
    if ($conn->query($query) === TRUE) {
        // echo "Pendaftaran berhasil!";
        $_SESSION['error_message'] = "Pendaftaran Berhasil";
        header("Location: beranda.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
    // Tutup koneksi
    $conn->close();
}
?>

