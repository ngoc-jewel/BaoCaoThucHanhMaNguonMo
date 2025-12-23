<?php
session_start(); 
// Bắt đầu session để có thể dùng $_SESSION
include("../../includes/database.php"); 
// Kết nối đến CSDL bằng file database.php (chứa PDO)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Combo - FoodZone</title>
    <style>
        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            background: #fafafa;
        }
        .product img { max-width: 100%; height: auto; }
        .product h3 { margin: 10px 0; }
        .product p { color: #666; }
        .product .price { color: #e60000; font-weight: bold; }
        .product form { display:inline-block; margin:5px; }
        .product button {
            padding: 8px 12px;
            border: none;
            background: #28a745;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .product button:hover { background: #218838; }
    </style>
</head>
<body>
    <?php include("../../layouts/header.php"); ?>
    <main>
        <h1>🎁 Danh sách Combo</h1>
        <div class="product-list">
            <?php
            // Lấy dữ liệu từ bảng Combo
            $stmt = $conn->prepare("SELECT id, TenCombo, GiaGoc, GiamGia, GiaCuoi, HinhAnh, ChiTiet FROM Combo ORDER BY id DESC");
            $stmt->execute();
            $combos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($combos) 
                {
                foreach ($combos as $c) 
                {
                    echo '<div class="product">';
                   echo '<div class="product">';
if (!empty($c['HinhAnh'])) {
    echo '<img src="../uploads/' . htmlspecialchars($c['HinhAnh']) . '" alt="' . htmlspecialchars($c['TenCombo']) . '">';
}
echo '<h3>' . htmlspecialchars($c['TenCombo']) . '</h3>';
echo '<p>Giá gốc: ' . number_format($c['GiaGoc'], 0, ',', '.') . ' VND</p>';
echo '<p>Giảm giá: ' . htmlspecialchars($c['GiamGia']) . '%</p>';
echo '<p class="price">Giá sau giảm: ' . number_format($c['GiaCuoi'], 0, ',', '.') . ' VND</p>';
echo '<p>' . htmlspecialchars($c['ChiTiet']) . '</p>';

// 👉 Ô nhập số lượng chung
echo '<label>Số lượng:</label>';
echo '<input type="number" id="soluong_'.$c['id'].'" value="1" min="1">';

// Form thêm vào giỏ
echo '<form method="get" action="../pages/giohang.php" style="display:inline-block; margin:5px;" 
      onsubmit="this.soluong.value=document.getElementById(\'soluong_'.$c['id'].'\').value">';
echo '<input type="hidden" name="add" value="' . $c['id'] . '">';
echo '<input type="hidden" name="loai" value="Combo">';
echo '<input type="hidden" name="soluong" value="1">';
echo '<button type="submit">Thêm vào giỏ</button>';
echo '</form>';

// Form thanh toán trực tiếp
echo '<form method="get" action="../pages/thanhtoan.php" style="display:inline-block; margin:5px;" 
      onsubmit="this.soluong.value=document.getElementById(\'soluong_'.$c['id'].'\').value">';
echo '<input type="hidden" name="id" value="' . $c['id'] . '">';
echo '<input type="hidden" name="loai" value="Combo">';
echo '<input type="hidden" name="soluong" value="1">';
echo '<button type="submit" class="btn">Thanh toán</button>';
echo '</form>';

echo '</div>';

                   

                }
                 } 
            else
            {
                echo "<p>Chưa có combo nào trong hệ thống.</p>";
            }
            ?>
        </div>
    </main>
    <?php include("../../layouts/footer.php"); ?>
</body>
</html>
