<?php
session_start();
include("../includes/database.php");

$donhang_id = $_GET['donhang_id'] ?? 0;

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM DonHang WHERE id = ?");
$stmt->execute([$donhang_id]);
$donhang = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy thông tin thanh toán
$stmt = $conn->prepare("SELECT * FROM ThanhToan WHERE donhang_id = ?");
$stmt->execute([$donhang_id]);
$thanhtoan = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy chi tiết món ăn
$stmt = $conn->prepare("SELECT ctd.*, m.TenMon FROM ChiTietDonHang ctd JOIN MonAn m ON ctd.monan_id = m.id WHERE ctd.donhang_id = ?");
$stmt->execute([$donhang_id]);
$chitiet = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Chi tiết thanh toán</h1>
<p>Khách hàng: <?= $thanhtoan['ho_ten'] ?></p>
<p>SĐT: <?= $thanhtoan['so_dien_thoai'] ?></p>
<p>Địa chỉ: <?= $thanhtoan['dia_chi'] ?></p>
<p>Phương thức: <?= $thanhtoan['phuong_thuc'] ?></p>
<p>Tổng tiền: <?= number_format($thanhtoan['tong_tien'], 0, ',', '.') ?> VND</p>

<table border="1">
<tr><th>Tên món</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>
<?php foreach ($chitiet as $item): ?>
<tr>
  <td><?= $item['TenMon'] ?></td>
  <td><?= $item['so_luong'] ?></td>
  <td><?= number_format($item['gia'], 0, ',', '.') ?> VND</td>
  <td><?= number_format($item['gia'] * $item['so_luong'], 0, ',', '.') ?> VND</td>
</tr>
<?php endforeach; ?>
</table>
