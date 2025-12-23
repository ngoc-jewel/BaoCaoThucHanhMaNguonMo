<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
     header {
  background-color: #ff914d; /* Màu cam tươi */
  color: white;
  display: flex;
  justify-content: space-between; /* Logo trái, menu phải */
  align-items: center;
  padding: 15px 40px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
  position: relative; /* Added for dropdown positioning */
}

/* Logo */
.logo {
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}

/* Menu */
nav ul {
  list-style: none;
  display: flex;
  gap: 30px; /* Khoảng cách giữa các mục */
}

/* Liên kết menu */
nav ul li {
  position: relative; /* Make parent li a positioning context */
}

nav ul li a {
  text-decoration: none;
  color: white;
  font-size: 16px;
  font-weight: 500;
  transition: 0.3s;
  padding: 5px 0; /* Add padding for better hover area */
  display: block; /* Make the whole area clickable */
}

/* Hiệu ứng hover cho menu chính */
nav ul li a:hover {
  color: #ffe082; /* vàng nhạt */
  text-decoration: underline;
}

/* Dropdown menu */
nav ul li ul {
  display: none; /* Hide by default */
  position: absolute;
  background-color: #ff914d; /* Same as header */
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  top: 100%; /* Position below the parent */
  left: 0;
  padding: 10px 0;
  border-radius: 0 0 8px 8px; /* Rounded corners at the bottom */
  flex-direction: column; /* Stack items vertically */
  gap: 0; /* Remove gap for dropdown items */
}

nav ul li ul li {
  width: 100%; /* Full width for dropdown items */
}

nav ul li ul li a {
  padding: 10px 20px; /* Padding for dropdown links */
  white-space: nowrap; /* Prevent text wrapping */
}

/* Show dropdown on hover */
nav ul li:hover > ul {
  display: flex; /* Show dropdown as flex column */
}
    </style>
</head>
<body>

    <header>
    <div class="logo">🍔 Nhà Hàng__</div>
    <nav>
        <ul>
            <li><a href="/web_bandoan/index.php">Trang chủ</a></li>
            <li><a href="#">Menu</a>
                <ul>
                   <li><a href="/web_bandoan/pages/food/monan.php">Món ăn</a></li>
                    <li><a href="/web_bandoan/pages/food/monnuoc.php">Món nước</a></li> 
                    <li><a href="/web_bandoan/pages/food/combo.php">Combo</a></li> 
                    <li><a href="/web_bandoan/pages/food/trangmieng.php">Tráng miệng</a></li> 
                    <li><a href="/web_bandoan/pages/giohang/giohang.php">🛒 Giỏ hàng</a></li>
              </ul>
            </li>
          <li><a href="/web_bandoan/pages/giohang/giohang.php">🛒 Giỏ hàng</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
   <li class="dropdown">
    <a href="#">
        👤 <?= htmlspecialchars($_SESSION['user_name']) ?>
    </a>
    <ul class="dropdown-content">
        <li><a href="/web_bandoan/dangnhap/dangnhap.php">Đăng xuất</a></li>
        <li><a href="/web_bandoan/pages/donhang/donhang_cuatoi.php">Đơn hàng của bạn</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="/web_bandoan/admin/index.php">⚙️ Quản trị</a></li>
        <?php endif; ?>
    </ul>
</li>

<?php else: ?>
    <li><a href="/web_bandoan/dangnhap/dangnhap.php">Đăng nhập</a></li>
<li><a href="/web_bandoan/dangnhap/dangky.php">Đăng ký</a></li>


<?php endif; ?>

        </ul>
    </nav>
</header>

</body>
</html>

