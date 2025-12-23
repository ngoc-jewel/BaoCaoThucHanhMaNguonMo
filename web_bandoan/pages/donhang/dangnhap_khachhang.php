<?php
session_start();
include("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];

    $stmt = $conn->prepare("SELECT * FROM KhachHang WHERE Email = ?");
    $stmt->execute([$email]);
    $khachhang = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($khachhang && password_verify($matkhau, $khachhang['MatKhau'])) {
        $_SESSION['user_id'] = $khachhang['id'];
        $_SESSION['username'] = $khachhang['Ten'];
        header("Location: giohang.php");
        exit();
    } else {
        echo "Sai email hoặc mật khẩu!";
    }
}
?>
<form method="post">
    <label>Email</label><input type="email" name="email" required>
    <label>Mật khẩu</label><input type="password" name="matkhau" required>
    <button type="submit">Đăng nhập</button>
</form>
