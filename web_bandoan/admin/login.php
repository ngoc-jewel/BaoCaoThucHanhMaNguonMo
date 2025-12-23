<?php
session_start();
require_once("../includes/database.php");

// ✅ Tạo tài khoản admin mặc định nếu chưa có
$check_stmt = $conn->query("SELECT id FROM adminuser LIMIT 1");
if ($check_stmt->rowCount() === 0) {
    $default_user = 'admin';
    $default_pass_hashed = password_hash('admin123', PASSWORD_DEFAULT);
    $default_role = 'admin';
    $insert_stmt = $conn->prepare("INSERT INTO adminuser (username, password, role) VALUES (?, ?, ?)");
    $insert_stmt->execute([$default_user, $default_pass_hashed, $default_role]);
}

$error_message = '';

// ✅ Xử lý form đăng nhập
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM adminuser WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_role'] = $admin['role'];
        header("Location: index.php"); // ✅ chuyển vào admin/index.php
        exit();
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 12px; background-color: #ff914d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #e65c00; }
        .error { color: red; text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập Admin</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Đăng nhập</button>
            <?php if ($error_message): ?>
                <p class="error"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
