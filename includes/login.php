<?php

    require "database.php";
    $sql = "select username, password from users";
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
                header("Location: ../index.php");
                exit();
            }
        }
    }

?>