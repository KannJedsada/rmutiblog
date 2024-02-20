<?php

session_start();
include '../security/condb.php';

if (isset($_POST['signin'])) {
    $uname = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $uname, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($uname)) {
        $_SESSION['error'] = 'แกลืมกรอกชื่อผู้ใช้';
        header("location: ./frm_login.php");
    } elseif (empty($password)) {
        $_SESSION['error'] = 'แกลืมกรอกรหัสผ่าน';
        header("location: ./frm_login.php");
    } else {
        try {
            $check_data = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $check_data->bindParam(":username", $uname);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($check_data->rowCount() > 0) {
                if ($uname == $row['username']) {
                    if ($password == $row['password']) {
                        if ($row['role_id'] == 900 or $row['role_id'] == 999) {
                            $_SESSION['admin_login'] = $row['user_id'];
                            header('location: ../admin/adminindex.php');
                        } else {
                            $_SESSION['user_login'] = $row['user_id'];
                            header('location: ../user/userindex.php');
                        }
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านของแกไม่ถูกต้อง';
                        header('location: frm_login.php');
                    }
                } else {
                    $_SESSION['error'] = 'ไม่มีชื่อผู้ใช้นี้';
                    header('location: frm_login.php');
                }
            } else {
                $_SESSION['error'] = 'ไม่มีแกในระบบ';
                header('location:./frm_login.php');
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
