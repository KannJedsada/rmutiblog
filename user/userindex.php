<?php

session_start();
require_once '../security/condb.php';

if (!isset($_SESSION['user_login'])) {
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
    <link rel="stylesheet" href="../style.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <title>User Page</title>
</head>

<body>
    <?php
    if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <div class="nav-container">
        <div class="logo"><a href="./userindex.php">RMUTI</a></div>
        <div>
            <form action="search.php" method="get" class="clear-input-container">
                <input class="clear-input" type="text" name="search"> <!-- Corrected name attribute -->
                <button class="clear-input-button" aria-label="Clear input" title="Clear input">×</button>
                <button type="submit" class="clear-search"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">
                <?php if (!empty($row['profile_img'])) { ?>
                    <img src="../img/profile/<?php echo $row['profile_img']; ?>" alt="User Profile">
                <?php } else { ?>
                    <img src="../img/profile/profile-icon-png-910.png" alt="Default Profile Image">
                <?php } ?>
                &nbsp; <?php echo $row['username'] ?></button>
            <div id="myDropdown" class="dropdown-content">
                <a href="./profileuser.php?id=<?php echo $row['user_id']; ?>" name="user">Profile</a>
                <a href="../security/logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="bodypost">
        <div class="apppost">
            <!-- Trigger/Open The Modal -->
            <button id="myBtn" onclick="openModal('addModal')">เพิ่มโพสต์ที่นี่</button>
            <!-- The Modal -->
            <div id="addModal" class="modaladd">
                <!-- Modal content -->
                <div class="modal-contentadd">
                    <span class="close" onclick="closeModal('addModal')">&times;</span>
                    <form action="addpost.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div>
                                <label for="title" style="font-family: Montserrat, sans-serif">Title</label>
                                <input type="text" name="post_title" class="input-group" required>
                            </div>
                            <textarea class="form-control" rows="5" id="comment" name="post_description"></textarea>
                            <div>
                                <label for="postImg" style="font-family: Montserrat, sans-serif">Image</label>
                                <input name="post_img" id="postImg" type="file" onchange="previewImage()" accept="image/*">
                                <img id="previewImg" src="#" alt="Preview" style="max-width: 30%; display: none; margin: auto;">
                            </div>
                        </div>
                        <button type="submit" name="addpost" class="btn">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    // ดึงข้อมูลโพสต์จากตาราง posts
    $query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id ORDER BY posts.date DESC, posts.time DESC";
    $result = $conn->query($query);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <div class="card-container">
            <div class="card-topic">
                <div><a href="./seepost.php?id=<?php echo $row['post_id']; ?>"><?php echo $row['post_title']; ?></a></div>
                <?php if ($row['users_id'] == $_SESSION['user_login']) {  ?>
                    <div>
                        <button class="btn btn-warning" onclick="openModal('editModal_<?php echo $row['post_id']; ?>')">Edit</button>
                        <div id="editModal_<?php echo $row['post_id']; ?>" class="modaladd">
                            <!-- Modal content -->
                            <div class="modal-contentadd">
                                <span class="close" onclick="closeModal('editModal_<?php echo $row['post_id']; ?>')">&times;</span>
                                <?php
                                $editPostId = $row['post_id'];
                                $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = :editPostId");
                                $stmt->bindParam(":editPostId", $editPostId);
                                $stmt->execute();
                                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <form action="editpost.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="editPostId" value="<?php echo $data['post_id']; ?>">
                                    <input type="hidden" value="<?php echo $data['post_img']; ?>" required class="form-control" name="img2">
                                    <div class="form-group">
                                        <div>
                                            <label for="title" style="font-family: Montserrat, sans-serif">Title</label>
                                            <input type="text" name="post_title" class="input-group" value="<?php echo $data['post_title']; ?>" required>
                                        </div>
                                        <textarea class="form-control" rows="5" id="comment" name="post_description"><?php echo $data['post_description']; ?></textarea>
                                        <div>
                                            <label for="postImg" style="font-family: Montserrat, sans-serif">Image</label>
                                            <input name="post_img" id="post_img" type="file" onchange="previewFile()">
                                            <?php if (!empty($row['post_img'])) : ?>
                                                <img id="preview" src="../img/postImg/<?php echo $row['post_img']; ?>" alt="Post Image" style="max-width: 30%; margin: auto;">
                                            <?php else : ?>
                                                <img id="preview" src="#" alt="Post Image Preview" style="max-width: 30%; display: none; margin: auto;">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <button type="submit" name="editpost" class="btn" style="background-color: orange; color: white; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='darkorange'; this.style.color='white'" onmouseout="this.style.backgroundColor='orange'; this.style.color='white'">
                                        บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                        <form action="deletepost.php" method="POST" style="display: inline;">
                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('จะลบจริงป่าว?');">Delete</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
            <div class="card-name">
                <div><?php echo $row['username']; ?></div>
                <div><?php echo date('d/m/Y', strtotime($row['date'])) . ', ' . date('H:i', strtotime($row['time'])); ?></div>
            </div>
            <div class="description">
                <p><?php echo $row['post_description']; ?></p>
            </div>
            <div class="card-img">
                <?php if (!empty($row["post_img"])) : ?>
                    <img src="../img/postImg/<?php echo $row["post_img"]; ?>" alt="Image <?php echo $row['post_id']; ?>">
                <?php endif; ?>
            </div>
            <div><a href="seepost.php?id=<?php echo $row['post_id']; ?>" class="btn">comment</a></div>
        </div>
    <?php
    }
    ?>


    <script src="../script.js"></script>
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../assets/dist/js/demo.js"></script>
    <script>
        function previewFile() {
            var preview = document.getElementById('preview');
            var fileInput = document.getElementById('post_img');
            var file = fileInput.files[0];

            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
</body>

</html>