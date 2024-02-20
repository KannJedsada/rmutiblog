<?php
require_once "../security/condb.php";

if (isset($_GET['id'])) {
    $uid = $_GET['id'];
    echo $uid;
    $stmt_check = $conn->prepare("SELECT * FROM users 
    LEFT JOIN posts ON users.user_id = posts.users_id 
    LEFT JOIN comments ON users.user_id = comments.users_id 
    WHERE users.user_id = :uid");

    $stmt_check->bindParam(":uid", $uid);
    $stmt_check->execute();
    $existingData = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existingData) {
        $imageProfile = "../img/profile/" . $existingData['profile_img'];
        $imagepostImg = "../img/postImg/" . $existingData['post_img'];
        $imagecommentImg = "../img/commentImg/" . $existingData['comment_img'];

        if (file_exists($imageProfile)) {
            unlink($imageProfile);
        }

        if (file_exists($imagepostImg)) {
            unlink($imagepostImg);
        }

        if (file_exists($imagecommentImg)) {
            unlink($imagecommentImg);
        }

        try {
            $stmt_delete = $conn->prepare("DELETE FROM users WHERE user_id = :uid");
            $stmt_delete->bindParam(":uid", $uid);

            if ($stmt_delete->execute()) {
                echo "User, associated posts, and associated comments deleted successfully!";
                header("location: ./user.php");
                exit();
            } else {
                echo "Error deleting user!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "User not found!";
    }
}
