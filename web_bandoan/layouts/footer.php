<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
      /* Đảm bảo footer luôn nằm dưới cùng */
body {
  margin: 0;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex: 1;
}

/* Footer chính */
.footer {
  background-color: #ff914d;
  color: white;
  padding: 40px 20px 20px;
  font-size: 14px;
  margin-top: auto;
}

/* Bố cục 3 cột */
.footer-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.footer-about,
.footer-links,
.footer-contact {
  flex: 1;
  min-width: 250px;
}

/* Tiêu đề trong footer */
.footer h3,
.footer h4 {
  margin-bottom: 15px;
  font-size: 18px;
  color: #ffe082;
}

/* Nội dung và liên kết */
.footer p,
.footer a {
  color: white;
  text-decoration: none;
  margin-bottom: 8px;
  display: block;
}

.footer a:hover {
  text-decoration: underline;
  color: #ffe082;
}

/* Dòng cuối cùng */
.footer-bottom {
  text-align: center;
  margin-top: 30px;
  border-top: 1px solid rgba(255,255,255,0.3);
  padding-top: 15px;
  font-size: 13px;
}

    </style>
</head>
<body>
    <footer class="footer">
  <div class="footer-container">
    <div class="footer-about">
      <h3>🍔 FoodZone</h3>
      <p>FoodZone là nơi hội tụ những món ăn ngon, phục vụ nhanh chóng và tận tâm. Cảm ơn bạn đã đồng hành cùng chúng tôi!</p>
    </div>
    <div class="footer-links">
      <h4>Liên kết nhanh</h4>
      <ul>
        <li><a href="#">Trang chủ</a></li>
        <li><a href="#">Menu</a></li>
        <li><a href="#">Khuyến mãi</a></li>
        <li><a href="giohang.php">Giỏ hàng</a></li>
      </ul>
    </div>
    <div class="footer-contact">
      <h4>Liên hệ</h4>
      <p>📍 123 Đường ----, Quận ---, TP.HCM</p>
      <p>📞 0909-------</p>
      <p>📧 support@foodzone.vn</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 FoodZone. All rights reserved.</p>
  </div>
</footer>
</body>
</html>
