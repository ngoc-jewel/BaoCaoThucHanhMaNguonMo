<?php
session_start();
include("../../includes/database.php");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM giohang WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
}

header("Location: giohang.php");
exit();
