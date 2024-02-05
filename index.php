<?php include './header.php'; 
    require_once './security/condb.php';
?>

<link rel="stylesheet" href="style.css">

<?php
$query = "SELECT * FROM posts INNER JOIN users ON posts.users_id = users.user_id ORDER BY posts.date DESC, posts.time DESC";
$result = $conn->query($query);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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