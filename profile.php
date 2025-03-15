<?php
    session_start();
    require "includes/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <main>
        <div class="profile-container">
            <div class="image-container">
                <img src="images/avata.png" alt="avata" width="100px" height="100px">
            </div>
            <div class="info-container">
                <?php
                    echo '
                        <h3 class="user-name">'.$_SESSION["fullName"].'</h3>
                        <p>SDT: '.$_SESSION["phone"].'</p>
                        <p>Email: '.$_SESSION["email"].'</p>
                        <address>'.$_SESSION["address"].'</address>
                        <p>Số tiền trong ví: '.$_SESSION["wallet"].' $</p>
                        <button class="edit-profile-btn">Chỉnh sửa hồ sơ</button>
                    ';
                ?>
                <!-- <h3 class="user-name">User full name</h3>
                <p>SDT: 0932 324 534</p>
                <p>Email: jessica@gmail.com</p>
                <address>Địa chỉ hiện tại</address>
                <p>Số tiền trong ví: 99 $</p>
                <button class="edit-profile-btn">Chỉnh sửa hồ sơ</button> -->
            </div>   
        </div>

        <div class="orders-history">
            <h3 class="order-title">Danh sách đơn hàng</h3>
            <div class="order-header">
                <h3>Mã vận đơn</h3>
                <h3>Trạng thái</h3>
                <h3>Giá</h3>
                <h3 class="center">Thao tác</h3>
            </div>
            <div class="order-item">
                <p class="order-id">9953</p>
                <p class="order-status">Đang giao</p>
                <p class="order-price">99 $</p>
                <div>
                    <i class="fa-solid fa-eye show-order-details" title="Xem chi tiết đơn"></i>
                </div>
                
            </div>

            <div class="order-item">
                <p class="delivery-id">9953</p>
                <p class="delivery-status">Đang giao</p>
                <p class="delivery-price">99 $</p>
                <div>
                    <i class="fa-solid fa-eye show-order-details" title="Xem chi tiết đơn"></i>    
                </div>
                          
            </div>
        </div>

        <div class="order-details">
            <p>Mã vận đơn</p>
            <p>Trạng thái</p>
            <p>Giá</p>
            <p>Địa chỉ nhận hàng</p>
            <p>Phương thức thanh toán</p>
            <div class="product-list">
                <div class="card">
                    <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                    <p>Số lượng : ?</p>
                    <p>Giá: 32$</p>
                </div>

                <div class="card">
                    <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                    <p>Số lượng : ?</p>
                    <p>Giá: 32$</p>
                </div>

                <div class="card">
                    <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                    <p>Số lượng : ?</p>
                    <p>Giá: 32$</p>
                </div>
            </div>
            <button class="close-btn">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
            
        </div>
        
    </main>
</body>
</html>