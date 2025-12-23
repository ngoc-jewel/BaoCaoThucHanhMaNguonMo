<?php
$host = "localhost";
$dbname = "foodzone";
$username = "root";
$password = "";
try 
{
    // Tạo đối tượng PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Thiết lập chế độ báo lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) 
{
    die( "Kết nối thất bại: " . $e->getMessage());
}
//PDO hỗ trợ nhiều loại CSDL (MySQL, PostgreSQL, SQLite…), nên linh hoạt hơn mysqli
?>
