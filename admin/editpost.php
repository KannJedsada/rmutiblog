<?php
session_start();
require_once '../security/condb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
    exit();
}

if (isset($_POST['editpost'])) {
    $editPostId = $_POST['editPostId'];
    $pTitle = $_POST['post_title'];
    $pdes = $_POST['post_description'];
    $pimg = $_FILES['post_img'];
    $pimg2 = $_POST['img2'];
    $upload = $_FILES['post_img']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = pathinfo($pimg['name'], PATHINFO_EXTENSION); // Get file extension
        $fileActExt = strtolower($extension);
        $fileNew = uniqid() . "." . $fileActExt; // Generate a unique filename
        $filePath = '../img/postImg/' . $fileNew;

        if ($pimg['error'] !== UPLOAD_ERR_OK) {
            die("File upload failed with error code {$pimg['error']}");
        }

        if (in_array($fileActExt, $allow)) {
            if ($pimg['size'] > 0 && $pimg['error'] == 0) {
                // Perform the image upload and SQL update within this block
                move_uploaded_file($pimg['tmp_name'], $filePath);
                $sql = $conn->prepare("UPDATE posts SET post_title = :pTitle, post_description = :pdes, post_img = :pimg WHERE post_id = :editPostId");
                $sql->bindParam(":pTitle", $pTitle);
                $sql->bindParam(":pdes", $pdes);
                $sql->bindParam(":pimg", $fileNew);
                $sql->bindParam(":editPostId", $editPostId);
                $sql->execute();
            }
        }
    } else {
        // No new image uploaded, update only text fields
        $sql1 = $conn->prepare("UPDATE posts SET post_title = :pTitle, post_description = :pdes WHERE post_id = :editPostId");
        $sql1->bindParam(":pTitle", $pTitle);
        $sql1->bindParam(":pdes", $pdes);
        $sql1->bindParam(":editPostId", $editPostId);
        $sql1->execute();
    }

    if ($sql) {
        // Delete the old image file if it exists
        if ($pimg2 && file_exists("../img/postImg/" . $pimg2)) {
            unlink("../img/postImg/" . $pimg2);
        }
    }
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "profile.php") !== false) {
        header("location: profile.php");
        exit();
    }
    //  else {
    //     header("location: profile.php");
    //     exit();
    // }
}
