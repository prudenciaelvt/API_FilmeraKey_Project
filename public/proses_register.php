<?php
session_start();
require_once '../config_db/db_koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $cek = $conn->query("SELECT * FROM tbl_users WHERE username = '$username'");
    if ($cek->num_rows > 0) {
        header("Location: register.php?error=Username sudah digunakan");
        exit();
    }

    // Insert user baru
    $sql = "INSERT INTO tbl_users (username, email, password, created_at) VALUES ('$username', '$email', '$password', NOW())";

    if ($conn->query($sql)) {
        $user_id = $conn->insert_id;
        $api_key = bin2hex(random_bytes(16)); // generate API key

        $conn->query("INSERT INTO tbl_api_keys (user_id, api_key, created_at) VALUES ('$user_id', '$api_key', NOW())");

        header("Location: login.php?message=Registrasi berhasil, silakan login");
    } else {
        header("Location: register.php?error=Gagal registrasi");
    }
} else {
    header("Location: register.php");
}
?>
