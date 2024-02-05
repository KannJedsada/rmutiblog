<?php

session_start();
require_once '../security/condb.php';

if (isset($_POST['comment'])) {
    $comment_in = $_POST['comment_in'];
    $cby = $_SESSION['user_login'];
    $cdes = $_POST['comment_desc'];
    $cimg = $_FILES['comment_img'];

    if (!empty($cimg['name'])) {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode(".", $cimg['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "../img/commentImg/" . $fileNew;

        if (in_array($fileActExt, $allow) && $cimg['size'] > 0 && $cimg['error'] == 0) {
            if (move_uploaded_file($cimg['tmp_name'], $filePath)) {
                $sql = $conn->prepare("INSERT INTO comments(post_id, users_id, comment_img, comment_desc, date, time) VALUES(:comment_in, :cby, :cimg, :cdes, NOW(), NOW())");
                $sql->bindParam(':comment_in', $comment_in, PDO::PARAM_STR);
                $sql->bindParam(':cby', $cby, PDO::PARAM_STR);
                $sql->bindParam(':cimg', $fileNew, PDO::PARAM_STR);
                $sql->bindParam(':cdes', $cdes, PDO::PARAM_STR);
                $sql->execute();
            }
        }
    } else {
        // If no image file is uploaded
        $sql = $conn->prepare("INSERT INTO comments(post_id, users_id, comment_desc, date, time) VALUES(:comment_in, :cby, :cdes, NOW(), NOW())");
        $sql->bindParam(':comment_in', $comment_in, PDO::PARAM_STR);
        $sql->bindParam(':cby', $cby, PDO::PARAM_STR);
        $sql->bindParam(':cdes', $cdes, PDO::PARAM_STR);
        $sql->execute();
    }

    if ($sql) {
        header('location: seepost.php?id=' . $comment_in);
        exit();
    }
}
