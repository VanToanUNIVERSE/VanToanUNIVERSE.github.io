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
                <label for="username">Tên đăng nhập: </label>
                <input type="text" placeholder="Nhập tên đăng nhập" id="username" name="username" onkeyup="validateUsername()">
                <span id="username-error"></span>
            </div>
            <div class="input-group">
                <label for="password">Đăng kí: </label>
                <input type="password" placeholder="Nhập mật khẩu" id="password" name="password" onkeyup="validatePassword()">
                <span id="password-error"></span>
            </div> 
            <button type="submit" name="submit" onclick="validateLogin(event);">Đăng nhập</button>
            <span id="login-error" class="submit-error"></span>
            <?php
                    if(isset($_SESSION["error"]))
                    {
                        echo '<span id="not-exist-error" class="submit-error" style="display: block;">'.$_SESSION["error"].'</span>';
                        unset($_SESSION["error"]);
                    }
                ?>
            <a href="signup.php">Chưa có tài khoản?</a>
            <a href="login.php" style="margin-left: 230px">Quên mật khẩu?</a>
        </form>
    </div>
    <script src="js/login-signup.js"></script>
</body>
</html>