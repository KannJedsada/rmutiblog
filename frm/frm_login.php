<?php session_start(); ?>

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

    <title>Login</title>
    <style>
        .card-header {
            display: flex;
            background-color: orange;
            justify-content: center;
        }
    </style>
</head>

<body class="loginfrm">

    <div class="card">
        <div class="card-header">
            <div>
                <h3>Login</h3>
            </div>
        </div>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php
                if (is_array($_SESSION['error'])) {
                    foreach ($_SESSION['error'] as $error) {
                        echo $error;
                    }
                } else {
                    echo $_SESSION['error'];
                }
                unset($_SESSION['error']);
                ?>
            </div>
        <?php }  ?>
        <form action="login.php" method="POST">

            <div class="card-body">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter username" id="username">
                    <span id="error-message" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Password" id="password">
                    <span id="error-message" class="error-message"></span>
                    <br>
                    <input type="checkbox" onclick="showPassword()"> Show Password
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn" name="signin" style="background-color: orange;" id="submit">Login</button> <br>
                ยังไม่เป็นสมาชิกใช่มะ <a href="./frm_register.php">คลิ๊กที่นี่เลย</a>
            </div>
        </form>
        <div style="background-color: orange; text-align: center; height: auto; font-size: 20px;">
            <a href="../index.php"><i class="fas fa-home"></i></a>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../script.js"></script>

</body>

</html>