<?php
session_start();
require_once "../security/condb.php";

if (isset($_POST["adduser"])) {
    // echo "text";
    $uname = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, dateCreate) VALUES (:username, :email, :password, NOW())");
    $stmt->bindParam(":username", $uname);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    header("location: ./user.php");
} elseif (isset($_POST["import"])) {
    if ($_FILES["file"]["name"] != '') {
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];

        require '../excelread/vendor/autoload.php';

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');

        try {
            $spreadsheet = $reader->load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();

            $errors = []; // เก็บข้อผิดพลาดที่เกิดขึ้นในแต่ละแถว
            $rowNumber = 1;

            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                $uname = isset($data[0]) ? $data[0] : null;
                $email = isset($data[2]) ? $data[2] : null;
                $password = isset($data[1]) ? $data[1] : null;

                // Check if username or email already exists
                $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
                $stmt_check->bindParam(":username", $uname);
                $stmt_check->bindParam(":email", $email);
                $stmt_check->execute();
                $count = $stmt_check->fetchColumn();

                if ($count == 0) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Insert data into database
                        $stmt = $conn->prepare("INSERT INTO users (username, password, email, dateCreate) VALUES (:username, :password, :email, NOW())");
                        $stmt->bindParam(":username", $uname);
                        $stmt->bindParam(":password", $password);
                        $stmt->bindParam(":email", $email);
                        $stmt->execute();
                    } else {
                        // Invalid email format
                        $errors[] = "Invalid email format for user $uname with email $email in row number $rowNumber";
                    }
                } else {
                    // Duplicate entry for username or email
                    $errors[] = "Duplicate entry for user $uname with email $email in row number $rowNumber";
                }
                $rowNumber++;
            }

            if (!empty($errors)) {
                // เกิดข้อผิดพลาดในการนำเข้าข้อมูล
                $_SESSION['error'] = $errors;
                header("Location: ./user.php");
            } else {
                // ไม่มีข้อผิดพลาด
                $_SESSION['success'] = "upload success";
                header("Location: ./user.php");
            }
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            echo "Error reading the file: " . $e->getMessage();
        }
    } else {
        echo "Please select a file to upload";
    }
}
