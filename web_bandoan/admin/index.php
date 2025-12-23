<?php
session_start();

// ✅ Kiểm tra quyền admin
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; }
        header { background: #ff914d; color: white; padding: 20px; text-align: center; }
        nav { background: #333; padding: 10px; text-align: center; }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        nav a:hover { text-decoration: underline; }
        .container { padding: 30px; }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <h1>👋 Xin chào, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
    <p>Chào mừng bạn đến trang quản trị hệ thống</p>
</header>

<nav>
    <a href="quanlyfood/index.php">🍱 Quản lý món ăn</a>
    <a href="donhang/quanly_donhang.php">📦 Quản lý đơn hàng</a>
    <a href="nguoidung/index.php">👥 Quản lý người dùng</a>
    <a href="taixe/index.php">🚚 Quản lý tài xế</a>
    <a href="logout.php">🔓 Đăng xuất</a>
</nav>

<div class="container">
    <div class="card">
        <h2>📊 Thống kê nhanh</h2>
        <p>🔸 Tổng số đơn hàng: đang cập nhật...</p>
        <p>🔸 Số món ăn hiện có: đang cập nhật...</p>
        <p>🔸 Người dùng đăng ký: đang cập nhật...</p>
    </div>
</div>

</body>
</html>
