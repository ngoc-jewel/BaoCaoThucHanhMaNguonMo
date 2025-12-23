<?php
session_start();
include("../includes/database.php");
include("../includes/OrderService.php");
$orderService = new OrderService($conn);
$donhangs = $orderService->getCancelledOrders($_SESSION['user_id']);
?>
<h1>🗑️ Đơn hàng đã hủy</h1>
<table>
  <tr>
    <th>ID</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Lý do</th><th>Ngày hủy</th>
  </tr>
  <?php if (empty($donhangs)): ?>
    <tr><td colspan="5">Bạn chưa có đơn hàng nào bị hủy.</td></tr>
  <?php else: ?>
    <?php foreach ($donhangs as $dh): ?>
      <tr>
        <td><?= $dh['don_hang_id'] ?></td>
        <td><?= $dh['ngay_dat'] ?></td>
        <td><?= number_format($dh['tong_tien'], 0, ',', '.') ?> VND</td>
        <td><?= $dh['ly_do'] ?></td>
        <td><?= $dh['ngay_huy'] ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>
