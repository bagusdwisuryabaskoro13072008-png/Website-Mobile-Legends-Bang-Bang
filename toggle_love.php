<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { echo "unauthorized"; exit; }

$user_id = $_SESSION['user_id'];
$skin_id = intval($_POST['skin_id'] ?? 0);

if ($skin_id <= 0) { echo "invalid"; exit; }

$cek = mysqli_query($conn, "SELECT * FROM skin_love WHERE user_id='$user_id' AND skin_id='$skin_id'");
if (mysqli_num_rows($cek) > 0) {
  mysqli_query($conn, "DELETE FROM skin_love WHERE user_id='$user_id' AND skin_id='$skin_id'");
  echo "unloved";
} else {
  mysqli_query($conn, "INSERT INTO skin_love (user_id, skin_id) VALUES ('$user_id', '$skin_id')");
  echo "loved";
}
?>
