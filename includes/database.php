<?php
    define("DATABASE_SERVER", "localhost");
    define("DATABASE_USER", "root");
    define("DATABASE_NAME", "quanlybanquanao");
    define("DATABASE_PASSWORD", "");

    try 
    {
        $connection = new PDO("mysql:host=". DATABASE_SERVER.";dbname=".DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<script>console.log('ket noi thanh cong')</script>";
    }
    catch(PDOException $e)
    {
        echo "loi ket noi : ".$e->getMessage();
    }
?>