<?php
include '../security/condb.php';
session_start();
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
    <!-- Add these lines inside the head section -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="check.js"></script>
    <title>Register</title>
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
                <h3>Register</h3>
            </div>
        </div>
        <form action="register.php" method="POST">
            <!-- From -->
            <div class="card-body">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter username" id="username" required>
                    <span id="username-error" class="error-message"></span>
                </div>
                <div class="form-group">    
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="example@email.com" id="email" required>
                    <span id="email-error" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" id="password1" required>
                    <span id="password-error" class="error-message"></span>
                    <br>
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPasswordInput" name="c_password" placeholder="Confirm Password" id="password2" required>
                    <span id="confirmPassword-error" class="error-message"></span><br>
                    <input type="checkbox" onclick="showPassword()"> Show Password
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn" name="signup" style="background-color: orange;">Register</button>
                <p style="margin-top: 5px; ">เป็นสมาชิกแล้วใช่มะ <a href="./frm_login.php">คลิ๊กที่นี่เลย</a></p>
            </div>
        </form>
        <div style="background-color: orange; text-align: center; height: auto; font-size: 20px;">
            <a href="../index.php"><i class="fas fa-home"></i></a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../script.js"></script>
    <script src="check.js"></script>
</body>

</html>