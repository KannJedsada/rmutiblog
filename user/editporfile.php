<?php
session_start();
require_once '../security/condb.php';

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
    exit();
}

if (isset($_POST['editprofile'])) {
    $userId = $_POST['editprofileId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    // Handle profile image upload
    $profile = $_FILES['profile_img'];
    $profile2 = $_POST['oldimg'];
    $upload = $profile['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode('.', $profile['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = '../img/profile/' . $fileNew;

        if ($profile['error'] !== UPLOAD_ERR_OK) {
            die("File upload failed with error code {$profile['error']}");
        }

        if (in_array($fileActExt, $allow)) {
            if ($profile['size'] > 0 && $profile['error'] == 0) {
                move_uploaded_file($profile['tmp_name'], $filePath);

                // Remove old profile image
                if ($profile2 != '' && file_exists('../img/profile/' . $profile2)) {
                    unlink('../img/profile/' . $profile2);
                }
            }
        }
    } else {
        $fileNew = $profile2;
    }

    // Check if the user is updating their own profile
    if ($_SESSION['user_login'] == $userId) {
        // Check if the new username already exists
        $checkUsernameQuery = $conn->prepare("SELECT user_id FROM users WHERE username = :newUsername AND user_id != :userId");
        $checkUsernameQuery->bindParam(":newUsername", $username);
        $checkUsernameQuery->bindParam(":userId", $userId);
        $checkUsernameQuery->execute();

        if ($checkUsernameQuery->rowCount() > 0) {
            // The new username already exists, show an alert
            echo "alert('Username already exists. Please choose a different username.');";
        } else {
            // Update with username and profile image
            $sql = $conn->prepare("UPDATE users SET username = :username, email = :email, password = :password, profile_img = :profile WHERE user_id = :userId");
            $sql->bindParam(":username", $username);
            $sql->bindParam(":profile", $fileNew);
            $sql->bindParam(":email", $email);
            $sql->bindParam(":password", $password);
            $sql->bindParam(":userId", $userId);
            $sql->execute();
        }
    } else {
        echo "You don't have permission to update this profile.";
        exit;
    }

    $query = "SELECT * FROM users WHERE user_id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":userId", $userId);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($sql) && $sql) {
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: profileuser.php?id=" . $row['user_id']);
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location:  profileuser.php?id=" . $row['user_id']);
    }
}
?>
