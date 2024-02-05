<?php 
require_once "../security/condb.php";

if (isset($_POST['check_username'])) {
    $username = $_POST['check_username'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
}
?>
