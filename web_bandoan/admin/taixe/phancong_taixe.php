<?php
session_start();
include "../includes/database.php";

// =========================
// GÁN TÀI XẾ
// =========================
if (isset($_POST['assign'])) {
    $don_id = $_POST['don_hang_id'];
    $tai_xe_id = $_POST['tai_xe_id'];

    $stmt = $conn->prepare("UPDATE DonHang SET tai_xe_id = ?, trang_thai = 'dang_giao' WHERE don_hang_id = ?");
    $stmt->execute([$tai_xe_id, $don_id]);

    header("Location: phancong_taixe.php");
    exit;
}

// =========================
// LẤY ĐƠN CHƯA CÓ TÀI XẾ
// =========================
$stmt = $conn->prepare("
    SELECT * FROM DonHang 
    WHERE tai_xe_id IS NULL AND trang_thai = 'dang_xu_ly'
");
$stmt->execute();
$don = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =========================
// LẤY TÀI XẾ SẴN SÀNG
// =========================
$stmt = $conn->prepare("SELECT * FROM TaiXe WHERE trang_thai = 'san_sang'");
$stmt->execute();
$taixe = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Phân công tài xế</title>
<style>
table { width:100%; border-collapse:collapse; margin-top:20px; background:#fff; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#eee; }
.btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; background:#2196f3; color:white; }
</style>
</head>
<body>

<h1>🚚 PHÂN CÔNG TÀI XẾ</h1>

<table>
<tr>
    <th>ID đơn</th>
    <th>Khách hàng</th>
    <th>Tổng tiền</th>
    <th>Chọn tài xế</th>
    <th>Gán</th>
</tr>

<?php foreach ($don as $d): ?>
<tr>
    <td><?= $d['don_hang_id'] ?></td>
    <td><?= $d['khach_hang_id'] ?></td>
    <td><?= number_format($d['tong_tien']) ?> VND</td>
    <td>
        <form method="post">
            <input type="hidden" name="don_hang_id" value="<?= $d['don_hang_id'] ?>">
            <select name="tai_xe_id">
                <?php foreach ($taixe as $tx): ?>
                    <option value="<?= $tx['id'] ?>"><?= $tx['ten'] ?> (<?= $tx['bien_so'] ?>)</option>
                <?php endforeach; ?>
            </select>
    </td>
    <td>
            <button class="btn" name="assign">Gán</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
