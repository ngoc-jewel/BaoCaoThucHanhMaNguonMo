<?php
session_start();
include("../../includes/database.php");
include("../../includes/OrderService.php");

$orderService = new OrderService($conn);

// ✅ Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("❌ Bạn chưa đăng nhập.");
}

// ✅ Kiểm tra form gửi lên
if (!isset($_POST['cancel_order']) || !isset($_POST['don_hang_id'])) {
    die("❌ Yêu cầu không hợp lệ.");
}

$order_id = (int)$_POST['don_hang_id'];
$khach_hang_id = $_SESSION['user_id'];
$ly_do = $_POST['ly_do'] ?? "Không rõ";

// ✅ Kiểm tra đơn hàng có thuộc về khách không + trạng thái
$stmt = $conn->prepare("SELECT trang_thai FROM DonHang WHERE don_hang_id = ? AND khach_hang_id = ?");
$stmt->execute([$order_id, $khach_hang_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("<p style='color:red;'>❌ Không tìm thấy đơn hàng.</p>");
}

// ✅ Chỉ cho hủy khi trạng thái = 0 (Chờ phê duyệt)
if ($order['trang_thai'] != 0) {
    die("<p style='color:red;'>❌ Đơn hàng đã được xử lý, không thể hủy.</p>");
}

// ✅ Cập nhật trạng thái đơn hàng thành HỦY (4)
$stmt = $conn->prepare("UPDATE DonHang SET trang_thai = 4, ghi_chu = ? WHERE don_hang_id = ?");
$stmt->execute([$ly_do, $order_id]);

// ✅ Chuyển hướng về trang đơn hàng
header("Location: /web_bandoan/pages/donhang_cuatoi.php?msg=huy_thanh_cong");
exit;
?>
