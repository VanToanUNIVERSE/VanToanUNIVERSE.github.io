<?php
    session_start();
    
    require "database.php";
    $sql = "select * from users";
    try 
    {
        $statement = $connection->prepare($sql);
        $statement->execute();
        $usersData = $statement->fetchAll();
    }
    catch(PDOException $e)
    {
        echo "loi ket noi: ".$e->getMessage();
    }

    if(isset($_POST["submit"])) 
    {
        if(empty($_POST["username"]) || empty($_POST["password"])) {
            $_SESSION["error"] = "Vui lòng nhập đầy đủ thông tin";
            header("Location: ../login.php");
            exit();
        }
    
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        foreach($usersData as $userData) 
        {
            if($userData["username"] == $username && $userData["password"] == $password)
            {
                
                $_SESSION["userID"] = $userData["id"] ?? '';
                $_SESSION["username"] = $userData["username"] ?? '';
                $_SESSION["password"] = $userData["password"] ?? '';
                $_SESSION["email"] = $userData["email"] ?? '';
                $_SESSION["fullName"] = $userData["fullName"] ?? '';
                $_SESSION["phone"] = $userData["phone"] ?? '';
                $_SESSION["address"] = $userData["address"] ?? '';
                $_SESSION["role"] = $userData["role"] ?? '';
                $_SESSION["image"] = $userData["image"] ?? 'defaultAvata.png';
                $_SESSION["wallet"] = $userData["wallet"] ?? '';
                if($userData["role"] == 1)
                {
                    header("Location: ../admin/admin.php");
                    exit();
                }
                header("Location: ../index.php");
                exit();
            }  
                
        }
        $_SESSION["error"] = "Tai khoan nay khong ton tai";
        header("Location: ../login.php");
        exit();
    }

?>