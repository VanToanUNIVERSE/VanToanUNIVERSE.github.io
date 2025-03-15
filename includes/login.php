<?php
    session_start();
    
    require "database.php";
    $sql = "select * from users";
    try 
    {
        $statement = $connection->prepare($sql);
        $statement->execute();
        $usersData = $statement->fetchAll();
        foreach($usersData as $userData)
        {
            $username = $userData["username"];
            $password = $userData["password"];
            /* echo "$username - $password"; */
        }
    }
    catch(PDOException $e)
    {
        echo "loi ket noi: ".$e->getMessage();
    }

    if(isset($_POST["submit"])) 
    {
        foreach($usersData as $userData) 
        {
            if($userData["username"] == $_POST["username"] && $userData["password"] == $_POST["password"])
            {
                $_SESSION["id"] = $userData["id"] ?? '';
                $_SESSION["username"] = $userData["username"] ?? '';
                $_SESSION["password"] = $userData["password"] ?? '';
                $_SESSION["email"] = $userData["email"] ?? '';
                $_SESSION["fullName"] = $userData["fullName"] ?? '';
                $_SESSION["phone"] = $userData["phone"] ?? '';
                $_SESSION["address"] = $userData["address"] ?? '';
                $_SESSION["role"] = $userData["role"] ?? '';
                $_SESSION["wallet"] = $userData["wallet"] ?? '';
                echo $_SESSION["username"];
                var_dump($_SESSION);
                header("Location: ../index.php");
                exit();
            }
        }
    }

?>