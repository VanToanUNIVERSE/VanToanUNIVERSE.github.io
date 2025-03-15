<?php
    session_start();
    $username = $_SESSION["username"]  ?? '';
    $image = $_SESSION["image"] ?? 'defaultAvata.png';
    $_SESSION["userID"] = $_SESSION["userID"] ?? '';
    require "includes/database.php";
    
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
            <a href="<?php echo $_SESSION["userID"] ? "includes/logout.php" : "login.php" ?>"><?php echo $_SESSION["userID"] ? "Đăng xuất" : "Đăng nhập" ?></a>
            <a href="payment.php">Giỏ hàng</a>
            <a href="">Sản phẩm yêu thích</a>
            <a href="">Nạp tiền</a>
            <button class="close-btn" onclick="showAndClose('more-content')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="user-info">
            <a href="profile.php">
                <img src="<?php echo "images/avata/$image" ?>" alt="avata" width="50px" height="50px">
                <div><p><?php echo $username;?></p></div>
            </a>
            
        </div>
        
        <div class="nav-container">
            <div class="logo">
                <h3>Amazing shop</h3>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="">Home</a></li>
                    <div class="drop-down">
                        <li><a href="product.php">Categories <i class="fa-solid fa-caret-down"></i></a></li>
                        <div class="drop-down-content">
                            <ul>
                                    <?php
                                        foreach($categoriesData as $dt)
                                        {
                                            echo '<li><a href="product.php" id="'.$dt["id"].'">'.$dt["name"].'</a></li>';
                                            echo '<script>console.log(document.querySelector(".drop-down-content"))</script>';
                                        }
                                    ?>
                                
                            </ul>
                        </div>
                    </div>
                    
                    <li><a href="payment.php">Cart</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
            <div class="social">
                <a href="https://www.facebook.com/profile.php?id=100031724704790&sk=about&locale=vi_VN" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@toannguyen9579?is_from_webapp=1&sender_device=pc" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                
            </div>
        </div>
        
    </header>


    <main>

        <div class="introduce">
            <div class="left">
                <h3>A place where you can buy any clothes you want</h3>
                <div class="negavi">
                    <a href="#products">Shopping now</a>
                    <a id="login" href="login.php">Login</a>
                </div>
                
            </div>
            <div class="right">
                <img src="images/introduce.png" alt="introduce images" width="100px" height="100px">
            </div>
        </div>

        <div id="products" class="products-container">
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
            <div class="card">
                <img src="images/AK1.png" alt="áo thun" width="200px" height="200px">
                <p>product name</p>
                <p>product cost</p>
            </div>
           
            <a href="product.php" class="more-products">Xem thêm <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </main>
    <footer>
        <div class="footer-top">
            <div class="contact-info footer-column">
                <h3>Liên hệ</h3>
                <address>168 Nguyễn Văn Cừ Nối Dài, An Bình, Ninh Kiều, Cần Thơ</address>
                <p><i class="fa-solid fa-phone"></i> 0982 342 532</p>
                <p><i class="fa-solid fa-envelope"></i> <a href="mailto:totitangu@gmail.com" style="text-decoration: none;"> totitangu@gmail.com</a> </p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/profile.php?id=100031724704790&sk=about&locale=vi_VN" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@toannguyen9579?is_from_webapp=1&sender_device=pc" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div class="navigation footer-column">
                <h3>Điều hướng</h3>
                <ul>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Phương thức thanh toán</a></li>
                </ul>
            </div>

            <div class="navigation footer-column">
                <h3>Chính sách</h3>
                <ul>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Phương thức thanh toán</a></li>
                </ul>
            </div>
        </div>

        <hr>
        <div class="footer-bottom">
            <p>	&#169; 2025 Shop Quần Áo Amazing Shop </p>
            <small>Thiết kế bởi <b>Nguyễn Văn Toàn và Lê Văn Minh Toàn</b></small>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>