<?php 
include 'header.php';
session_start();
require_once './security/condb.php';

if (isset($_GET['search'])) {
    $keywords = '%' . $_GET['search'] . '%';
    
    $sql = "SELECT posts.*, users.username, users.profile_img
        FROM posts 
        INNER JOIN users ON posts.users_id = users.user_id
        WHERE posts.post_title LIKE :keywords OR posts.post_description LIKE :keywords
        ORDER BY posts.date DESC, posts.time DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        echo '<div style="text-align: center; margin-top: 10%; font-weight: bold; font-size: 50px;">ไม่มี Post ที่เกี่ยวข้อง</div>';
        die($stmt->errorInfo()[2]);
    }
    
} else {
  
    $sql = "SELECT posts.*, users.username, users.profile_img
            FROM posts 
            INNER JOIN users ON posts.post_by = users.user_id
            ORDER BY posts.date DESC, posts.time DESC";

    $stmt = $conn->query($sql);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        die("Query failed: " . $stmt->errorInfo()[2]);
    }
} 

foreach ($result as $row) {
?>
    <div class="card-container" style="padding-bottom: 20px;">
        <div class="card-topic">
            <div><a href="seepost.php?id=<?php echo $row['post_id'];?>"><?php echo $row['post_title']; ?></a></div>
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
                <img src="./img/postImg/<?php echo $row["post_img"]; ?>" alt="Image <?php echo $row['post_id']; ?>">
            <?php endif; ?>
        </div>
    </div>
<?php
}
?>

<?php include './footer.php'; ?>
