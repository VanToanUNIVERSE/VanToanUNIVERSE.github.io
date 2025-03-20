<?php
    require "includes/header.php";
    require "includes/productsData.php";

    //lay va hien thi vi tien cua khach hang
    if(!isset($_SESSION["userID"])|| $_SESSION["userID"] == "" )
    {
        $userWallet = 0;
    }
    else
    {
        $sql = "select wallet from users where id = ?";
        $statement = $connection->prepare($sql);
        $statement->bindParam(1, $_SESSION["userID"]);
        $statement->execute();
        $userWallet = $statement->fetchColumn();
    }

    //tinh tong tien
    $orderPrice = 0;
    foreach($_SESSION["cart"] as $cartKey => $product)
    {
        $orderPrice += $product["productPrice"] * $product["productQuantity"];
    }

    //xoa sp
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $cartKey = $_POST["cartKey"];
        if(isset($_SESSION["cart"][$cartKey]))
        {
            unset($_SESSION["cart"][$cartKey]);
            echo "xoa thanh cong";
        }

    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/payment.css">
    
</head>
<body>
    <main>
        <div class="product-list-container">
            <button class="before-btn effect-btn"><i class="fa-solid fa-forward before "></i></button>
            <div class="product-list">

            <?php
            if(isset($_SESSION["cart"]) && !empty($_SESSION["cart"]))
            {
                foreach($_SESSION["cart"] as $cartKey => $product)
                {
                    
                    echo '
                    <div class="card" id="'.$cartKey.'">
                    <a href="product-detail.php?productID='.$product["productID"].'"><div >
                    <img src="images/products/'.$product["productImage"].'" alt="" width="100px" height="100px">
                    <p id="'.$cartKey.'-quantity" data-quantity="'.$product["productQuantity"].'">Số lượng : '.$product["productQuantity"].'</p>
                    <p id="'.$cartKey.'-price" data-price="'.$product["productPrice"].'">Giá: '.number_format($product["productPrice"], 0, ',', '.').'</p>
                    <p>Size: '.$product["productSize"].'</p>
                    </div>
                    
                    </a>
                    <button class="delete-btn" style="z-index: 1" type="button" onclick=\'deleteCard("'.$cartKey.'");deleteCardUI("'.$cartKey.'")\'><i class="fa-solid fa-trash"></i></button>
                    </div>';
                    
                }
            }
            else
            {
                echo "<h3>Chưa có sản phẩm nào trong giỏ</h3>";
            }
                
            ?>
                
                <!-- <div class="card">
                    <img src="images/AK1.png" alt="" width="100px" height="100px">
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
                </div> -->
                
            </div>
            <button class="after-btn effect-btn"><i class="fa-solid fa-forward after"></i></button>
        </div>
        

        <div class="payment-info">
            
                <p class="user-wallet">Tiền trong ví :<?php echo number_format($userWallet, 0, ',', '.') ?> VNĐ <a href="">Nạp tiền</a></p>
                
            <?php echo '<p class="total-price">Tổng :<span id="total-price-value">'.number_format($orderPrice, 0, ',', '.').'<span> VNĐ</p>'; ?>
            <!-- <p class="total-price">Tổng : 99999$</p> -->
            <p>Phương thức thanh toán: </p>
            <form>
                <label>
                  <input type="radio" name="payment-method" value="wallet" id="wallet" checked> Ví wallet
                </label>
                <label>
                  <input type="radio" name="payment-method" value="cod" id="cod"> Thanh toán khi nhận
                </label> <br>
                
                <textarea name="address" id="address" rows="3" placeholder="Nhập địa chỉ"></textarea>
                <small id="address-error">Khong dc trong</small>
                <br>
                <button class="btn payment-btn" type="button" onclick="">Thanh toan</button>
              </form>

        </div>

        <div class="display-none">
            <div class="validate-payment">
                <h2>Xác nhận thanh toán</h2>
                <div class="product-payment-list">
                    <p class="validate-product"><span class="validate-quantity">3 </span>Tên sản phâm</p>
                    <p class="validate-product"><span>3 </span class="validate-quantity">Tên sản phâm</p>
                    <p class="validate-product"><span>3 </span class="validate-quantity">Tên sản phâm</p>
                </div>
    
                <h3 id="validate-total-price">999999$</h3>
    
                <p id="validate-address"></p>
    
                <button type="button" class="validate-btn btn" onclick="Payment(); displayDeliveryContainer()">Xác nhận</button>
                <p class="payment-error">Error</p>
                <button class="closes-btn btn" onclick="hienHinh('')">X</button>
            </div>
        </div>
        <!-- thong tin dat hang -->
         <div class="delivery-container">
            <div class="delivery-head">
                <h3>Mã vận đơn</h3>
                <h3>Trạng thái</h3>
                <h3>Giá</h3>
                <h3 class="center">Thao tác</h3>
            </div>
            <!-- <div class="delivery-item">
                <p class="delivery-id">9953</p>
                <p class="delivery-status">Đang giao</p>
                <p class="delivery-price">99 $</p>
                <div class="delivery-icons">
                    <i class="fa-solid fa-eye" title="Xem chi tiết đơn"></i>
                    <i class="fa-solid fa-trash" title="Hủy đơn"></i>
                </div>
            </div>

            <div class="delivery-item">
                <p class="delivery-id">9953</p>
                <p class="delivery-status">Đang giao</p>
                <p class="delivery-price">99 $</p>
                <div class="delivery-icons">
                    <i class="fa-solid fa-eye" title="Xem chi tiết đơn"></i>
                    <i class="fa-solid fa-trash" title="Hủy đơn"></i>
                </div>
            </div> -->


         </div>

         <div class="payment-detail">
            <p>Mã vận đơn</p>
            <p>Trạng thái</p>
            <p>Giá</p>
            <p>Địa chỉ nhận hàng</p>
            <p>Phương thức thanh toán</p>
            <div class="product-list" id="payment-product-list">
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

            <button class="close-payment-detail-btn btn" onclick="closePMD()">X</button>
         </div>
        
    </main>
    <?php
    require "includes/footer.php";
    ?>
    
    <script src="js/payment.js"></script>
   
    
</body>
</html>