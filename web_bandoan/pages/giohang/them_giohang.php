<?php
session_start();
include("../../includes/database.php"); // file kết nối PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id    = $_SESSION['user_id']; // giả sử đã có user đăng nhập
    $product_id = (int)$_POST['product_id'];
    $loai       = $_POST['loai'];
    $ten        = $_POST['ten'];
    $gia        = (float)$_POST['gia'];
    $soluong    = isset($_POST['soluong']) ? (int)$_POST['soluong'] : 1;

    try {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $stmt = $conn->prepare("SELECT * FROM giohang WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Nếu đã có thì cập nhật số lượng
            $update = $conn->prepare("UPDATE giohang SET soluong = soluong + ? WHERE id = ?");
            $update->execute([$soluong, $row['id']]);
        } else {
            // Nếu chưa có thì thêm mới
            $insert = $conn->prepare("INSERT INTO giohang (user_id, product_id, loai, ten, gia, soluong) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$user_id, $product_id, $loai, $ten, $gia, $soluong]);
        }

        header("Location: giohang.php");
        exit();
    } catch (PDOException $e) {
        die("Lỗi thêm giỏ hàng: " . $e->getMessage());
    }
}
