<?php
session_start();
include '../../includes/database.php'; // $conn là PDO

// Xử lý Thêm/Sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $id            = $_POST['id'];
    $ten_km        = $_POST['ten_km'];
    $mo_ta         = $_POST['mo_ta'];
    $giam_gia      = $_POST['giam_gia'];
    $ngay_bat_dau  = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

    if (empty($ten_km) || empty($giam_gia) || empty($ngay_bat_dau) || empty($ngay_ket_thuc)) {
        $error = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } else {
        if (empty($id)) {
            // Thêm mới
            $stmt = $conn->prepare("INSERT INTO khuyenmai (ten_km, mo_ta, giam_gia, ngay_bat_dau, ngay_ket_thuc) 
                                    VALUES (?, ?, ?, ?, ?)");
            $ok = $stmt->execute([$ten_km, $mo_ta, $giam_gia, $ngay_bat_dau, $ngay_ket_thuc]);
        } else {
            // Cập nhật
            $stmt = $conn->prepare("UPDATE khuyenmai 
                                    SET ten_km = ?, mo_ta = ?, giam_gia = ?, ngay_bat_dau = ?, ngay_ket_thuc = ? 
                                    WHERE id = ?");
            $ok = $stmt->execute([$ten_km, $mo_ta, $giam_gia, $ngay_bat_dau, $ngay_ket_thuc, $id]);
        }

        if ($ok) {
            header("Location: quanlykhuyenmai.php");
            exit();
        } else {
            $error = "Có lỗi xảy ra khi lưu khuyến mãi.";
        }
    }
}
// Xử lý Xóa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM khuyenmai WHERE id = ?");
    $ok = $stmt->execute([$id]);
    if ($ok) {
        header("Location: quanlykhuyenmai.php");
        exit();
    } else {
        $error = "Lỗi khi xóa khuyến mãi.";
    }
}

// Lấy dữ liệu để sửa
$khuyenmai_edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM khuyenmai WHERE id = ?");
    $stmt->execute([$id]);
    $khuyenmai_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách khuyến mãi
$stmt = $conn->query("SELECT * FROM khuyenmai ORDER BY id DESC");
$khuyenmai_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khuyến Mãi</title>
   <style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f6f7fb;
        margin: 0;
        padding: 30px;
        color: #333;
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

    .error {
        color: #E74C3C;
        font-weight: 600;
        margin-bottom: 15px;
    }

    form {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    form label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="date"],
    form textarea {
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

    .action-links a.edit {
        color: #E67E22;
    }

    .action-links a.delete {
        color: #E74C3C;
    }

    .action-links a:hover {
        text-decoration: underline;
    }
</style>

    
</head>
<body>
    <h1>Quản Lý Khuyến Mãi</h1>
    <a href="index.php">Quay về trang quản trị</a>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <h2><?php echo $khuyenmai_edit ? 'Chỉnh Sửa Khuyến Mãi' : 'Thêm Khuyến Mãi Mới'; ?></h2>
    <form action="quanlykhuyenmai.php" method="post">
        <input type="hidden" name="id" value="<?php echo $khuyenmai_edit['id'] ?? ''; ?>">
        
        <label for="ten_km">Tên Khuyến Mãi (*)</label>
        <input type="text" id="ten_km" name="ten_km" value="<?php echo htmlspecialchars($khuyenmai_edit['ten_km'] ?? ''); ?>" required>
        
        <label for="mo_ta">Mô Tả</label>
        <textarea id="mo_ta" name="mo_ta" rows="4"><?php echo htmlspecialchars($khuyenmai_edit['mo_ta'] ?? ''); ?></textarea>
        
        <label for="giam_gia">Giảm Giá (%) (*)</label>
        <input type="number" id="giam_gia" name="giam_gia" value="<?php echo htmlspecialchars($khuyenmai_edit['giam_gia'] ?? ''); ?>" required>
        
        <label for="ngay_bat_dau">Ngày Bắt Đầu (*)</label>
        <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" value="<?php echo $khuyenmai_edit['ngay_bat_dau'] ?? ''; ?>" required>
        
        <label for="ngay_ket_thuc">Ngày Kết Thúc (*)</label>
        <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" value="<?php echo $khuyenmai_edit['ngay_ket_thuc'] ?? ''; ?>" required>
        
        <button type="submit" name="save"><?php echo $khuyenmai_edit ? 'Cập Nhật' : 'Thêm Mới'; ?></button>
        <?php if ($khuyenmai_edit): ?>
            <a href="quanlykhuyenmai.php">Hủy</a>
        <?php endif; ?>
        
    </form>

    <h2>Danh Sách Khuyến Mãi</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Khuyến Mãi</th>
                <th>Mô Tả</th>
                <th>Giảm Giá (%)</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($khuyenmai_list as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['ten_km']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['mo_ta'])); ?></td>
                <td><?php echo $row['giam_gia']; ?></td>
                <td><?php echo $row['ngay_bat_dau']; ?></td>
                <td><?php echo $row['ngay_ket_thuc']; ?></td>
                <td class="action-links">
                    <a class="edit" href="quanlykhuyenmai.php?edit=<?php echo $row['id']; ?>">Sửa</a>
                    <a class="delete" href="quanlykhuyenmai.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này không?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
