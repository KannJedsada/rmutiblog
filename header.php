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

    <title>RmutiBlog</title>
</head>

<body>
    <div class="nav-container">
        <div class="logo"><a href="./index.php">RMUTI</a></div>
        <div>
            <form action="search.php" method="get" class="clear-input-container">
                <input class="clear-input" type="text" name="search"> <!-- Corrected name attribute -->
                <button class="clear-input-button" aria-label="Clear input" title="Clear input">×</button>
                <button type="submit" class="clear-search"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="menu"><a href="./frm/frm_login.php" type="button">Login</a></div>
    </div>