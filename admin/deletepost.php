<?php
session_start();
require_once '../security/condb.php';

if (isset($_POST['post_id'])) {
    // Cast post_id to an integer for security
    $post_id = $_POST['post_id'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        $fetch_image_query = "SELECT p.post_img FROM posts p WHERE p.post_id = :post_id";
        $fetch_image_statement = $conn->prepare($fetch_image_query);
        $fetch_image_statement->bindParam(':post_id', $post_id);
        $fetch_image_statement->execute();
        $image_filename = $fetch_image_statement->fetchColumn();

        // // Delete comments associated with the specific post
        $fetch_comment_images_query = "SELECT comment_img FROM comments WHERE comments.post_id = :post_id";
        $fetch_comment_images_statement = $conn->prepare($fetch_comment_images_query);
        $fetch_comment_images_statement->bindParam(':post_id', $post_id);
        $fetch_comment_images_statement->execute();
        $comment_images = $fetch_comment_images_statement->fetchAll(PDO::FETCH_COLUMN);
        foreach ($comment_images as $comment_image) {
            if ($comment_image && file_exists("../img/commentImg/" . $comment_image)) {
                unlink("../img/commentImg/" . $comment_image);
            }
        }

        // Delete the image file record from the database
        $delete_image_query = "UPDATE posts SET post_img = NULL WHERE post_id = :post_id";
        $delete_image_statement = $conn->prepare($delete_image_query);
        $delete_image_statement->bindParam(':post_id', $post_id);
        $delete_image_statement->execute();

        // Delete the post
        $delete_post_query = "DELETE FROM posts WHERE post_id = :post_id";
        $delete_post_statement = $conn->prepare($delete_post_query);
        $delete_post_statement->bindParam(':post_id', $post_id);
        $delete_post_statement->execute();

        // Commit the transaction
        $conn->commit();

        // Delete the post image file if it exists
        if ($image_filename && file_exists("../img/postImg/" . $image_filename)) {
            unlink("../img/postImg/" . $image_filename);
        }

        // Redirect based on the referring page
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "profile.php") !== false) {
            header("location: profile.php");
            exit();
        } else {
            header("location: post.php");
            exit();
        }
    } catch (PDOException $e) {
        // An error occurred, rollback the transaction
        $conn->rollBack();
        // Handle the error, you can log it or display a user-friendly message
        echo 'Error: ' . $e->getMessage();
    }
} else {
    ob_start();
    echo 'Invalid request';
    ob_end_flush();
}
