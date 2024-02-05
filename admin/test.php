<?php 
require_once "../security/condb.php";

if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM users WHERE user_id = :pid');
    $stmt->bindParam(":pid", $pid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo $row["username"];?>
</body>
</html>
