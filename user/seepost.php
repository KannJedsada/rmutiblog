<?php
session_start();
require_once '../security/condb.php';

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
    <link rel="stylesheet" href="../style.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <title><?php echo htmlspecialchars($row['post_title']); ?></title>
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
        <div class="clear-input-container">
            <input class="clear-input" type="text">
            <button class="clear-input-button" aria-label="Clear input" title="Clear input">×</button>
            <a href=""><i class="fas fa-search"></i></a>
        </div>
        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">
                <?php if (!empty($row['user_profile'])) { ?>
                    <img src="<?php echo $row['user_profile']; ?>" alt="User Profile">
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
    <div class="seepost">
        <div class="post">
            <div>
                <?php
                $query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id WHERE posts.post_id = :pid";
                $stmtp = $conn->prepare($query);
                $stmtp->bindParam(":pid", $pid);
                $stmtp->execute();

                $rowpost = $stmtp->fetch(PDO::FETCH_ASSOC); ?>
                <div><?php echo $rowpost['post_title']; ?>
                    <?php if ($rowpost['users_id'] == $_SESSION['user_login']) {  ?>
                        <div>
                            <button class="btn btn-warning" style="color: white;" onclick="openModal('editModal_<?php echo $rowpost['post_id']; ?>')">Edit</button>
                            <div id="editModal_<?php echo $rowpost['post_id']; ?>" class="modaladd">
                                <!-- Modal content -->
                                <div class="modal-contentadd">
                                    <span class="close" onclick="closeModal('editModal_<?php echo $rowpost['post_id']; ?>')">&times;</span>
                                    <?php
                                    $editPostId = $rowpost['post_id'];
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
                                                <?php if (!empty($rowpost['post_img'])) : ?>
                                                    <img id="preview" src="../img/postImg/<?php echo $rowpost['post_img']; ?>" alt="Post Image" style="max-width: 30%; margin: auto;">
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
                                <input type="hidden" name="post_id" value="<?php echo $rowpost['post_id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('จะลบจริงป่าว?');">Delete</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <div class="card-name">
                    <div><?php echo $rowpost['username']; ?></div>
                    <div><?php echo date('d/m/Y', strtotime($rowpost['date'])) . ', ' . date('H:i', strtotime($rowpost['time'])); ?></div>
                </div>
                <div class="description">
                    <p><?php echo $rowpost['post_description']; ?></p>
                </div>
                <div class="card-img">
                    <?php if (!empty($rowpost["post_img"])) : ?>
                        <img src="../img/postImg/<?php echo $rowpost["post_img"]; ?>" alt="Image <?php echo $rowpost['post_id']; ?>">
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div>
            <div id="comment-section">
                <form action="comment.php" method="POST" enctype="multipart/form-data">
                    <div class="insertcomment">
                        <input type="hidden" name="comment_in" value="<?php echo $rowpost['post_id']; ?>">
                        <textarea name="comment_desc" id="" cols="50" rows="1"></textarea>
                        <input name="comment_img" id="postImg" type="file" onchange="previewImage()" accept="image/*">
                        <img id="previewImg" src="#" alt="Preview" style="max-width: 30%; display: none; margin: auto;">
                        <button type="submit" name='comment'><i class="fas fa-caret-right"></i></button>
                    </div>
                </form>
                <?php
                $query = "SELECT comments.* , users.username
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
                            <div>
                                <?php echo $rowcomment['username']; ?>
                                <div><?php echo date('d/m/Y', strtotime($rowcomment['date'])) . ', ' . date('H:i', strtotime($rowcomment['time'])); ?></div>
                            </div>
                            <div>
                                <?php if ($rowcomment['users_id'] == $_SESSION['user_login']) {  ?>
                                    <div>
                                        <button class="btn btn-warning" style="color: white;" onclick="openModal('editModal_<?php echo $rowcomment['comment_id']; ?>')">Edit</button>
                                        <div id="editModal_<?php echo $rowcomment['comment_id']; ?>" class="modaladd">
                                            <!-- Modal content -->
                                            <div class="modal-contentadd">
                                                <span class="close" onclick="closeModal('editModal_<?php echo $rowcomment['comment_id']; ?>')">&times;</span>
                                                <?php
                                                $editcomment = $rowcomment['comment_id'];
                                                $stmt = $conn->prepare("SELECT * FROM comments WHERE comment_id = :editcomment");
                                                $stmt->bindParam(":editcomment", $editcomment);
                                                $stmt->execute();
                                                $datac = $stmt->fetch(PDO::FETCH_ASSOC);
                                                ?>
                                                <form action="editcomment.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name='postid' value="<?php echo $rowpost['post_id']; ?>">
                                                    <input type="hidden" value="<?php echo $datac['comment_img']; ?>" required class="form-control" name="img3">
                                                    <input type="hidden" name="editcommentID" value="<?php echo $datac['comment_id']; ?>">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="5" id="comment" name="comment_desc"><?php echo $datac['comment_desc']; ?></textarea>
                                                        <div>
                                                            <label for="postImg" style="font-family: Montserrat, sans-serif">Image</label>
                                                            <input name="comment_img" id="post_img" type="file" onchange="previewFile()">
                                                            <?php if (!empty($rowcomment['comment_img'])) : ?>
                                                                <img id="preview" src="../img/commentImg/<?php echo $rowcomment['comment_img']; ?>" alt="Post Image" style="max-width: 30%; margin: auto;">
                                                            <?php else : ?>
                                                                <img id="preview" src="#" alt="Post Image Preview" style="max-width: 30%; display: none; margin: auto;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <button type="submit" name="editcomment" class="btn" style="background-color: orange; color: white; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='darkorange'; this.style.color='white'" onmouseout="this.style.backgroundColor='orange'; this.style.color='white'">
                                                        บันทึก
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="deletecomment.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="commentin" value="<?php echo $rowcomment['comment_id']; ?>">
                                            <button type="submit" class="btn btn-danger" name="detelecomment" onclick="return confirm('จะลบจริงป่าว?');">Delete</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div><?php echo $rowcomment['comment_desc'] ?></div>
                        <?php if (!empty($rowcomment["comment_img"])) : ?>
                            <img src="../img/commentImg/<?php echo $rowcomment["comment_img"]; ?>" alt="Image <?php echo $rowcomment['comment_id']; ?>">
                        <?php endif; ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="../script.js"></script>
        <script>
            function previewFile() {
                var preview = document.getElementById('preview');
                var fileInput = document.getElementById('post_img');
                var file = fileInput.files[0];

                if (file) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }

                    reader.readAsDataURL(file);
                }
            }
        </script>
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
</body>

</html>