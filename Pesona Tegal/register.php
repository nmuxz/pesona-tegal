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
    // Cek apakah email sudah terdaftar menggunakan prepared statement
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("Email sudah terdaftar.");
    }
    $stmt->close();

    // Masukkan data pengguna ke database menggunakan prepared statement
    $query = "INSERT INTO users (name, origin, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $origin, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        // echo "Pendaftaran berhasil!";
        $_SESSION['error_message'] = "Pendaftaran Berhasil";
        header("Location: beranda.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    // Tutup koneksi
    $conn->close();
}
?>

