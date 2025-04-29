<?php
session_start();
require_once '../config_db/db_koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil user berdasarkan username
    $sql = "SELECT * FROM tbl_users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Password salah");
            exit();
        }
    } else {
        header("Location: login.php?error=User tidak ditemukan. Register Now");
        exit();
    }
} else {
    header("Location: login.php");
}
?>
