<?php
session_start();
include("../../includes/database.php");
include("../../includes/OrderService.php");

$orderService = new OrderService($conn);

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("❌ Vui lòng đăng nhập để xem đơn hàng của bạn.");
}

$user_id = $_SESSION['user_id'];
$donhangs = $orderService->getOrdersByUser($user_id);

// Hàm hiển thị trạng thái
function hienThiTrangThai($trang_thai) {
    $map = [
        'cho_duyet'   => "⏳ Chờ phê duyệt",
        'dang_xu_ly'  => "✅ Đã duyệt – Chờ giao",
        'dang_giao'   => "🚚 Đang giao",
        'hoan_thanh'  => "🎉 Hoàn tất",
        'huy'         => "❌ Đã hủy"
    ];
    return $map[$trang_thai] ?? "Không xác định";
}

// CSS class theo trạng thái
function trangThaiClass($trang_thai) {
    $map = [
        'cho_duyet'   => "pending",
        'dang_xu_ly'  => "approved",
        'dang_giao'   => "shipping",
        'hoan_thanh'  => "completed",
        'huy'         => "cancelled"
    ];
    return $map[$trang_thai] ?? "";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đơn hàng của tôi</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:20px; }
    h1 { text-align:center; color:#ff6600; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    th, td { border:1px solid #ddd; padding:12px; text-align:center; }
    th { background:#ff914d; color:#fff; }
    tr:nth-child(even) { background:#fdfdfd; }
    .status { font-weight:bold; }
    .pending { color:#ff9800; }
    .approved { color:#2196f3; }
    .shipping { color:#4caf50; }
    .completed { color:#673ab7; }
    .cancelled { color:#f44336; }
    button { cursor:pointer; padding:6px 12px; border:none; border-radius:4px; background:#f44336; color:#fff; }
    button:hover { background:#d32f2f; }
    .back-home { display:inline-block; margin-bottom:20px; padding:10px 20px; background:#ff6600; color:#fff; border:none; border-radius:6px; text-decoration:none; }
    .back-home:hover { background:#e65c00; }
  </style>
</head>
<body>

<a href="/web_bandoan/index.php" class="back-home">🏠 Quay về trang chủ</a>

<h1>📦 Đơn hàng của tôi</h1>

<table>
    <tr>
      <th>ID</th>
      <th>Ngày đặt</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Hủy đơn</th>
    </tr>

    <?php if (empty($donhangs)): ?>
      <tr><td colspan="5">Bạn chưa có đơn hàng nào.</td></tr>
    <?php else: ?>
      <?php foreach ($donhangs as $dh): ?>
        <tr>
          <td><?= htmlspecialchars($dh['don_hang_id']) ?></td>
          <td><?= htmlspecialchars($dh['ngay_dat']) ?></td>
          <td><?= number_format($dh['tong_tien'], 0, ',', '.') ?> VND</td>
          <td class="status <?= trangThaiClass($dh['trang_thai']) ?>">
            <?= hienThiTrangThai($dh['trang_thai']) ?>
          </td>
          <td>
            <?php if ($dh['trang_thai'] === 'cho_duyet'): ?>
                <form action="huydon.php" method="post" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                    <input type="hidden" name="cancel_order" value="1">
                    <input type="hidden" name="don_hang_id" value="<?= $dh['don_hang_id'] ?>">
                    <input type="hidden" name="ly_do" value="Người dùng tự hủy">
                    <button type="submit">❌ Hủy</button>
                </form>
            <?php else: ?>
                ❌ Không thể hủy
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>
