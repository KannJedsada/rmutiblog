<?php

session_start();
require_once '../security/condb.php';

if (isset($_POST['commentin'])) {
    $cid = $_POST['commentin'];
    $pid = $_POST['postid']; // Add missing semicolon here

    try {
        // Fetch the image filename associated with the comment
        $fetch_image_query = "SELECT comment_img FROM comments WHERE comment_id = :cid";
        $fetch_image_statement = $conn->prepare($fetch_image_query);
        $fetch_image_statement->bindParam(':cid', $cid);
        $fetch_image_statement->execute();
        $image_filename = $fetch_image_statement->fetchColumn();

        // Delete the comment from the database
        $delete_query = "DELETE FROM comments WHERE comment_id = :cid";
        $delete_statement = $conn->prepare($delete_query);
        $delete_statement->bindParam(':cid', $cid);
        $delete_statement->execute();

        // Delete the associated image file
        if ($image_filename && file_exists("../img/commentImg/" . $image_filename)) {
            unlink("../img/commentImg/" . $image_filename);
        }

        // Redirect to the appropriate page after successful deletion
        $_SESSION['success'] = "Comment and associated image deleted successfully";
        header("location: seepost.php?id=" . $pid);
        exit();
    } catch (PDOException $e) {
        // Handle any errors that occur during the deletion process
        $_SESSION['error'] = "Error deleting comment: " . $e->getMessage();
        header("location: userindex.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request";
    header("location: userindex.php");
    exit();
}
?>
