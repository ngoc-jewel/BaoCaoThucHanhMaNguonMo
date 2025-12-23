<?php
session_start();
include("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'];
    $email = $_POST['email'];
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO KhachHang (Ten, Email, MatKhau) VALUES (?, ?, ?)");
    $stmt->execute([$ten, $email, $matkhau]);

    echo "Đăng ký thành công! <a href='dangnhap_khach.php'>Đăng nhập ngay</a>";
}
?>
<form method="post">
    <label>Họ tên</label><input type="text" name="ten" required>
    <label>Email</label><input type="email" name="email" required>
    <label>Mật khẩu</label><input type="password" name="matkhau" required>
    <button type="submit">Đăng ký</button>
</form>
