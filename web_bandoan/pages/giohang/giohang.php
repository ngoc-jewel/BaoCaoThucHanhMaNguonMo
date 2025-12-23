<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../../includes/database.php");

// ================== XỬ LÝ THÊM MÓN VÀO GIỎ ==================
if (isset($_GET['add']) && isset($_GET['loai'])) {
    $id = (int)$_GET['add'];
    
    $loai = $_GET['loai']; // MonAn, MonNuoc, TrangMieng, Combo
    $soluong = isset($_GET['soluong']) ? (int)$_GET['soluong'] : 1;

    // Lấy thông tin món từ DB
    $stmt = $conn->prepare("SELECT * FROM $loai WHERE id = ?");
    $stmt->execute([$id]);
    $mon = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($mon) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Key duy nhất cho giỏ hàng (id + loại)
        $key = $loai . "_" . $id;

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['soluong'] += $soluong;
        } else {
            // Xác định tên và giá theo loại
            $ten = $mon['TenMon'] ?? $mon['TenNuoc'] ?? $mon['TenTM'] ?? $mon['TenCombo'];
            $gia = $mon['Gia'] ?? $mon['GiaCuoi'];

            $_SESSION['cart'][$key] = [
                'id' => $id,
                'loai' => $loai,
                'ten' => $ten,
                'gia' => $gia,
                'soluong' => $soluong
            ];
        }
    }
    header("Location: giohang.php");
    exit();
}

// ================== XỬ LÝ TĂNG/GIẢM SỐ LƯỢNG ==================
if (isset($_GET['update']) && isset($_GET['action'])) {
    $key = $_GET['update'];
    $action = $_GET['action'];

    if (isset($_SESSION['cart'][$key])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$key]['soluong']++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$key]['soluong']--;
            if ($_SESSION['cart'][$key]['soluong'] <= 0) {
                unset($_SESSION['cart'][$key]); // nếu giảm về 0 thì xóa luôn
            }
        }
    }
    header("Location: giohang.php");
    exit();
}

// ================== XỬ LÝ XÓA MÓN ==================
if (isset($_GET['remove'])) {
    $key = $_GET['remove'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: giohang.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - FoodZone</title>
    <style>
        /* Reset cơ bản */
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #fff8f0;
  color: #333;
}

/* Tiêu đề chính */
main h1 {
  text-align: center;
  color: #ff914d;
  margin: 30px 0;
}

/* Bảng giỏ hàng */
table {
  width: 90%;
  margin: 0 auto 30px auto;
  border-collapse: collapse;
  background-color: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
}

table th, table td {
  padding: 12px 15px;
  text-align: center;
}

table th {
  background-color: #ff914d;
  color: white;
  font-weight: 600;
}

table tr:nth-child(even) {
  background-color: #f9f9f9;
}

table tr:hover {
  background-color: #fff3e0;
}

/* Nút tăng/giảm số lượng */
.qty-btn {
  background-color: #ff914d;
  border: none;
  color: white;
  font-size: 16px;
  padding: 5px 10px;
  margin: 0 5px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.qty-btn:hover {
  background-color: #ffb74d;
}

/* Nút thanh toán */
.btn {
  display: block;
  width: 200px;
  margin: 20px auto;
  text-align: center;
  background-color: #ff914d;
  color: white;
  text-decoration: none;
  padding: 12px;
  border-radius: 6px;
  font-weight: bold;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: #ffb74d;
}

/* Thông báo giỏ hàng trống */
main p {
  text-align: center;
  font-size: 18px;
  color: #666;
}

    </style>
</head>
<body>
    <?php include("../../layouts/header.php"); ?>
    <main>
        <h1>🛒  GIỎ HÀNG CỦA BẠN</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <tr>
                    <th>Tên món</th>
                    <th>Loại</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
                <?php $tong = 0; ?>
                <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                    <?php $thanhtien = $item['gia'] * $item['soluong']; $tong += $thanhtien; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ten']) ?></td>
                        <td><?= htmlspecialchars($item['loai']) ?></td>
                        <td>
                            <form method="get" action="giohang.php" style="display:inline;">
                                <input type="hidden" name="update" value="<?= $key ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="qty-btn">-</button>
                            </form>
                            <strong><?= $item['soluong'] ?></strong>
                            <form method="get" action="giohang.php" style="display:inline;">
                                <input type="hidden" name="update" value="<?= $key ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="qty-btn">+</button>
                            </form>
                        </td>
                        <td><?= number_format($item['gia'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($thanhtien, 0, ',', '.') ?> VND</td>
                        <td><a href="giohang.php?remove=<?= $key ?>">Xóa</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Tổng cộng</strong></td>
                    <td colspan="2"><strong><?= number_format($tong, 0, ',', '.') ?> VND</strong></td>
                </tr>
            </table>
            <a href="../thanhtoan/thanhtoan.php" class="btn">Thanh toán</a>
        <?php else: ?>
            <p>Giỏ hàng đang trống.</p>
        <?php endif; ?>
    </main>
    <?php include("../../layouts/footer.php"); ?>
</body>
</html>
