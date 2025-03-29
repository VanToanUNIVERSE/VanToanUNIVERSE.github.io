<?php
    session_start();
    $username = $_SESSION["username"]  ?? '';
    $image = $_SESSION["image"] ?? 'defaultAvata.png';
    $userID = $_SESSION["userID"] ?? '';
    require "database.php";
    
    if(!$connection)
    {
        die("Loi ket noi voi databasse");
    }
    else
    {
        $sql = "select * from categories where active = 1";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $categoriesData = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <header>
        <i class="fa-regular fa-square-caret-down more"></i>
        <a href="payment.php"><i class="fa-solid fa-cart-shopping cart"></i></a>
        <div class="more-content">
            <a href="<?php echo $userID ? "includes/logout.php" : "login.php" ?>"><?php echo $userID ? "Đăng xuất" : "Đăng nhập" ?></a>
            <a href="../payment.php">Giỏ hàng</a>
            <a href="">Sản phẩm yêu thích</a>
            <a href="">Nạp tiền</a>
            <button class="close-btn" onclick="showAndClose('more-content')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="user-info">
            <a href="profile.php">
                <img src="<?php echo "images/avata/$image" ?>" alt="avata" width="50px" height="50px">
                
            </a>
            
        </div>
        <div class="nav-container">
            <div class="logo">
                <h3>Amazing shop</h3>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="../index.php">Trang chủ</a></li>
                    <div class="drop-down">
                        <li><a href="../product.php?page=1">Danh mục <i class="fa-solid fa-caret-down"></i></a></li>
                        <div class="drop-down-content">
                            <ul>
                                    <?php
                                        foreach($categoriesData as $dt)
                                        {
                                            echo '<li><a href="product.php?page=1&categoryID='.$dt["id"].'" id="'.$dt["id"].'">'.$dt["name"].'</a></li>';
                                            echo '<script>console.log(document.querySelector(".drop-down-content"))</script>';
                                        }
                                    ?>
                                
                            </ul>
                        </div>
                    </div>
                    
                    <li><a href="../payment.php">Giỏ hàng</a></li>
                    <li><a href="../about.php">Vê chúng tôi</a></li>
                </ul>
            </nav>
            <div class="social">
                <a href="https://www.facebook.com/profile.php?id=100031724704790&sk=about&locale=vi_VN" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@toannguyen9579?is_from_webapp=1&sender_device=pc" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                
            </div>
        </div>
        
    </header>