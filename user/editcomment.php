<?php

session_start();
require_once '../security/condb.php';

if (isset($_POST['editcomment'])) {
    $editcid = $_POST['editcommentID'];
    $cdesc = $_POST['comment_desc'];
    $cimg = $_FILES['comment_img']; 

    $cimg2 = $_POST['img3'];
    $upload = $_FILES['comment_img']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode('.', $cimg['name']);  // Correct variable name
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = '../img/commentImg/' . $fileNew;

        if ($cimg['error'] !== UPLOAD_ERR_OK) {
            die("File upload failed with error code {$cimg['error']}");
        }        

        if (in_array($fileActExt, $allow)) {
            if ($cimg['size'] > 0 && $cimg['error'] == 0) {  // Correct variable name
                move_uploaded_file($cimg['tmp_name'], $filePath);  // Correct variable name
            }
        }
    } else {
        $fileNew = $cimg2;
    }

    $sql = $conn->prepare("UPDATE comments SET comment_desc = :cdesc, comment_img = :cimg WHERE comment_id = :editcid");
    $sql->bindParam(":cdesc", $cdesc);  
    $sql->bindParam(":cimg", $fileNew);
    $sql->bindParam(":editcid", $editcid);
    $sql->execute();

    $query = "SELECT * FROM posts INNER JOIN users ON posts.post_by = users.user_id ORDER BY posts.date DESC, posts.time DESC";
    $result = $conn->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($sql) {
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: seepost.php?id=" . $row['post_id']);
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: index.php");
    }
}
?>
