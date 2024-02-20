<?php

session_start();
require_once '../security/condb.php';

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
    exit();
}

if (isset($_POST['editpost'])) {
    $pid = $_POST['postid'];
    $editPostId = $_POST['editPostId'];
    $pTitle = $_POST['post_title'];
    $pdes = $_POST['post_description'];
    $pimg = $_FILES['post_img'];

    $pimg2 = $_POST['img2'];
    $upload = $_FILES['post_img']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode('.', $pimg['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = '../img/postImg/' . $fileNew;

        if ($pimg['error'] !== UPLOAD_ERR_OK) {
            die("File upload failed with error code {$pimg['error']}");
        }

        if (in_array($fileActExt, $allow)) {
            if ($pimg['size'] > 0 && $pimg['error'] == 0) {
                move_uploaded_file($pimg['tmp_name'], $filePath);
            }
        }
    } else {
        $fileNew = $pimg2;
    }

    $sql = $conn->prepare("UPDATE posts SET post_title = :pTitle, post_description = :pdes, post_img = :pimg WHERE post_id = :editPostId");
    $sql->bindParam(":pTitle", $pTitle);
    $sql->bindParam(":pdes", $pdes);
    $sql->bindParam(":pimg", $fileNew);
    $sql->bindParam(":editPostId", $editPostId);
    $sql->execute();

    if ($sql) {
        // Delete the old image file if it exists
        if ($pimg2 && file_exists("../img/postImg/" . $pimg2)) {
            unlink("../img/postImg/" . $pimg2);
        }

        // Redirect based on the referring page
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "profileuser.php") !== false) {
            header("location: profileuser.php");
            exit();
        } else {
            header("location: userindex.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: userindex.php");
    }
}
