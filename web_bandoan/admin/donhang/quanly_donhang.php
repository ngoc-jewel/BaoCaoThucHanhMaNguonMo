<?php
session_start();
include "../includes/database.php";

// =========================
// XỬ LÝ CÁC HÀNH ĐỘNG
// =========================

// ✅ Phê duyệt đơn → chuyển sang 'dang_xu_ly'
if (isset($_POST['approve_id'])) {
    $id = (int)$_POST['approve_id'];
    $stmt = $conn->prepare("UPDATE DonHang SET trang_thai = 'dang_xu_ly' WHERE don_hang_id = ? AND trang_thai = 'cho_duyet'");
    $stmt->execute([$id]);
    header("Location: quanly_donhang.php");
    exit;
}

// ✅ Từ chối đơn → chuyển sang 'huy'
if (isset($_POST['reject_id'])) {
    $id = (int)$_POST['reject_id'];
    $reason = $_POST['reason'] ?? "Admin từ chối đơn";

    $stmt = $conn->prepare("UPDATE DonHang SET trang_thai = 'huy', ghi_chu = ? WHERE don_hang_id = ? AND trang_thai = 'cho_duyet'");
    $stmt->execute([$reason, $id]);

    header("Location: quanly_donhang.php");
    exit;
}

// ✅ Cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $id = (int)$_POST['don_hang_id'];
    $status = $_POST['trang_thai'];

    $stmt = $conn->prepare("UPDATE DonHang SET trang_thai = ? WHERE don_hang_id = ?");
    $stmt->execute([$status, $id]);

    header("Location: quanly_donhang.php");
    exit;
}

// ✅ Khóa đơn
if (isset($_POST['lock_id'])) {
    $id = (int)$_POST['lock_id'];
    $stmt = $conn->prepare("UPDATE DonHang SET is_locked = 1 WHERE don_hang_id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_donhang.php");
    exit;
}

// ✅ Mở khóa đơn
if (isset($_POST['unlock_id'])) {
    $id = (int)$_POST['unlock_id'];
    $stmt = $conn->prepare("UPDATE DonHang SET is_locked = 0 WHERE don_hang_id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_donhang.php");
    exit;
}

// =========================
// LẤY DANH SÁCH ĐƠN HÀNG
// =========================

// ✅ Đơn hàng chờ phê duyệt
$stmt = $conn->prepare("
    SELECT d.*, k.Ten 
    FROM DonHang d
    JOIN KhachHang k ON d.khach_hang_id = k.id
    WHERE d.trang_thai = 'cho_duyet'
    ORDER BY d.ngay_dat DESC
");
$stmt->execute();
$don_cho = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Tất cả đơn hàng khác
$stmt = $conn->prepare("
    SELECT d.*, k.Ten 
    FROM DonHang d
    JOIN KhachHang k ON d.khach_hang_id = k.id
    WHERE d.trang_thai != 'cho_duyet'
    ORDER BY d.ngay_dat DESC
");
$stmt->execute();
$don_khac = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Hàm hiển thị trạng thái
function hienThiTrangThai($t) {
    return [
        'cho_duyet' => "Chờ phê duyệt",
        'dang_xu_ly' => "Đang xử lý",
        'dang_giao' => "Đang giao",
        'hoan_thanh' => "Hoàn tất",
        'huy' => "Hủy"
    ][$t] ?? "Không xác định";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý đơn hàng</title>
<style>
    table { width:100%; border-collapse:collapse; margin-top:20px; background:#fff; }
    th, td { border:1px solid #ccc; padding:10px; text-align:center; }
    th { background:#eee; }
    .btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; }
    .approve { background:#4caf50; color:white; }
    .reject { background:#f44336; color:white; }
    .update { background:#2196f3; color:white; }
    .lock { background:#795548; color:white; }
    .unlock { background:#9c27b0; color:white; }
</style>
</head>
<body>

<h1>📦 QUẢN LÝ ĐƠN HÀNG</h1>

<!-- ========================= -->
<!-- ĐƠN HÀNG CHỜ PHÊ DUYỆT -->
<!-- ========================= -->
<h2>⏳ ĐƠN HÀNG CHỜ PHÊ DUYỆT</h2>

<table>
<tr>
    <th>ID</th>
    <th>Khách hàng</th>
    <th>Ngày đặt</th>
    <th>Tổng tiền</th>
    <th>Ghi chú</th>
    <th>Thao tác</th>
</tr>

<?php if (empty($don_cho)): ?>
<tr><td colspan="6">Không có đơn hàng chờ duyệt.</td></tr>
<?php else: ?>
<?php foreach ($don_cho as $dh): ?>
<tr>
    <td><?= $dh['don_hang_id'] ?></td>
    <td><?= $dh['Ten'] ?></td>
    <td><?= $dh['ngay_dat'] ?></td>
    <td><?= number_format($dh['tong_tien']) ?> VND</td>
    <td><?= $dh['ghi_chu'] ?></td>
    <td>
        <!-- Phê duyệt -->
        <form method="post" style="display:inline;">
            <button class="btn approve" name="approve_id" value="<?= $dh['don_hang_id'] ?>">✅ Duyệt</button>
        </form>

        <!-- Từ chối -->
        <form method="post" style="display:inline;">
            <input type="hidden" name="reason" value="Admin từ chối đơn">
            <button class="btn reject" name="reject_id" value="<?= $dh['don_hang_id'] ?>">❌ Từ chối</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>

<!-- ========================= -->
<!-- TẤT CẢ ĐƠN HÀNG KHÁC -->
<!-- ========================= -->
<h2>📄 TẤT CẢ ĐƠN HÀNG KHÁC</h2>

<table>
<tr>
    <th>ID</th>
    <th>Khách hàng</th>
    <th>Ngày đặt</th>
    <th>Tổng tiền</th>
    <th>Trạng thái</th>
    <th>Khóa</th>
    <th>Cập nhật</th>
</tr>

<?php foreach ($don_khac as $dh): ?>
<tr>
    <td><?= $dh['don_hang_id'] ?></td>
    <td><?= $dh['Ten'] ?></td>
    <td><?= $dh['ngay_dat'] ?></td>
    <td><?= number_format($dh['tong_tien']) ?> VND</td>
    <td><?= hienThiTrangThai($dh['trang_thai']) ?></td>

    <!-- Khóa / mở khóa -->
    <td>
        <?php if (empty($dh['is_locked'])): ?>
            <form method="post">
                <button class="btn lock" name="lock_id" value="<?= $dh['don_hang_id'] ?>">🔒 Khóa</button>
            </form>
        <?php else: ?>
            <form method="post">
                <button class="btn unlock" name="unlock_id" value="<?= $dh['don_hang_id'] ?>">🔓 Mở khóa</button>
            </form>
        <?php endif; ?>
    </td>

    <!-- Cập nhật trạng thái -->
    <td>
        <form method="post">
            <input type="hidden" name="don_hang_id" value="<?= $dh['don_hang_id'] ?>">
            <select name="trang_thai">
                <option value="dang_xu_ly">Đang xử lý</option>
                <option value="dang_giao">Đang giao</option>
                <option value="hoan_thanh">Hoàn tất</option>
                <option value="huy">Hủy</option>
            </select>
            <button class="btn update" name="update_status">Cập nhật</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
