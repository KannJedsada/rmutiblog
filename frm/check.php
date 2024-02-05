<?php
include '../security/condb.php';

if (isset($_POST['check_username'])) {
    $username = $_POST['check_username'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    echo $stmt->rowCount();
} elseif (isset($_POST['check_email'])) {
    $email = $_POST['check_email'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    echo $stmt->rowCount();
} elseif (isset($_POST['check_password'])) {
    $password = $_POST['check_password'];
    if (strlen($password) < 5 || strlen($password) > 20) {
        echo "error";
    } else {
        echo "success";
    }
}
?>
