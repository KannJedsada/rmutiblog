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

    $query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id ORDER BY posts.date DESC, posts.time DESC";
    $result = $conn->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($sql) {
        $_SESSION['success'] = "Data has been updated successfully";
        // header("location: seepost.php?id=" . $row['post_id']);
        header("location: userindex.php");
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: userindex.php");
    }
}
?>
