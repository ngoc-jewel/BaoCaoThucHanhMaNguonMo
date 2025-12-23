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
    <title>Danh sách tráng miệng - FoodZone</title>
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
        <h1>🍰 Danh sách tráng miệng</h1>
        <div class="product-list">
            <?php
            // Lấy dữ liệu từ bảng TrangMieng
            $stmt = $conn->prepare("SELECT id, TenTM, Gia, HinhAnh, ChiTiet FROM TrangMieng");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($products) {
                foreach ($products as $p) {
                    echo '<div class="product">';
                    if (!empty($p['HinhAnh'])) {
                        echo '<img src="../images/' . htmlspecialchars($p['HinhAnh']) . '" alt="' . htmlspecialchars($p['TenTM']) . '">';
                    }
                    echo '<h3>' . htmlspecialchars($p['TenTM']) . '</h3>';
                    echo '<p class="price">' . number_format($p['Gia'], 0, ',', '.') . ' VND</p>';
                    echo '<p>' . htmlspecialchars($p['ChiTiet']) . '</p>';

                    // Form thêm vào giỏ
                    echo '<form method="get" action="../pages/giohang.php" style="margin:5px;">';
                    echo '<input type="hidden" name="add" value="' . $p['id'] . '">';
                    echo '<input type="hidden" name="loai" value="TrangMieng">';
                    
                    echo '<button type="submit">Thêm vào giỏ</button>';
                    echo '</form>';

                    // Form đặt ngay
                    echo '<form method="get" action="../pages/thanhtoan.php" style="margin:5px;">';
                    echo '<input type="hidden" name="id" value="' . $p['id'] . '">';
                    echo '<input type="hidden" name="loai" value="TrangMieng">';
                    echo '<label>Số lượng:</label>';
                    echo '<input type="number" name="soluong" value="1" min="1">';
                    // Form thanh toán trực tiếp cho từng món
                    echo '<form method="get" action="../pages/thanhtoan.php" 
                    style="display:inline-block; margin:5px;"onsubmit="this.soluong.value=document.getElementById(\'soluong_'.$p['id'].'\').value">';
                    echo '<input type="hidden" name="id" value="' . $p['id'] . '">';
                    echo '<input type="hidden" name="loai" value="MonAn">';
                    echo '<input type="hidden" name="soluong" value="1">';
                    echo '<button type="submit" class="btn">Thanh toán</button>'; 
                    echo '</form>';
                   
                    echo '</form>';

                    echo '</div>';

                }
            } else {
                echo "<p>Chưa có món tráng miệng nào trong hệ thống.</p>";
            }
            ?>
        </div>
    </main>
    <?php include("../../layouts/footer.php"); ?>
</body>
</html>
