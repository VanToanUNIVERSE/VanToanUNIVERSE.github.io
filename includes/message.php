<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .notification {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            text-align: center;
            max-width: 400px;
        }
        .success { border-left: 5px solid #28a745; color: #28a745; }
        .error { border-left: 5px solid #dc3545; color: #dc3545; }
        .info { border-left: 5px solid #007bff; color: #007bff; }
        .notification p { font-size: 18px; margin-bottom: 10px; }
        .notification a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }
        .notification a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="notification">
        
            <?php 
                if(isset($_GET["success"]) && $_GET["success"] == 1)
                {
                    echo '<p class="success">Thành công</p>';
                }
                if(isset($_GET["error"]) && $_GET["error"] == 1)
                {
                    echo '<p class="error">Thất bại</p>';
                }
            ?>
        <a href="../index">Quay về trang chủ</a>
        <a href="../<?php echo $_SESSION["lastPage"]; ?>">OK</a>
    </div>
</body>
</html>
