<?php
require_once "../security/condb.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่า editprofileId ที่ถูกส่งมาจากฟอร์ม
    $editprofileId = $_POST['editprofileId'];

    // ตรวจสอบว่ามีข้อมูลที่ต้องการแก้ไขหรือไม่
    $stmt_check = $conn->prepare("SELECT * FROM users WHERE user_id = :editprofileId");
    $stmt_check->bindParam(":editprofileId", $editprofileId);
    $stmt_check->execute();
    $existingData = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existingData) {
        // รับค่าจากฟอร์ม
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role_id = $_POST['role_id'];
        $isActive = $_POST['isActive'];

        // อัปเดตข้อมูลในฐานข้อมูล
        $stmt_update = $conn->prepare("UPDATE users SET username = :username, email = :email, role_id = :role_id, isActive = :isActive WHERE user_id = :editprofileId");
        $stmt_update->bindParam(":username", $username);
        $stmt_update->bindParam(":email", $email);
        $stmt_update->bindParam(":role_id", $role_id);
        $stmt_update->bindParam(":isActive", $isActive);
        $stmt_update->bindParam(":editprofileId", $editprofileId);

        if ($stmt_update->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Error updating profile!";
        }
    } else {
        echo "Profile not found!";
    }
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "user.php") !== false) {
        // กลับไปยังหน้า profile.php
        header("location: user.php");
        exit();
    } else {
        // กลับไปยังหน้า userindex.php
        header("location: admin.php");
        exit();
    }
}
?>
