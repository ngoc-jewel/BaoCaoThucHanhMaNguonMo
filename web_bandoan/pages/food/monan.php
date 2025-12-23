<?php
session_start(); 
include("../../includes/database.php"); 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách món ăn - FoodZone</title>
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
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product h3 { margin: 10px 0; }
        .product p { color: #666; }
        .product .price { color: #e60000; font-weight: bold; }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
        }
        .btn:hover { background-color: #218838; }
    </style>
</head>
<body>
    
    <?php include("../../layouts/header.php"); ?>
    <main>
        <h1>🍲 Danh sách món ăn</h1>
        
        <div class="product-list">
            
            <?php
            $stmt = $conn->prepare("SELECT id,TenMon, Gia, HinhAnh, ChiTiet FROM MonAn WHERE LoaiMonAn = 'Món ăn'");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($products) {
                foreach ($products as $p) {
                   echo '<div class="product">';
                    if (!empty($p['HinhAnh'])) {
                        echo '<img src="../images/' . htmlspecialchars($p['HinhAnh']) . '" alt="' . htmlspecialchars($p['TenMon']) . '">';
                    }
                    echo '<h3>' . htmlspecialchars($p['TenMon']) . '</h3>';
                    echo '<p class="price">' . number_format($p['Gia'], 0, ',', '.') . ' VND</p>';
                    echo '<p>' . htmlspecialchars($p['ChiTiet']) . '</p>';

                    

                    echo '<form method="get" action="../giohang/giohang.php" style="display:inline-block; margin:5px;">';
                    echo '<input type="hidden" name="add" value="'.$p['id'].'">';   // thêm id sản phẩm
                    echo '<input type="hidden" name="loai" value="MonAn">';
                    echo '<input type="number" id="soluong_'.$p['id'].'" name="soluong" value="1" min="1">';
                    echo '<button type="submit">Thêm vào giỏ</button>';
                    echo '</form>';


                   // Form thanh toán trực tiếp cho từng món
                    echo '<form method="get" action="../thanhtoan/thanhtoan.php" 
                    style="display:inline-block; margin:5px;"onsubmit="this.soluong.value=document.getElementById(\'soluong_'.$p['id'].'\').value">';
                    echo '<input type="hidden" name="id" value="' . $p['id'] . '">';
                    echo '<input type="hidden" name="loai" value="MonAn">';
                    echo '<input type="hidden" name="soluong" value="1">';
                    echo '<button type="submit" class="btn">Thanh toán</button>'; 
                    echo '</form>';

                    echo '</div>';
                }
            } else {
                echo "<p>Chưa có món ăn nào trong hệ thống.</p>";
            }
            
            ?>
        </div>
       
    </main>
 
    <?php include("../../layouts/footer.php"); ?>
</body>
</html>
