<?php

use function PHPSTORM_META\sql_injection_subst;

require_once '../security/condb.php';

if (isset($_POST['saveimg'])) {
    $userId = $_POST['userid'];
    $profile = $_FILES['avatar'];
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
                if ($profile2 != '' && file_exists('../img/profile/' . $profile2)) {
                    unlink('../img/profile/' . $profile2);
                }
            }
        }
    } else {
        $sql = $conn->prepare("SELECT profile_img FROM users WHERE user_id = :userId");
        $sql->bindParam(":userId", $userId);
        $sql->execute();
        $pro_img = $sql->fetchColumn(); 

        if ($pro_img != NULL) {
            $fileNew = $profile2;
        } else {
            $sql = $conn->prepare("UPDATE users SET profile_img = NULL WHERE user_id = :userId");
            $sql->bindParam(":userId", $userId);
            $sql->execute();
        }
    }

    $sql = $conn->prepare("UPDATE users SET profile_img = :profile WHERE user_id = :userId");
    $sql->bindParam(":profile", $fileNew);
    $sql->bindParam(":userId", $userId);
    $result = $sql->execute();

    if ($result) {
        $_SESSION['success'] = "Data has been updated successfully";
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
    }

    header("location: profileuser.php?id=" . $userId);
    exit;
}

if (isset($_POST['deleteprofile'])) {
    $userId = $_POST['userid'];
    $profile = $_POST['oldimg'];

    $filePath = '../img/profile/' . $profile;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $sql = $conn->prepare("UPDATE users SET profile_img = NULL WHERE user_id = :userId");
            $sql->bindParam(":userId", $userId);

            if ($sql->execute()) {
                header("Location: profileuser.php");
                exit;
            } else {
                echo "Error: Profile image could not be removed from the database.";
            }
        } else {
            echo "Error: Profile image file could not be deleted.";
        }
    } else {
        echo "Error: Profile image file not found.";
    }
}
