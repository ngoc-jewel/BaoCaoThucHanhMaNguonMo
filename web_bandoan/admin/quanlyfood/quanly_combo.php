<?php

include "../../includes/database.php";

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

// Xóa combo
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM Combo WHERE id = ?");
    $stmt->execute([$id]);
    $stmt = $conn->prepare("DELETE FROM Combo_Mon WHERE combo_id=?");
    $stmt->execute([$id]);
    header("Location: quanly_combo.php?status=deleted");
    exit();
}

// Thêm combo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_combo'])) {
    $ten = $_POST['ten'];
    $giamgia = (int)$_POST['giamgia'];
    $chitiet = $_POST['chitiet'];
    $hinh = '';

    if (!empty($_FILES['hinh']['name'])) {
        $hinh = basename($_FILES['hinh']['name']);
        move_uploaded_file($_FILES['hinh']['tmp_name'], "../uploads/" . $hinh);
    }

    $mon_ids = $_POST['mon_ids'] ?? []; // mảng id món
    $loai_mons = $_POST['loai_mons'] ?? []; // mảng loại tương ứng

    // Tính tổng giá gốc
    $giaGoc = 0;
    foreach ($mon_ids as $index => $mid) {
        $loai = $loai_mons[$index];
        $stmt = $conn->prepare("SELECT Gia FROM $loai WHERE id=?");
        $stmt->execute([$mid]);
        $gia = $stmt->fetchColumn();
        $giaGoc += $gia;
    }

    // Tính giá cuối
    $giaCuoi = $giaGoc - ($giaGoc * $giamgia / 100);

    // Lưu combo
    $stmt = $conn->prepare("INSERT INTO Combo (TenCombo, GiaGoc, GiamGia, GiaCuoi, HinhAnh, ChiTiet) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ten, $giaGoc, $giamgia, $giaCuoi, $hinh, $chitiet]);
    $combo_id = $conn->lastInsertId();

    // Lưu các món thuộc combo
    foreach ($mon_ids as $index => $mid) {
        $loai = $loai_mons[$index];
        $stmt = $conn->prepare("INSERT INTO Combo_Mon (combo_id, mon_id, loai) VALUES (?, ?, ?)");
        $stmt->execute([$combo_id, $mid, $loai]);
    }

    header("Location: quanly_combo.php?status=added");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản Lý Combo</title>
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
        margin-bottom: 25px;
    }

    form {
        margin-bottom: 30px;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea {
        width: 100%;
        padding: 10px;
        margin: 8px 0 16px 0;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    textarea {
        min-height: 80px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

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

    button:hover {
        background: #e65c00;
    }

    a {
        display: inline-block;
        margin-bottom: 20px;
        color: #ff6600;
        font-weight: 600;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background: #ff914d;
        color: #fff;
    }

    tr:nth-child(even) {
        background: #f9f9f9;
    }

    tr:hover {
        background: #fff3e0;
    }

    td a {
        color: #d9534f;
        font-weight: 600;
        text-decoration: none;
    }

    td a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Quản Lý Combo</h1>

    <?php if ($action === 'add'): ?>
    <form method="POST" enctype="multipart/form-data">
        Tên combo: <input type="text" name="ten" required><br>
        Giảm giá (%): <input type="number" name="giamgia" min="0" max="100" required><br>
        Chọn hình: <input type="file" name="hinh"><br>
        Chi tiết: <textarea name="chitiet"></textarea><br>

        <h3>Chọn món cho combo</h3>
        <?php
        // Lấy danh sách món ăn/nước/tráng miệng
        $monan = $conn->query("SELECT id, TenMon, Gia FROM MonAn")->fetchAll(PDO::FETCH_ASSOC);
        $monnuoc = $conn->query("SELECT id, TenNuoc, Gia FROM MonNuoc")->fetchAll(PDO::FETCH_ASSOC);
        $trangmieng = $conn->query("SELECT id, TenTM, Gia FROM TrangMieng")->fetchAll(PDO::FETCH_ASSOC);

        echo "<h4>Món ăn</h4>";
        foreach ($monan as $m) {
            echo "<label><input type='checkbox' name='mon_ids[]' value='{$m['id']}'>
                  <input type='hidden' name='loai_mons[]' value='MonAn'>
                  {$m['TenMon']} - {$m['Gia']} đ</label><br>";
        }

        echo "<h4>Món nước</h4>";
        foreach ($monnuoc as $n) {
            echo "<label><input type='checkbox' name='mon_ids[]' value='{$n['id']}'>
                  <input type='hidden' name='loai_mons[]' value='MonNuoc'>
                  {$n['TenNuoc']} - {$n['Gia']} đ</label><br>";
        }

        echo "<h4>Tráng miệng</h4>";
        foreach ($trangmieng as $t) {
            echo "<label><input type='checkbox' name='mon_ids[]' value='{$t['id']}'>
                  <input type='hidden' name='loai_mons[]' value='TrangMieng'>
                  {$t['TenTM']} - {$t['Gia']} đ</label><br>";
        }
        ?>
        <button type="submit" name="add_combo">Thêm Combo</button>
    </form>
    <?php endif; ?>

    <a href="?action=add">+ Thêm Combo</a>
    <table>
        <tr><th>ID</th><th>Tên combo</th><th>Giá gốc</th><th>Giảm giá</th><th>Giá cuối</th><th>Hành động</th></tr>
        <?php
        $stmt = $conn->query("SELECT * FROM Combo ORDER BY id DESC");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['TenCombo']}</td>
                    <td>".number_format($row['GiaGoc'])." đ</td>
                    <td>{$row['GiamGia']}%</td>
                    <td>".number_format($row['GiaCuoi'])." đ</td>
                    <td><a href='?action=delete&id={$row['id']}'>Xóa</a></td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
