<?php
session_start();
include "../includes/database.php";

// =========================
// XỬ LÝ HÀNH ĐỘNG
// =========================

// ✅ Thêm tài xế
if (isset($_POST['add_driver'])) {
    $ten = $_POST['ten'];
    $sdt = $_POST['sdt'];
    $bien_so = $_POST['bien_so'];

    $stmt = $conn->prepare("INSERT INTO TaiXe (ten, sdt, bien_so, trang_thai) VALUES (?, ?, ?, 'san_sang')");
    $stmt->execute([$ten, $sdt, $bien_so]);

    header("Location: quanly_taixe.php");
    exit;
}

// ✅ Xóa tài xế
if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM TaiXe WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_taixe.php");
    exit;
}

// ✅ Khóa tài xế
if (isset($_POST['lock_id'])) {
    $id = (int)$_POST['lock_id'];
    $stmt = $conn->prepare("UPDATE TaiXe SET trang_thai = 'nghi' WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_taixe.php");
    exit;
}

// ✅ Mở khóa tài xế
if (isset($_POST['unlock_id'])) {
    $id = (int)$_POST['unlock_id'];
    $stmt = $conn->prepare("UPDATE TaiXe SET trang_thai = 'san_sang' WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_taixe.php");
    exit;
}

// =========================
// LẤY DANH SÁCH TÀI XẾ
// =========================
$stmt = $conn->prepare("SELECT * FROM TaiXe ORDER BY id DESC");
$stmt->execute();
$taixe = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý tài xế</title>
<style>
table { width:100%; border-collapse:collapse; margin-top:20px; background:#fff; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#eee; }
.btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; }
.add { background:#4caf50; color:white; }
.delete { background:#f44336; color:white; }
.lock { background:#795548; color:white; }
.unlock { background:#9c27b0; color:white; }
</style>
</head>
<body>

<h1>🚚 QUẢN LÝ TÀI XẾ</h1>

<h2>➕ Thêm tài xế</h2>
<form method="post">
    <input type="text" name="ten" placeholder="Tên tài xế" required>
    <input type="text" name="sdt" placeholder="Số điện thoại" required>
    <input type="text" name="bien_so" placeholder="Biển số xe" required>
    <button class="btn add" name="add_driver">Thêm</button>
</form>

<h2>📋 Danh sách tài xế</h2>
<table>
<tr>
    <th>ID</th>
    <th>Tên</th>
    <th>SĐT</th>
    <th>Biển số</th>
    <th>Trạng thái</th>
    <th>Thao tác</th>
</tr>

<?php foreach ($taixe as $tx): ?>
<tr>
    <td><?= $tx['id'] ?></td>
    <td><?= $tx['ten'] ?></td>
    <td><?= $tx['sdt'] ?></td>
    <td><?= $tx['bien_so'] ?></td>
    <td><?= $tx['trang_thai'] ?></td>
    <td>
        <?php if ($tx['trang_thai'] == 'san_sang'): ?>
            <form method="post" style="display:inline;">
                <button class="btn lock" name="lock_id" value="<?= $tx['id'] ?>">🔒 Khóa</button>
            </form>
        <?php else: ?>
            <form method="post" style="display:inline;">
                <button class="btn unlock" name="unlock_id" value="<?= $tx['id'] ?>">🔓 Mở khóa</button>
            </form>
        <?php endif; ?>

        <form method="post" style="display:inline;">
            <button class="btn delete" name="delete_id" value="<?= $tx['id'] ?>">🗑 Xóa</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
