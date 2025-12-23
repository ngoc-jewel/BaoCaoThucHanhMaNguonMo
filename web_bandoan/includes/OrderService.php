<?php

class OrderService 
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // ✅ Lấy danh sách đơn hàng của khách
    public function getOrdersByUser($khach_hang_id) 
    {
        $stmt = $this->conn->prepare("
            SELECT don_hang_id, ngay_dat, tong_tien, trang_thai 
            FROM DonHang 
            WHERE khach_hang_id = ? 
            ORDER BY ngay_dat DESC
        ");
        $stmt->execute([$khach_hang_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Kiểm tra đơn hàng có thuộc về khách và có thể hủy không
    public function canCancelOrder($order_id, $khach_hang_id) 
    {
        $stmt = $this->conn->prepare("
            SELECT trang_thai 
            FROM DonHang 
            WHERE don_hang_id = ? AND khach_hang_id = ?
        ");
        $stmt->execute([$order_id, $khach_hang_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return false;

        // ✅ Chỉ cho hủy khi trạng thái = 0 (Chờ phê duyệt)
        return ($order['trang_thai'] == 0);
    }

    // ✅ Hủy đơn hàng (CẬP NHẬT trạng thái = 4)
    public function deleteOrder($order_id, $khach_hang_id, $ly_do) 
    {
        // Kiểm tra đơn hàng có thể hủy không
        if (!$this->canCancelOrder($order_id, $khach_hang_id)) {
            return "approved"; // đã duyệt hoặc không thể hủy
        }

        // ✅ Cập nhật trạng thái đơn hàng thành HỦY (4)
        $stmt = $this->conn->prepare("
            UPDATE DonHang 
            SET trang_thai = 4, ghi_chu = ? 
            WHERE don_hang_id = ? AND khach_hang_id = ?
        ");
        $stmt->execute([$ly_do, $order_id, $khach_hang_id]);

        return true;
    }
}
?>
