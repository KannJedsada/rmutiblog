<?php

session_start();
require_once '../security/condb.php';

if (!isset($_SESSION['user_login'])) {
    // echo $_SESSION['user_login'];
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
}
if (isset($_SESSION['user_login'])) {
    $user_id = $_SESSION['user_login'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>