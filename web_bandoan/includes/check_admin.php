<?php
// Khởi tạo session
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) 
{
    // Chuyển hướng người dùng về trang đăng nhập admin
    header('Location: /web_bandoan/admin/login.php');
    exit();
}

function has_role($role) {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === $role;
}

function is_admin() {
    return has_role('admin');
}
?>
