<?php
session_start();
require_once './security/condb.php';

if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM posts WHERE post_id = :pid');
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
    <link rel="stylesheet" href="style.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./assets/dist/css/adminlte.min.css">
    <title><?php echo htmlspecialchars($row['post_title']); ?></title>
</head>

<body>
    <div class="nav-container">
        <div class="logo"><a href="./index.php">RMUTI</a></div>
        <div class="clear-input-container">
            <input class="clear-input" type="text">
            <button class="clear-input-button" aria-label="Clear input" title="Clear input">×</button>
            <a href=""><i class="fas fa-search"></i></a>
        </div>
        <div class="menu"><a href="./frm/frm_login.php" type="button">Login</a></div>
    </div>
    <div class="seepost">
        <div class="post">
            <div>
                <?php
                $query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id WHERE posts.post_id = :pid";
                $stmtp = $conn->prepare($query);
                $stmtp->bindParam(":pid", $pid);
                $stmtp->execute();

                $rowpost = $stmtp->fetch(PDO::FETCH_ASSOC); ?>
                <div><?php echo $rowpost['post_title']; ?></div>
                <div class="card-name">
                    <div><?php echo $rowpost['username']; ?></div>
                    <div><?php echo date('d/m/Y', strtotime($row['date'])) . ', ' . date('H:i', strtotime($row['time'])); ?></div>
                </div>
                <div class="description">
                    <p><?php echo $rowpost['post_description']; ?></p>
                </div>
                <div class="card-img">
                    <?php if (!empty($rowpost["post_img"])) : ?>
                        <img src="./img/postImg/<?php echo $rowpost["post_img"]; ?>" alt="Image <?php echo $rowpost['post_id']; ?>">
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div>
            <div id="comment-section">
                <?php
                $query = "SELECT comments.*, users.username AS comment_by
        FROM posts
        INNER JOIN comments ON posts.post_id = comments.post_id
        INNER JOIN users ON comments.users_id = users.user_id
        WHERE posts.post_id = :pid";
                $stmtc = $conn->prepare($query);
                $stmtc->bindParam(":pid", $pid);
                $stmtc->execute();
                ?>
                <?php while ($rowcomment = $stmtc->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="comment">
                        <div>
                            <div><?php echo $rowcomment['comment_by']; ?></div>
                            <div><?php echo date('d/m/Y', strtotime($rowcomment['date'])) . ', ' . date('H:i', strtotime($rowcomment['time'])); ?></div>
                        </div>
                        <div><?php echo $rowcomment['comment_desc'] ?></div>
                        <?php if (!empty($rowcomment["comment_img"])) : ?>
                            <img src="./img/commentImg/<?php echo $rowcomment["comment_img"]; ?>" alt="Image <?php echo $rowcomment['comment_id']; ?>">
                        <?php endif; ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="script.js"></script>

        <!-- jQuery -->
        <script src="./assets/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="./assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- bs-custom-file-input -->
        <script src="./assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!-- AdminLTE App -->
        <script src="./assets/dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="./assets/dist/js/demo.js"></script>
</body>

</html>