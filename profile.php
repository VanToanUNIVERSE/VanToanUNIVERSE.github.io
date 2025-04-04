<?php
    require "includes/header.php";
    if(!isset($_SESSION["userID"])) //chuyen huong sang trang dang nhap neu chua dn
    {
        header("Location: login.php");
        exit();
    }
    

    if(isset($_POST["submit"]))//CẬP NHẬT THÔNG TIN CÁ NHÂN
    {
        $id = $_SESSION["userID"]; // gan id
        $fullName  = !empty($_POST["fullName"]) ? $_POST["fullName"] : $_SESSION["fullName"];
        $email = !empty($_POST["email"]) ? $_POST["email"] : $_SESSION["email"];
        $phone = !empty($_POST["phone"]) ? $_POST["phone"] : $_SESSION["phone"];
        $address = !empty($_POST["address"]) ? $_POST["address"] : $_SESSION["address"];
        if(empty($fullName) && empty($email) && empty($phone) && empty($address))
        {
            $_SESSION["lastPage"] = "profile.php";
            header("Location: includes/message.php?error=1");
            exit();
        }
        else
        {
            $sql = "update users 
            set fullName = ?, email = ?, phone = ?, address = ?
            where id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $fullName);
            $statement->bindParam(2, $email);
            $statement->bindParam(3, $phone);
            $statement->bindParam(4, $address);
            $statement->bindParam(5, $id);
            $statement->execute();
            $_SESSION["fullName"] = $fullName;
            $_SESSION["email"] = $email;
            $_SESSION["phone"] = $phone;
            $_SESSION["address"] = $address;
            $_SESSION["lastPage"] = "profile.php";

            if(isset($_FILES["image"]) && !empty($_FILES["image"]["name"]))
            {
                $sql = "update users set image = ? where id = ?";
                $statement = $connection->prepare($sql);
                $statement->bindParam(1, $_FILES["image"]["name"]);
                $statement->bindParam(2, $id);
                $statement->execute();
                $_SESSION["image"] = $_FILES["image"]["name"];

                move_uploaded_file($_FILES["image"]["tmp_name"], "images/avata/".$_FILES["image"]["name"]);
            }
            header("Location: includes/message.php?success=1");
            exit();
        }
        header("Location: ./profile.php");
        exit();
    }
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
                <img src="images/avata/<?php echo $_SESSION["image"]; ?>" alt="" width="100px" height="100px">
            </div>
            <div class="info-container">
                <?php
                    echo '
                        <h3 class="user-name">'.$_SESSION["fullName"].'</h3>
                        <p>SDT: '.$_SESSION["phone"].'</p>
                        <p>Email: '.$_SESSION["email"].'</p>
                        <address>'.$_SESSION["address"].'</address>
                        <p>Số tiền trong ví: '.number_format($_SESSION["wallet"], 0, ',', '.').' VNĐ</p>
                        
                    ';
                ?>
                <button class="edit-profile-btn" onclick="
                    document.getElementById('submit-form').style.display = 'flex';
                ">Chỉnh sửa hồ sơ</button>
                <!-- <h3 class="user-name">User full name</h3>
                <p>SDT: 0932 324 534</p>
                <p>Email: jessica@gmail.com</p>
                <address>Địa chỉ hiện tại</address>
                <p>Số tiền trong ví: 99 $</p>
                <button class="edit-profile-btn">Chỉnh sửa hồ sơ</button> -->
            </div>   
        </div>

        <div class="orders-history" style="display: none">
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

        <div class="edit-form-container">
            <form action="" method="post" id="submit-form" enctype="multipart/form-data">
                <h2>Chỉnh sửa thông tin</h2>
                <div class="edit-info-container">
                    <label for="full-name">Họ tên: </label>
                    <input type="text" name="fullName" id="full-name">
                </div>
                <div class="edit-info-container">
                    <label for="email">Email: </label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="edit-info-container">
                    <label for="phone">SDT: </label>
                    <input type="text" name="phone" id="phone">
                </div>
                <div class="edit-info-container">
                    <label for="address">Địa chỉ: </label>
                    <input type="text" name="address" id="address">
                </div>
                <div class="edit-info-container">
                    <label for="address">Hình ảnh: </label>
                    <input type="file" name="image" id="image" accept="image/*">
                </div>
                <!-- <div class="edit-info-container">
                    <label for="full-name">Họ tên</label>
                    <input type="text" name="fullName" id="full-name">
                </div>-->
                <button name="submit" id="update-btn" type="submit" onclick="validateForm(event)">Cập nhật</button>
                <span id="submit-error"></span>
                <button id="close-btn" onclick="Close(event)">X</button>
            </form>
        </div>
        
    </main>
    <script src="js/profile.js"></script>
    <?php require "includes/footer.php" ?>
</body>
</html>