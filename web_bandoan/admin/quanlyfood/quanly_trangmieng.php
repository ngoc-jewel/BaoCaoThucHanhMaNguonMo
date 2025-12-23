<?php

include "../../includes/database.php";

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

// Xóa tráng miệng
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM TrangMieng WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_trangmieng.php?status=deleted");
    exit();
}

// Thêm / Cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $chitiet = $_POST['chitiet'];
    $hinh = '';

    if (!empty($_FILES['hinh']['name'])) {
        $hinh = basename($_FILES['hinh']['name']);
        move_uploaded_file($_FILES['hinh']['tmp_name'], "../uploads/" . $hinh);
    }

    if (isset($_POST['add_trangmieng'])) {
        $stmt = $conn->prepare("INSERT INTO TrangMieng (TenTM, Gia, HinhAnh, ChiTiet) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ten, $gia, $hinh, $chitiet]);
        header("Location: quanly_trangmieng.php?status=added");
        exit();
    }

    if (isset($_POST['update_trangmieng'])) {
        $id_update = (int)$_POST['id'];
        if ($hinh) {
            $stmt = $conn->prepare("UPDATE TrangMieng SET TenTM=?, Gia=?, HinhAnh=?, ChiTiet=? WHERE id=?");
            $stmt->execute([$ten, $gia, $hinh, $chitiet, $id_update]);
        } else {
            $stmt = $conn->prepare("UPDATE TrangMieng SET TenTM=?, Gia=?, ChiTiet=? WHERE id=?");
            $stmt->execute([$ten, $gia, $chitiet, $id_update]);
        }
        header("Location: quanly_trangmieng.php?status=updated");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Tráng Miệng</title>
   <style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f6f7fb;
        margin: 0;
        padding: 30px;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    h1 {
        text-align: center;
        color: #ff6600;
        margin-bottom: 20px;
    }

    a {
        color: #ff6600;
        text-decoration: none;
        font-weight: 600;
    }
    a:hover {
        text-decoration: underline;
    }

    form {
        background: #fff8f2;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    form label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="date"],
    form textarea,
    form select,
    form input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    form textarea {
        min-height: 80px;
    }

    form button {
        background: #ff914d;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }

    form button:hover {
        background: #e65c00;
    }

    .btn-add {
        display: inline-block;
        margin-bottom: 20px;
        background: #4CAF50;
        color: #fff;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-add:hover {
        background: #388E3C;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #ff914d;
        color: #fff;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background: #f9f9f9;
    }

    tr:hover {
        background: #fff3e0;
    }

    .action-links a {
        margin: 0 8px;
        font-weight: 600;
        text-decoration: none;
    }

    .action-links a:hover {
        text-decoration: underline;
    }

    .action-links a.delete {
        color: #E74C3C;
    }

    .action-links a.edit {
        color: #E67E22;
    }
</style>

</head>
<body>
<div class="container">
    <a href="index.php">❮ Trở về Dashboard</a>
    <h1>Quản Lý Tráng Miệng</h1>

    <?php if ($action === 'add' || $action === 'edit'): 
        $tm = ['TenTM'=>'','Gia'=>'','HinhAnh'=>'','ChiTiet'=>'','id'=>''];
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT * FROM TrangMieng WHERE id=?");
            $stmt->execute([$id]);
            $tm = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $tm['id'] ?>">
        Tên tráng miệng: <input type="text" name="ten" value="<?= htmlspecialchars($tm['TenTM']) ?>" required><br>
        Giá: <input type="number" name="gia" value="<?= htmlspecialchars($tm['Gia']) ?>" required><br>
        Hình hiện tại: <br>
        <?php if ($tm['HinhAnh']): ?>
            <img src="../uploads/<?= htmlspecialchars($tm['HinhAnh']) ?>" width="100"><br>
        <?php endif; ?>
        Chọn hình mới: <input type="file" name="hinh"><br>
        Chi tiết: <textarea name="chitiet"><?= htmlspecialchars($tm['ChiTiet']) ?></textarea><br>
        <button type="submit" name="<?= $action==='add'?'add_trangmieng':'update_trangmieng' ?>">
            <?= $action==='add'?'Thêm':'Cập nhật' ?>
        </button>
        <a href="quanly_trangmieng.php">Hủy</a>
    </form>
    <?php endif; ?>

    <a href="?action=add" class="btn-add">+ Thêm Tráng Miệng</a>
    <table>
        <tr>
            <th>ID</th><th>Hình</th><th>Tên tráng miệng</th><th>Giá</th><th>Hành động</th>
        </tr>
        <?php
        $stmt = $conn->query("SELECT * FROM TrangMieng ORDER BY id DESC");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?php if ($row['HinhAnh']): ?><img src="../uploads/<?= htmlspecialchars($row['HinhAnh']) ?>" width="80"><?php endif; ?></td>
            <td><?= htmlspecialchars($row['TenTM']) ?></td>
            <td><?= number_format($row['Gia']) ?> đ</td>
            <td class="action-links">
                <a href="?action=edit&id=<?= $row['id'] ?>">Sửa</a>
                <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Xóa tráng miệng này?')" class="delete">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
