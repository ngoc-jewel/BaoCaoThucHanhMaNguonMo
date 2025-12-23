<?php

require_once("../includes/database.php"); // Kết nối CSDL
$thongbao = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $ten = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $matkhau = trim($_POST["password"]);

    // Kiểm tra dữ liệu không được để trống
    if (empty($ten) || empty($email) || empty($matkhau))
    {
        $thongbao = "Vui lòng nhập đầy đủ thông tin.";
    } 
    else
    {
        // Kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT id FROM khachhang WHERE Email = ?");
                $select_success = $stmt->execute([$email]);
        
                if (!$select_success) {
                    $thongbao = "Lỗi khi kiểm tra email tồn tại. Vui lòng thử lại.";
                } else if ($stmt->rowCount() > 0)
                {
                    $thongbao = "Email đã được sử dụng.";
                }
                else
                {
                    // Mã hóa mật khẩu
                    $matkhau_mahoa = password_hash($matkhau, PASSWORD_DEFAULT);
        
                    // Thêm người dùng mới
                    $stmt = $conn->prepare("INSERT INTO KhachHang (Ten, Email, MatKhau) VALUES (?, ?, ?)");
                    $result=$stmt->execute([$ten,$email,$matkhau_mahoa]);
        
                    if ($result)
                    {
                        $thongbao = "Đăng ký thành công! Bạn có thể <a href='dangnhap.php'>đăng nhập</a>.";
                    }
                     else
                    {
                        $thongbao = "Lỗi khi đăng ký. Vui lòng thử lại.";
                    }
                }
        $stmt=null;
    }

    $conn=null;
}
// Dòng 46 (đã sửa): Luôn đóng kết nối CSDL ở cuối script để tránh rò rỉ tài nguyên.
$conn=null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <style>
        /* CSS giữ nguyên như bạn đã viết ở trên */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ff6600;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e65c00;
        }

        p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #ff6600;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form action="/dangky.php" method="post">
        <h2>Đăng ký tài khoản</h2>
        <?php if (!empty($thongbao)): ?>
            <div class="message"><?php echo $thongbao; ?></div>
        <?php endif; ?>
        <label for="name">Tên:</label>
        <input type="text" id="name" name="name" placeholder="Nhập tên..." required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Nhập email..." required>
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required>
        <button type="submit">Đăng Ký</button>
        <p>Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a></p>
    </form>
</body>
</html>
