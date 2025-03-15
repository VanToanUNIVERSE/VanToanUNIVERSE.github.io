<?php
    session_start();
    require "includes/database.php";
    if(isset($_POST["submit"]))
    {
        $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : "";
        $password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : "";
        $confirmPassword = isset($_POST["confirmPassword"]) ? htmlspecialchars($_POST["confirmPassword"]) : "";
        $sql0 = "select username from users";
        $statement = $connection->prepare($sql0);
        $statement->execute();
        $usernamesData = $statement->fetchAll();
        $usernameList = array_column($usernamesData, "username");
        if(in_array($username, $usernameList))
        {
            $_SESSION["error"] = "Username da ton tai";
            header("Location: signup.php");
            exit();
        }
        else
        {
            $sql = "insert into users(username, password) values (?, ?)";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $username);
            $statement->bindParam(2, $password);
            $statement->execute();
            $_SESSION["success"] = "Tạo tài khoản thành công";
            header("Location: signup.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="css/login-signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <i class="fa-solid fa-paper-plane"></i>
            <div class="input-group">
                <label for="username">User name: </label>
                <input name="username" type="text" placeholder="Enter your user name" id="username" onkeyup="validateUsername()">
                <span id="username-error"><?php echo isset($usernameError) ? $usernameError : '' ?></span>
            </div>
            <div class="input-group">
                <label for="password">Password: </label>
                <input name="password" type="password" placeholder="Enter your password" id="password" onkeyup="validatePassword()">
                <span id="password-error"><?php echo isset($passwordError) ? $passwordError : '' ?></span>
            </div> 
            <div class="input-group">
                <label for="confirm-password">Confirm password: </label>
                <input name="confirmPassword" type="password" placeholder="Confirm your password" id="confirm-password" onkeyup="validateConfirmPassword()">
                <span id="confirm-password-error"></span>
            </div> 
            <button name="submit" type="submit" onclick="validateSignup(event);">Sign up</button>
            <span id="signup-error" class="submit-error"></span>
            <?php
            if(isset($_SESSION["error"]))
            {
                echo '<span id="signup-error" class="submit-error">'.$_SESSION["error"].'</span>';
            }
            if(isset($_SESSION["success"]))
            {
                echo '<span id="signup-error" class="submit-error-success">'.$_SESSION["success"].'</span>';
                
            }
            unset($_SESSION['error']);
            unset($_SESSION['success']);      
            ?>
            
            <a href="login.php">Have a count? Login</a>
        </form>
    </div>
    <script src="js/login-signup.js"></script>
</body>
</html>