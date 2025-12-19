<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password_plain = $_POST['password'] ?? '';

    if (empty($email) || empty($password_plain)) {
        echo "<script>alert('Email dan Password tidak boleh kosong!'); window.location='index.php';</script>";
        exit;
    }

    // Cek apakah email sudah digunakan
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan!'); window.location='index.php';</script>";
    } else {
        $password = password_hash($password_plain, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users(email, password, role) VALUES(?, ?, 'user')");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
    }
}
?>
