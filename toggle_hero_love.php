<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { echo "unauthorized"; exit; }

$user_id = $_SESSION['user_id'];
$hero_id = intval($_POST['hero_id'] ?? 0);

if ($hero_id <= 0) { echo "invalid"; exit; }

$cek = mysqli_query($conn, "SELECT * FROM hero_love WHERE user_id='$user_id' AND hero_id='$hero_id'");
if (mysqli_num_rows($cek) > 0) {
  mysqli_query($conn, "DELETE FROM hero_love WHERE user_id='$user_id' AND hero_id='$hero_id'");
  echo "unloved";
} else {
  mysqli_query($conn, "INSERT INTO hero_love (user_id, hero_id) VALUES ('$user_id', '$hero_id')");
  echo "loved";
}
?>
