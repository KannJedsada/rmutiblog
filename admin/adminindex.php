<?php

session_start();
require_once '../security/condb.php';
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style1.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <?php

    if (isset($_SESSION['admin_login'])) {
        $admin_id = $_SESSION['admin_login'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :admin_id");
        $stmt->bindParam(":admin_id", $admin_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $stmtCount = $conn->prepare("SELECT COUNT(*) AS user_count FROM users  WHERE role_id = 100");
    $stmtCount->execute();
    $userCount = $stmtCount->fetch(PDO::FETCH_ASSOC)['user_count'];

    $stmtDailyCount = $conn->prepare("SELECT COUNT(*) AS daily_count FROM users WHERE DATE(dateCreate) = CURDATE()");
    $stmtDailyCount->execute();
    $dailyCount = $stmtDailyCount->fetch(PDO::FETCH_ASSOC)['daily_count'];

    $stmtAdminCount = $conn->prepare("SELECT COUNT(*) AS admin_count FROM users WHERE role_id = 900");
    $stmtAdminCount->execute();
    $adminCount = $stmtAdminCount->fetch(PDO::FETCH_ASSOC)['admin_count'];

    $stmtPostCount = $conn->prepare("SELECT COUNT(*) AS post_count FROM posts");
    $stmtPostCount->execute();
    $postCount = $stmtPostCount->fetch(PDO::FETCH_ASSOC)['post_count'];

    ?>
    <div class="nav">
        <h1>Dashboard</h1>
    </div>

    <div class="col">
        <div class="box-r">
            <div class="col-a">
                <a href="profile.php?id=<?php echo $row["user_id"] ?>">Profile</a>
            </div>
            <div class="col-a">
                <a href="../security/logout.php">Logout</a>
            </div>
        </div>
        <div class="box">
            <div class="box-a">
                <a href="admin.php"><i class="ri-user-line"></i>Admain : <?php echo $adminCount; ?></a>
            </div>
            <div class="box-a">
                <a href="user.php"><i class="ri-group-line"></i>Users : <?php echo $userCount; ?></a>
            </div>
            <div class="box-a">
                <a href="regis.php"><i class="ri-folder-open-line"></i>Registration : <?php echo $dailyCount; ?></a>
            </div>
            <div class="box-a">
                <a href="post.php"><i class="ri-timer-line"></i>Post of : <?php echo $postCount; ?></a>
            </div>
        </div>

</body>

</html>