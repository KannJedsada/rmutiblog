<?php

session_start();
require_once '../security/condb.php';

if (isset($_POST['commentin'])) {
    $cid = $_POST['commentin'];

    // echo $cid;
    $delete_query = "DELETE FROM comments WHERE comment_id = :cid";
    $delete_statement = $conn->prepare($delete_query);
    $delete_statement->bindParam(':cid', $cid);
    $delete_statement->execute();
}
$query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id ORDER BY posts.date DESC, posts.time DESC";
$result = $conn->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($delete_query) {
    $_SESSION['success'] = "Data has been updated successfully";
    header("location: seepost.php?id=" . $row['post_id']);
} else {
    $_SESSION['error'] = "Data has not been updated successfully";
    // header("location: userindex.php");
}
