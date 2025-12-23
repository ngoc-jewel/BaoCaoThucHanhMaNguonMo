<?php
//Bắt đầu một phiên làm việc (session) để lưu thông tin người dùng sau khi đăng nhập thành công.
session_start();
// Kết nối CSDL
require_once("../includes/database.php"); 
//Dùng để lưu thông báo lỗi hoặc trạng thái đăng nhập.
$thongbao = "";
//Kiểm tra nếu form được gửi bằng phương thức POST (người dùng nhấn nút đăng nhập)
if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
     //Lấy dữ liệu từ form (email, password) và loại bỏ khoảng trắng thừa bằng trim()
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

        //Nếu người dùng chưa nhập email hoặc mật khẩu → báo lỗi.
    if (empty($email) || empty($password)) 
    {
        $thongbao = "Vui lòng nhập đầy đủ thông tin.";
    }
     else 
    {
         // Truy vấn thông tin tài khoản từ MySQL
        //Chuẩn bị câu lệnh SQL để tìm tài khoản theo email.
       //Sử dụng Prepared Statement để tránh SQL Injection.
        $stmt = $conn->prepare("SELECT id, Ten, MatKhau FROM khachhang WHERE Email = ?");
        //Gắn giá trị $email vào dấu ? trong câu SQL     
        $stmt->execute([$email]);
        //Nếu tìm thấy đúng 1 tài khoản với email này.
        if ($stmt->rowCount()==1) 
        {
            //Lấy dữ liệu từ kết quả truy vấn: id, Ten (tên khách hàng), MatKhau (mật khẩu đã mã hóa trong DB)
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            $id=$row['id'];
            $ten=$row['Ten'];
            $matkhau_db=$row['MatKhau'];
            // Kiểm tra mật khẩu
            if (password_verify($password, $matkhau_db))
            {
                // Lưu thông tin tài khoản vào session
                $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $ten;
                // Chuyển đến trang chủ mua sắm
                header("Location: ../index.php");
                exit();
            }
            else 
            {
                $thongbao = "Mật khẩu không đúng.";
            }
        } 
        else 
        {
            $thongbao = "Email không tồn tại.";
        }
        //Đóng statement và kết nối CSDL sau khi xử lý xong.
        $stmt=null;
    }
    // đóng kết nối csdl
    $conn=null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
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
    </style>
</head>
<body>
    <!-- FORM ĐĂNG NHẬP-->
   <form action="/web_bandoan/dangnhap/dangnhap.php" method="post">

    <!-- Form gửi dữ liệu đến file dangnhap.php bằng phương thức POST --> 
    <h2>Đăng nhập</h2>
    <!-- Tiêu đề của form, hiển thị chữ "Đăng nhập" -->
    <?php if (!empty($thongbao)): ?>
        <!-- Nếu biến $thongbao không rỗng (có thông báo lỗi hoặc trạng thái) thì hiển thị -->
        <p style="color:red;"><?php echo $thongbao; ?></p>
        <!-- In ra nội dung thông báo với màu đỏ -->
    <?php endif; ?>
    <label for="email">Email:</label>
    <!-- Nhãn cho ô nhập email -->
    <input type="email" name="email" required>
    <!-- Ô nhập emaila, thuộc tính type="email" giúp trình duyệt kiểm tra định dạng email.
         Thuộc tính required bắt buộc người dùng phải nhập -->
    <label for="password">Mật khẩu:</label>
    <!-- Nhãn cho ô nhập mật khẩu -->
    <input type="password" name="password" required>
    <!-- Ô nhập mật khẩu, type="password" sẽ che ký tự nhập vào.
         Thuộc tính required bắt buộc nhập -->
    <button type="submit">Đăng nhập</button>
    <!-- Nút bấm gửi form, khi nhấn sẽ gửi dữ liệu email và password đến dangnhap.php -->
    <p>Chưa có tài khoản? <a href="/web_bandoan/dangnhap/dangky.php">Đăng ký</a>

    <!-- Dòng chữ gợi ý: nếu chưa có tài khoản thì nhấn vào link để sang trang đăng ký -->
</form>
    <!-- FORM ĐĂNG NHẬP-->
</body>
</html>
</html>