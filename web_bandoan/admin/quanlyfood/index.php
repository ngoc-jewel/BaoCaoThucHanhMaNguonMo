<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f6f7fb;
  margin: 0;
  padding: 40px;
}

.menu {
  max-width: 900px;
  margin: 0 auto;
}

.menu-title {
  font-size: 26px;
  font-weight: 700;
  color: #ff6600;
  text-align: center;
  margin-bottom: 30px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}

.menu-item a {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  background: #fff;
  color: #333;
  text-decoration: none;
  border-radius: 14px;
  border: 1px solid #eee;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  font-weight: 600;
  font-size: 18px;
}

.menu-item a:hover {
  background: #ff914d;
  color: #fff;
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}

.menu-item a:active {
  transform: scale(0.98);
}

@media (max-width: 560px) {
  .menu-grid {
    grid-template-columns: 1fr;
  }
}


    </style>
</head>
<body>
    <div class="menu">
  <div class="menu-title">Quản lý thực đơn</div>
  <ul class="menu-grid">
    <li class="menu-item"><a href="monnoibat.php">🌟 QL Món Nổi Bật</a></li>
    <li class="menu-item"><a href="quanly_combo.php">🥗 QL Combo</a></li>
    <li class="menu-item"><a href="quanly_tonghop.php">🍲 QL Món Ăn</a></li>
    <li class="menu-item"><a href="quanly_trangmieng.php">🍰 QL Món Tráng Miệng</a></li>
    <li class="menu-item"><a href="quanlykhuyenmai.php">🎁 QL Khuyến Mãi</a></li>
    <li class="menu-item"><a href="quanlymonnuoc.php">🥤 QL Món Nước</a></li>
  </ul>
</div>

    
</body>
</html>