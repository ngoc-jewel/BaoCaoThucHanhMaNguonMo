<?php
include_once "../includes/check_admin.php";

if (!is_admin()) {
    die("Bạn không có quyền truy cập trang này.");
}

include "../includes/database.php";

//======================================================================
// PHẦN XỬ LÝ LOGIC (THÊM, SỬA, XÓA) - PDO
//======================================================================

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

// --- XỬ LÝ XÓA KHÁCH HÀNG ---
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM KhachHang WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: quanly_khachhang_tonghop.php?status=deleted");
    exit();
}

// --- XỬ LÝ THÊM & CẬP NHẬT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten   = $_POST['ten'];
    $email = $_POST['email'];

    // Thêm khách hàng mới
    if (isset($_POST['add_customer'])) {
        $matkhau_hashed = password_hash($_POST['matkhau'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO KhachHang (Ten, Email, MatKhau) VALUES (?, ?, ?)");
        $stmt->execute([$ten, $email, $matkhau_hashed]);

        header("Location: quanly_khachhang_tonghop.php?status=added");
        exit();
    }

    // Cập nhật khách hàng
    if (isset($_POST['update_customer'])) {
        $id_update = (int)$_POST['id'];

        if (!empty($_POST['matkhau'])) {
            $matkhau_hashed = password_hash($_POST['matkhau'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE KhachHang SET Ten=?, Email=?, MatKhau=? WHERE id=?");
            $stmt->execute([$ten, $email, $matkhau_hashed, $id_update]);
        } else {
            $stmt = $conn->prepare("UPDATE KhachHang SET Ten=?, Email=? WHERE id=?");
            $stmt->execute([$ten, $email, $id_update]);
        }

        header("Location: quanly_khachhang_tonghop.php?status=updated");
        exit();
    }
}

//======================================================================
// PHẦN HIỂN THỊ GIAO DIỆN (VIEW)
//======================================================================
?>
<?php
//======================================================================
// PHẦN HIỂN THỊ GIAO DIỆN (VIEW) - PDO
//======================================================================
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Khách Hàng</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f7f7f7; }
        .action-links a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .action-links a.delete { color: #dc3545; }
        .form-container { background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #ff914d; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-add { display: inline-block; background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        .admin-nav { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="admin-nav">
        <a href="index.php">❮ Trở về Dashboard</a>
    </div>
    <h1>Quản Lý Khách Hàng</h1>

    <?php
    // HIỂN THỊ FORM THÊM/SỬA
    if ($action === 'add' || $action === 'edit')
         {
        $kh = ['Ten' => '', 'Email' => '', 'id' => ''];
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT id, Ten, Email FROM KhachHang WHERE id = ?");
            $stmt->execute([$id]);
            $kh = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    ?>
        <div class="form-container">
            <h2><?= $action === 'add' ? 'Thêm Khách Hàng Mới' : 'Chỉnh Sửa Khách Hàng' ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $kh['id'] ?>">
                Tên khách hàng:
                <input type="text" name="ten" value="<?= htmlspecialchars($kh['Ten']) ?>" required><br>
                Email:
                <input type="email" name="email" value="<?= htmlspecialchars($kh['Email']) ?>" required><br>
                Mật khẩu:
                <input type="password" name="matkhau" placeholder="<?= $action === 'edit' ? 'Để trống nếu không muốn đổi' : 'Nhập mật khẩu' ?>" <?= $action === 'add' ? 'required' : '' ?>><br>
                <button type="submit" name="<?= $action === 'add' ? 'add_customer' : 'update_customer' ?>">
                    <?= $action === 'add' ? 'Thêm' : 'Cập nhật' ?>
                </button>
                <a href="quanly_khachhang_tonghop.php">Hủy</a>
            </form>
        </div>
    <?php 
}
 ?>

    <!-- HIỂN THỊ DANH SÁCH KHÁCH HÀNG -->
    <a href="?action=add" class="btn-add">+ Thêm Khách Hàng</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php
        $stmt = $conn->query("SELECT * FROM KhachHang ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['Ten']) ?></td>
            <td><?= htmlspecialchars($row['Email']) ?></td>
            <td><?= $row['NgayTao'] ?></td>
            <td class="action-links">
                <a href="?action=edit&id=<?= $row['id'] ?>">Sửa</a>
                <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')" class="delete">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
