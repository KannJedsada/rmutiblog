<?php

session_start();
include '../security/condb.php';

if (isset($_POST['signup'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $role = 100;
    // if (empty($uname)) {
    //     $_SESSION['error'] = 'แกลืมกรอกชื่อผู้ใช้';
    // } elseif (empty($email)) {
    //     $_SESSION['error'] = 'แกลืมกรอกอีเมลล์';
    // } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     $_SESSION['error'] = 'แกพิมพ์อีเมลล์ไม่ถูก';
    // } elseif (empty($password)) {
    //     $_SESSION['error'] = 'แกลืมกรอกรหัสผ่าน';
    // } elseif (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    //     $_SESSION['error'] = 'แกต้องกรอกรหัสผ่านตั้งแต่ 5 - 20 ตัว';
    // } elseif (empty($c_password)) {
    //     $_SESSION['error'] = 'แกลืมยืนยันรหัสผ่าน';
    // } elseif ($password != $c_password) {
    //     $_SESSION['error'] = 'รหัสของแกไม่ตรงกัน';
    // } else {
    try {
        $stmt = $conn->prepare("INSERT INTO users(username, password, email, role_id, dateCreate) VALUES(:username, :password, :email, :role_id, NOW())");
        $stmt->bindParam(":username", $uname);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":role_id", $role);
        $stmt->execute();
        header("location: ./frm_login.php");
    } catch (PDOException $e) {
        echo $e->getMessage();
   
    }
    // }
}
