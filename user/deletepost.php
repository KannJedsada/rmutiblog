<?php
session_start();
require_once '../security/condb.php';

if (isset($_POST['post_id'])) {
    // Cast post_id to an integer for security
    $post_id = $_POST['post_id'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Delete comments associated with the specific post
        $delete_comments_query = "DELETE FROM comments WHERE post_id = :post_id";
        $delete_comments_statement = $conn->prepare($delete_comments_query);
        $delete_comments_statement->bindParam(':post_id', $post_id);
        $delete_comments_statement->execute();

        // Delete the post
        $delete_post_query = "DELETE FROM posts WHERE post_id = :post_id";
        $delete_post_statement = $conn->prepare($delete_post_query);
        $delete_post_statement->bindParam(':post_id', $post_id);
        $delete_post_statement->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect based on the referring page
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "profileuser.php") !== false) {
            header("location: profileuser.php");
            exit();
        } else {
            header("location: userindex.php");
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

