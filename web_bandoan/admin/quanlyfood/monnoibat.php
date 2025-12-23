<?php
session_start();
include("../../includes/database.php");

// ===== DANH SÁCH BẢNG THEO LOẠI =====
$LOAI_TABLES = [
    'MonAn'     => 'MonAn',
    'MonNuoc'   => 'MonNuoc',
    'KhuyenMai' => 'khuyenmai',
    'Combo'     => 'combo'
];

$message = "";

// ===== XỬ LÝ THÊM =====
if (isset($_POST['add'])) {
    $loai   = $_POST['loai'] ?? '';
    $mon_id = $_POST['mon_id'] ?? '';

    if (!empty($loai) && !empty($mon_id)) {

        // Kiểm tra trùng
        $check = $conn->prepare("SELECT id FROM MonNoiBat WHERE mon_id = ? AND loai = ?");
        $check->execute([$mon_id, $loai]);

        if ($check->fetch()) {
            $message = "❌ Món này đã có trong danh sách nổi bật!";
        } else {
            $stmt = $conn->prepare("INSERT INTO MonNoiBat (mon_id, loai) VALUES (?, ?)");
            $stmt->execute([$mon_id, $loai]);
            $message = "✅ Đã thêm món nổi bật thành công!";
        }
    }
}

// ===== XỬ LÝ XÓA =====
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $del = $conn->prepare("DELETE FROM MonNoiBat WHERE id = ?");
    $del->execute([$id]);
    header("Location: monnoibat.php");
    exit;
}

// ===== LOAD DANH SÁCH MÓN THEO LOẠI =====
$currentLoai = $_GET['loai'] ?? '';
$monList = [];

if (!empty($currentLoai) && isset($LOAI_TABLES[$currentLoai])) {
    $table = $LOAI_TABLES[$currentLoai];

    if ($currentLoai == 'MonNuoc') {
        $stmt = $conn->prepare("SELECT id, TenNuoc AS TenMon FROM MonNuoc ORDER BY TenNuoc ASC");
    }
    elseif ($currentLoai == 'KhuyenMai') {
        $stmt = $conn->prepare("SELECT id, ten_km AS TenMon FROM khuyenmai ORDER BY ten_km ASC");
    }
    elseif ($currentLoai == 'Combo') {
    $stmt = $conn->prepare("SELECT id, TenCombo AS TenMon FROM combo ORDER BY TenCombo ASC");
}
else {
    $stmt = $conn->prepare("SELECT id, TenMon FROM $table ORDER BY TenMon ASC");
}


    $stmt->execute();
    $monList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===== LOAD DANH SÁCH MÓN NỔI BẬT =====
$noibatList = [];
$stmt = $conn->prepare("SELECT * FROM MonNoiBat ORDER BY created_at DESC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $loai = $row['loai'];
    $id   = $row['mon_id'];

    if ($loai == 'MonNuoc') {
        $q = $conn->prepare("SELECT TenNuoc AS TenMon, Gia, HinhAnh FROM MonNuoc WHERE id = ?");
    }
    elseif ($loai == 'KhuyenMai') {
        $q = $conn->prepare("SELECT ten_km AS TenMon, giam_gia AS Gia, NULL AS HinhAnh FROM khuyenmai WHERE id = ?");
    }
    elseif ($loai == 'Combo') {
    $q = $conn->prepare("SELECT TenCombo AS TenMon, GiaCuoi AS Gia, HinhAnh 
                         FROM combo WHERE id = ?");
}
else {
    $table = $LOAI_TABLES[$loai];
    $q = $conn->prepare("SELECT TenMon, Gia, HinhAnh FROM $table WHERE id = ?");
}


    $q->execute([$id]);
    $mon = $q->fetch(PDO::FETCH_ASSOC);

    if ($mon) {
        $noibatList[] = [
            'nb_id'   => $row['id'],
            'loai'    => $loai,
            'TenMon'  => $mon['TenMon'],
            'Gia'     => $mon['Gia'],
            'HinhAnh' => $mon['HinhAnh'],
            'created_at' => $row['created_at']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý món nổi bật</title>

    <style>
        body { font-family: Arial; background: #f4f6f9; margin: 0; padding: 0; }
        .container {
            max-width: 1100px; margin: 30px auto; background: #fff;
            padding: 25px; border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,0.1);
        }
        h1 { text-align: center; color: #e67e22; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 6px; }
        .success { background: #e8f8f5; color: #148f77; }
        .error { background: #fdecea; color: #c0392b; }
        select, button {
            padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px;
        }
        button { background: #27ae60; color: white; cursor: pointer; }
        button:hover { background: #1e8449; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; }
        .thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
    </style>
</head>

<body>
<div class="container">

    <h1>Quản lý món nổi bật</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- CHỌN LOẠI -->
    <form method="get">
        <label>Chọn loại món:</label>
        <select name="loai" onchange="this.form.submit()">
            <option value="">-- Chọn loại --</option>
            <?php foreach ($LOAI_TABLES as $key => $tbl): ?>
                <option value="<?= $key ?>" <?= ($currentLoai == $key) ? 'selected' : '' ?>>
                    <?= $key ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- CHỌN MÓN -->
    <?php if (!empty($monList)): ?>
        <form method="post" style="margin-top: 15px;">
            <input type="hidden" name="loai" value="<?= $currentLoai ?>">

            <label>Chọn món:</label>
            <select name="mon_id">
                <?php foreach ($monList as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= $m['TenMon'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="add">Thêm vào nổi bật</button>
        </form>
    <?php endif; ?>

    <!-- DANH SÁCH MÓN NỔI BẬT -->
    <h2 style="margin-top: 30px;">Danh sách món nổi bật</h2>

    <?php if (!empty($noibatList)): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Ảnh</th>
                <th>Tên món</th>
                <th>Loại</th>
                <th>Giá / Giảm</th>
                <th>Ngày thêm</th>
                <th>Hành động</th>
            </tr>

            <?php foreach ($noibatList as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>

                    <td>
                        <?php if (!empty($item['HinhAnh'])): ?>
                            <img class="thumb" src="../assets/images/<?= $item['HinhAnh'] ?>">
                        <?php else: ?>
                            Không có ảnh
                        <?php endif; ?>
                    </td>

                    <td><?= $item['TenMon'] ?></td>
                    <td><?= $item['loai'] ?></td>

                    <td>
                        <?php if ($item['loai'] == 'KhuyenMai'): ?>
                            Giảm <?= $item['Gia'] ?>%
                        <?php else: ?>
                            <?= number_format($item['Gia'], 0, ',', '.') ?> VND
                        <?php endif; ?>
                    </td>

                    <td><?= $item['created_at'] ?></td>

                    <td>
                        <a href="monnoibat.php?delete=<?= $item['nb_id'] ?>"
                           onclick="return confirm('Xóa món này khỏi nổi bật?');">
                            <button class="btn-danger">Xóa</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p>Chưa có món nổi bật nào.</p>
    <?php endif; ?>

</div>
</body>
</html>
