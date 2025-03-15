<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/login-signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <form action="includes/login.php" method="POST">
            <i class="fa-solid fa-paper-plane"></i>
            <div class="input-group">
                <label for="username">User name: </label>
                <input type="text" placeholder="Enter your user name" id="username" name="username" onkeyup="validateUsername()">
                <span id="username-error"></span>
            </div>
            <div class="input-group">
                <label for="password">Password: </label>
                <input type="password" placeholder="Enter your password" id="password" name="password" onkeyup="validatePassword()">
                <span id="password-error"></span>
            </div> 
            <button type="submit" name="submit" onclick="validateLogin(event);">Login</button>
            <span id="login-error" class="submit-error"></span>
            <?php
                    if(isset($_SESSION["error"]))
                    {
                        echo '<span id="not-exist-error" class="submit-error" style="display: block;">'.$_SESSION["error"].'</span>';
                    }
                ?>
            <a href="signup.php">Don't have account?</a>
        </form>
    </div>
    <script src="js/login-signup.js"></script>
</body>
</html>