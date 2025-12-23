<?php
session_start();
require_once __DIR__ . '/includes/database.php';

// Nếu đã đăng nhập thì lấy tên user, còn chưa thì để trống
$username = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <title>Trang chủ</title>
    <style>
        /* CSS của bạn giữ nguyên */
    </style>
</head>
<body>
  <!-- HEADER -->
  <?php include __DIR__ . "/layouts/header.php"; ?>

  <!-- BANNER -->
  <?php include __DIR__ . "/layouts/banner.php"; ?>

  <!-- MÓN NỔI BẬT -->
  <?php include __DIR__ . "/layouts/monnoibac.php"; ?>

  <!-- GIỚI THIỆU -->
  <?php include __DIR__ . "/layouts/gioithieu.php"; ?>

  <!-- GOOGLE MAP -->
  <h2>📍 Vị trí quán FoodZone</h2>
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.123456789!2d106.700000!3d10.800000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1234567890abcdef%3A0xabcdef1234567890!2sFoodZone!5e0!3m2!1svi!2s!4v1234567890"
    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
  </iframe>

  <!-- FOOTER -->
  <?php include __DIR__ . "/layouts/footer.php"; ?>
</body>
</html>
