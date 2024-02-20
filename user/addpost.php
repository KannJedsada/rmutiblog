<?php
session_start();
include '../security/condb.php';

if (isset($_POST['addpost'])) {
    $pby = $_SESSION['user_login'];
    $ptitle = $_POST['post_title'];
    $pdes = $_POST['post_description'];
    $pimg = $_FILES['post_img'];

    // ตรวจสอบว่ามีไฟล์ภาพถูกอัปโหลดหรือไม่
    if (!empty($pimg['name'])) {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode(".", $pimg['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "../img/postImg/" . $fileNew;

        // ตรวจสอบนามสกุลของไฟล์ภาพ
        if (in_array($fileActExt, $allow) && $pimg['size'] > 0 && $pimg['error'] == 0) {
            // ถ้าเป็นไฟล์ภาพที่ถูกต้อง และอัปโหลดไฟล์ได้สำเร็จ
            if (move_uploaded_file($pimg['tmp_name'], $filePath)) {
                // บันทึกโพสต์พร้อมกับข้อมูลรูปภาพ
                $sql = $conn->prepare("INSERT INTO posts(users_id, post_title, post_description, post_img, date, time) VALUES(:pby, :ptitle, :pdes, :pimg, NOW(), NOW())");
                $sql->bindParam(':pby', $pby, PDO::PARAM_STR);
                $sql->bindParam(':ptitle', $ptitle, PDO::PARAM_STR);
                $sql->bindParam(':pdes', $pdes, PDO::PARAM_STR);
                $sql->bindParam(':pimg', $fileNew, PDO::PARAM_STR);
                $sql->execute();
            }
        }
    } else {
        // ถ้าไม่มีไฟล์ภาพถูกอัปโหลด
        $sql = $conn->prepare("INSERT INTO posts(users_id, post_title, post_description, date, time) VALUES(:pby, :ptitle, :pdes, NOW(), NOW())");
        $sql->bindParam(':pby', $pby, PDO::PARAM_STR);
        $sql->bindParam(':ptitle', $ptitle, PDO::PARAM_STR);
        $sql->bindParam(':pdes', $pdes, PDO::PARAM_STR);
        $sql->execute();
    }

    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "profileuser.php") !== false) {
        header("location: ./profileuser.php");
        exit();
    } else {
        header("location: ./userindex.php");
        exit();
    }
}
