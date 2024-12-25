<?php
session_start();
include 'config.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    // Query untuk mendapatkan data user berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Password benar, login berhasil
            $_SESSION['isLoggedIn'] = true; 
            $_SESSION['email'] = $email; // Menyimpan email ke session
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name']; // Menyimpan nama pengguna ke session
            $_SESSION['error_message'] = "Login Berhasil! Selamat datang, " . $user['name'] . " di Pesona Tegal";
            header('Location: beranda.php');
            exit();
        } else {
            // Password salah
            $_SESSION['error_message'] = "Password yang Anda masukkan salah.";
            header("Location: beranda.php");
            exit;
        }
    } else {
        // Email tidak ditemukan
        $_SESSION['error_message'] = "Email tidak terdaftar.";
        header("Location: beranda.php");
        exit;
    }

    // Menutup koneksi database
    $conn->close();
}
?>
