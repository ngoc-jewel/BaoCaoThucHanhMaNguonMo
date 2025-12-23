<?php

include "../../includes/database.php";

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

// Xóa món nước
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM MonNuoc WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_monnuoc.php?status=deleted");
    exit();
}

// Thêm / Cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST')
 {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $chitiet = $_POST['chitiet'];
    $hinh = '';

    if (!empty($_FILES['hinh']['name']))
     {
        $hinh = basename($_FILES['hinh']['name']);
        move_uploaded_file($_FILES['hinh']['tmp_name'], "../uploads/" . $hinh);
    }

    if (isset($_POST['add_monnuoc'])) 
    {
        $stmt = $conn->prepare("INSERT INTO MonNuoc (TenNuoc, Gia, HinhAnh, ChiTiet) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ten, $gia, $hinh, $chitiet]);
        header("Location: quanly_monnuoc.php?status=added");
        exit();
    }

    if (isset($_POST['update_monnuoc'])) 
    {
        $id_update = (int)$_POST['id'];
        if ($hinh) 
        {
            $stmt = $conn->prepare("UPDATE MonNuoc SET TenNuoc=?, Gia=?, HinhAnh=?, ChiTiet=? WHERE id=?");
            $stmt->execute([$ten, $gia, $hinh, $chitiet, $id_update]);
        } else {
            $stmt = $conn->prepare("UPDATE MonNuoc SET TenNuoc=?, Gia=?, ChiTiet=? WHERE id=?");
            $stmt->execute([$ten, $gia, $chitiet, $id_update]);
        }
        header("Location: quanly_monnuoc.php?status=updated");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Món Nước</title>
  <style>
    /* Reset */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f6f7fb;
        color: #333;
        padding: 30px;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    h1 {
        text-align: center;
        color: #ff6600;
        margin-bottom: 25px;
    }

    .admin-nav {
        margin-bottom: 20px;
    }
    .admin-nav a {
        color: #ff6600;
        text-decoration: none;
        font-weight: 600;
    }
    .admin-nav a:hover {
        text-decoration: underline;
    }

    /* Tabs */
    .tabs {
        display: flex;
        justify-content: center;
        border-bottom: 2px solid #eee;
        margin-bottom: 25px;
    }
    .tabs a {
        padding: 12px 20px;
        text-decoration: none;
        color: #555;
        font-weight: 600;
        transition: color 0.3s, border-bottom 0.3s;
    }
    .tabs a.active {
        color: #ff914d;
        border-bottom: 3px solid #ff914d;
    }
    .tabs a:hover {
        color: #ff6600;
    }

    /* Form */
    .form-container {
        background: #fff8f2;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .form-container h2 {
        margin-bottom: 15px;
        color: #333;
    }
    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }
    textarea { min-height: 80px; }

    button {
        background: #ff914d;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }
    button:hover { background: #e65c00; }

    .btn-add {
        display: inline-block;
        background: #28a745;
        color: #fff;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: background 0.3s;
    }
    .btn-add:hover { background: #218838; }

    /* Table */
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
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #fff3e0; }

    /* Action links */
    .action-links a {
        margin: 0 8px;
        font-weight: 600;
        text-decoration: none;
    }
    .action-links a.edit { color: #E67E22; }
    .action-links a.delete { color: #E74C3C; }
    .action-links a:hover { text-decoration: underline; }
</style>

</head>
<body>
<div class="container">
    <a href="index.php">❮ Trở về Dashboard</a>
    <h1>Quản Lý Món Nước</h1>

    <?php if ($action === 'add' || $action === 'edit'): 
        $nuoc = ['TenNuoc'=>'','Gia'=>'','HinhAnh'=>'','ChiTiet'=>'','id'=>''];
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT * FROM MonNuoc WHERE id=?");
            $stmt->execute([$id]);
            $nuoc = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    ?>
    <form action="#" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $nuoc['id'] ?>">
        Tên nước: <input type="text" name="ten" value="<?= htmlspecialchars($nuoc['TenNuoc']) ?>" required><br>
        Giá: <input type="number" name="gia" value="<?= htmlspecialchars($nuoc['Gia']) ?>" required><br>
        Hình hiện tại: <br>
        <?php if ($nuoc['HinhAnh']): ?>
            <img src="../uploads/<?= htmlspecialchars($nuoc['HinhAnh']) ?>" width="100"><br>
        <?php endif; ?>
        Chọn hình mới: <input type="file" name="hinh"><br>
        Chi tiết: <textarea name="chitiet"><?= htmlspecialchars($nuoc['ChiTiet']) ?></textarea><br>
        <button type="submit" name="<?= $action==='add'?'add_monnuoc':'update_monnuoc' ?>">
            <?= $action==='add'?'Thêm':'Cập nhật' ?>
        </button>
        <a href="quanly_monnuoc.php">Hủy</a>
    </form>
    <?php endif; ?>

    <a href="?action=add" class="btn-add">+ Thêm Món Nước</a>
    <table>
        <tr>
            <th>ID</th><th>Hình</th><th>Tên nước</th><th>Giá</th><th>Hành động</th>
        </tr>
        <?php
        $stmt = $conn->query("SELECT * FROM MonNuoc ORDER BY id DESC");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?php if ($row['HinhAnh']): ?><img src="../uploads/<?= htmlspecialchars($row['HinhAnh']) ?>" width="80"><?php endif; ?></td>
            <td><?= htmlspecialchars($row['TenNuoc']) ?></td>
            <td><?= number_format($row['Gia']) ?> đ</td>
            <td class="action-links">
                <a href="?action=edit&id=<?= $row['id'] ?>">Sửa</a>
                <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Xóa món nước này?')" class="delete">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
