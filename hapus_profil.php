<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
$user_id = $_SESSION['user_id'];

mysqli_query($conn,"DELETE FROM profil WHERE user_id='$user_id'");
echo "<script>alert('Profil berhasil dihapus!');window.location='profil.php';</script>";
?>
