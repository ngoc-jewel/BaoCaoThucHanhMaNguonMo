<?php
session_start();
include("../../includes/database.php");

if (isset($_POST['checkout']))
     {
    // Lấy dữ liệu từ form
    $fullname       = trim($_POST['fullname']);
    $phone          = trim($_POST['phone']);
    $address        = trim($_POST['address']);
    $payment_method = $_POST['payment_method'];
    $tong_tien      = (float)$_POST['tong_tien'];
    // Kiểm tra user đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) 
    {
        die("❌ Bạn cần đăng nhập để đặt hàng.");
    }
    // Tạo đơn hàng (ví dụ bảng DonHang)
    $stmt = $conn->prepare("INSERT INTO DonHang (khach_hang_id, tong_tien, trang_thai, ngay_dat) 
                            VALUES (?, ?, ?, NOW())");
    $stmt->execute([$_SESSION['user_id'], $tong_tien, 'Chờ xử lý']);
    $donhang_id = $conn->lastInsertId();
    // Lưu thông tin thanh toán vào bảng thanhtoan
    $stmt = $conn->prepare
    ("INSERT INTO thanhtoan 
        (donhang_id, user_id, phuong_thuc, ho_ten, so_dien_thoai, dia_chi, tong_tien, trang_thai) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute
    (
        [
        $donhang_id,
        $_SESSION['user_id'],
        $payment_method,
        $fullname,
        $phone,
        $address,
        $tong_tien,
        'Chờ xử lý'
    ]);
    echo "<h2 style='color:green; text-align:center;'>✅ Đơn hàng của bạn đã đặt thành công!</h2>";
    echo "<p style='text-align:center;'><a href='../donhang/donhang_cuatoi.php'>Xem đơn hàng của bạn</a></p>";

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Reset cơ bản */
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Container chính */
main {
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Tiêu đề */
h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Label và input */
label {
    display: block;
    margin: 12px 0 6px;
    font-weight: bold;
    color: #444;
}

input[type="text"],
input[type="email"],
input[type="password"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 15px;
    transition: border-color 0.3s;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #007bff;
    outline: none;
}

/* Nút thanh toán */
button {
    width: 100%;
    background: #28a745;
    color: #fff;
    padding: 12px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background: #218838;
}

/* Thông tin tổng tiền */
.total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    color: #e60000;
    margin-top: 10px;
}/* Reset cơ bản */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #f0f4f8, #d9e8ff);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Container chính */
main {
    width: 100%;
    max-width: 500px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

/* Tiêu đề */
h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 24px;
}

/* Label và input */
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #34495e;
}

input[type="text"],
textarea,
select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0,123,255,0.3);
    outline: none;
}

/* Nút thanh toán */
button {
    width: 100%;
    background: #28a745;
    color: #fff;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

button:hover {
    background: #218838;
    transform: scale(1.02);
}

/* Thông tin tổng tiền */
.total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    color: #e60000;
    margin-top: 10px;
}


    </style>
</head>
<body>
    <form method="post">
    <label>Họ tên</label>
    <input type="text" name="fullname" value="<?= $_SESSION['username'] ?? '' ?>" required>
    <label>SĐT</label>
    <input type="text" name="phone" required>
    <label>Địa chỉ</label>
    <textarea name="address" required></textarea>
    <label>Phương thức thanh toán</label>
    <select name="payment_method">
        <option value="Tiền mặt">Tiền mặt</option>
        <option value="MOMO">MOMO</option>
        <option value="VNPAY">VNPAY</option>
        <option value="Thẻ">Thẻ</option>
        <option value="ZALOPAY">ZaloPay</option>
    </select>
    <input type="hidden" name="tong_tien" value="100000"> <!-- demo -->
    <button type="submit" name="checkout">Xác nhận thanh toán</button>
</form>
</body>
</html>

