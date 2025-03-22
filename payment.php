<?php
    require "includes/header.php";
    require "includes/productsData.php";
    

    //lay va hien thi vi tien cua khach hang
    if(!isset($_SESSION["userID"])|| $_SESSION["userID"] == "" )
    {
        $_SESSION["wallet"] = 0;
    }

    //tinh tong tien
    $_SESSION["order-price"] = 0;
    if(isset($_SESSION['cart']))
    {
        foreach($_SESSION["cart"] as $cartKey => $product)
        {
            $_SESSION["order-price"] += $product["productPrice"] * $product["productQuantity"];
        }
    }
    

    //xoa sp
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["cartKey"]))
        {
            if(isset($_SESSION["cart"][$cartKey]))
            {

                $_SESSION["order-price"] -= $_SESSION["cart"][$cartKey]["productPrice"] * $_SESSION["cart"][$cartKey]["productQuantity"];
                unset($_SESSION["cart"][$cartKey]);
            }
        }
        
    }

    //hien thi nhung sp xac nhan
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["beforePayment"]))
        {
            ob_clean();
            foreach($_SESSION["cart"] as $cartKey => $product)
            {
                echo '<p class="validate-product"><span class="validate-quantity">'.$product["productQuantity"].' </span>'.$product["productName"].'</p>';
            }
            exit();
        }
    }
    

    //THANH TOAN
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["wallet"]) && isset($_POST["wallet"]) && isset($_POST["method"]) && isset($_POST["status"]))
        {
            $address = $_POST["address"];
            $wallet = $_POST["wallet"];
            $method = $_POST["method"];
            $status = $_POST["status"];
            $orderPrice = $_POST["orderPrice"];

            if($wallet < $orderPrice && $method == "wallet")
            {
                $response = ["message" => "1"];
                ob_clean();
                echo json_encode($response);
                exit();
            }
            else if($method == "wallet" && $wallet >= $orderPrice)
            {
                $wallet -= $orderPrice;
            }
            
            $sql = "update users set wallet = ? where id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $wallet);
            $statement->bindParam(2, $_SESSION["userID"]);
            $statement->execute();
            $_SESSION["wallet"] = $wallet;
            $_SESSION["orderPrice"] = 0;
            
            


            $sql = "insert into orders(user_ID, price, address) values (?, ?, ?)";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $_SESSION["userID"]);
            $statement->bindParam(2, $orderPrice);
            $statement->bindParam(3, $address);
            $statement->execute();

            $orderID = $connection->lastInsertId();
            
            $sql = "insert into payments(order_ID, method, status) values (?, ?, ?)";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->bindParam(2, $method);
            $statement->bindParam(3, $status);
            $statement->execute();

            foreach($_SESSION["cart"] as $cartKey => $product)
            {
                $sql = "INSERT INTO order_items(order_ID, product_ID, quantity, price, size) VALUES (?, ?, ?, ?, ?)";
                $statement = $connection->prepare($sql);
                $statement->bindParam(1, $orderID);
                $statement->bindParam(2, $product["productID"]);
                $statement->bindParam(3, $product["productQuantity"]);
                $price = $product["productPrice"] * $product["productQuantity"];
                $statement->bindParam(4, $price);
                $statement->bindParam(5, $product["productSize"]);
                $statement->execute();
            }

            $sql = "select create_time from orders where id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->execute();
            $createTime = $statement->fetchColumn();

            ob_clean();
            $response = [
                "wallet" => $wallet,
                "orderPrice" => 0,
                "delivery" => '
                            <p class="delivery-id">'.$orderID.'</p>
                                <p class="delivery-status" id="'.$orderID.'-status">Chờ xác nhận</p>
                                <p class="delivery-price">'.number_format($orderPrice, 0, ',', '.').' VNĐ</p>
                                <p class="delivery-create-time">'.$createTime.'</p>
                                <div class="delivery-icons">
                                    <button type="button" onclick=\'showOrderDetails("'.$orderID.'")\'><i class="fa-solid fa-eye" title="Xem chi tiết đơn"></i></button>
                                    <button id="cancel-btn" type="button" onclick=\'cancelOrder("'.$orderID.'")\'><i class="fa-solid fa-trash" title="Hủy đơn"></i></button>
                                </div>
                ',
                "orderID" => $orderID
            ];
            echo json_encode($response);


            unset($_SESSION["cart"]);
            exit();
        }
    }


    //HIEN THI ORDERS
    if(isset($_SESSION["userID"]))
    {
        $sql = "select * from orders where user_ID = ?";
        $statement = $connection->prepare($sql);
        $statement->bindValue(1, $_SESSION["userID"], PDO::PARAM_INT);
        $statement->execute();
        $ordersData = $statement->fetchAll();
    }
    //HIEN THI CHI TIET ORDER

    
    

    /* foreach($orderItemsData as $orderItem)
    {
        echo $orderItem["product_ID"];
    } */
    if(isset($_SERVER["REQUEST_METHOD"]) == "POST")// xem chi tiet don
    {
        $action = $_POST["action"] ?? "";
        if(isset($_POST["orderID"]) && $action == "show")
        {
            $orderID = $_POST["orderID"];
            $sql = "select orders.*, payments.status as paymentStatus, payments.method FROM
            orders LEFT JOIN payments ON orders.id = payments.order_ID
            where orders.id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->execute();
            $order = $statement->fetch();

            $sql = "select order_items.*, products.price AS productPrice, product_images.image from order_items left join products on order_items.product_ID = products.id inner join product_images on product_images.product_id = products.id and product_images.is_main = 1 where order_items.order_ID = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->execute();
            $orderItems = $statement->fetchAll();
            $paymentMethod = $order["method"] == "wallet" ? "Tiền trong ví" : "Thanh toán khi nhận hàng";

            ob_clean();
            echo '
            <p>Mã đơn: '.$order["id"].'</p>
            <p>Trạng thái đơn: '.$order["status"].'</p>
            <p>Giá: '.number_format($order["price"]).' VNĐ</p>
            <p>Địa chỉ nhận hàng: '.$order["address"].'</p>
            <p>Phương thức thanh toán: '.$paymentMethod.'</p>
            <p>Trạng thái thanh toán: '.$order["paymentStatus"].'</p>
            <div id="payment-product-list">';


            
            

            foreach($orderItems as $orderItem)
            {
                echo '<div class="card">
                    <img src="images/products/'.$orderItem["image"].'" alt="" width="100px" height="100px">
                    <p>Size : '.$orderItem["size"].'</p>
                    <p>Số lượng : '.$orderItem["quantity"].'</p>
                    <p>Đơn giá: '.number_format($orderItem["productPrice"]).' VNĐ</p>
                    <p>Số lượng: '.$orderItem["quantity"].'</p>
                    <p>Tổng: '.number_format($orderItem["price"]).' VNĐ</p>
                </div>';
            }
                

                /* <div class="card">
                    <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                    <p>Số lượng : ?</p>
                    <p>Giá: 32$</p>
                </div>

                <div class="card">
                    <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                    <p>Số lượng : ?</p>
                    <p>Giá: 32$</p>
                </div>
 */
                
            echo '</div>

            <button class="close-order-detail btn" onclick="closePMD();">X</button>';
                
                exit();
            
        } 
    }
    if(isset($_POST["orderID"]) && $action == "cancel")//huy don
    {
        if(isset($_POST["remove"]) && $_POST["remove"] == 1)
        {
            $sql = "delete from orders where id = ?";
        }
        else
        {   
            $sql = "update orders set status = 'Đã hủy' where id = ?";
        }
        
        $statement = $connection->prepare($sql);
        $statement->bindParam(1, $_POST["orderID"]);
        $statement->execute();
        ob_clean();
        echo "Đã hủy";
        exit();
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
            
                <p class="user-wallet" data-wallet="<?php echo $_SESSION["wallet"] ?>">Tiền trong ví :<?php echo number_format($_SESSION["wallet"], 0, ',', '.') ?> VNĐ <a href="">Nạp tiền</a></p>
                
            <?php echo '<p class="total-price" data-total-price="'.$_SESSION["order-price"].'">Tổng :<span id="total-price-value">'.number_format($_SESSION["order-price"], 0, ',', '.').'<span> VNĐ</p>'; ?>
            <!-- <p class="total-price">Tổng : 99999$</p> -->
            <p>Phương thức thanh toán: </p>
            <form>
                <label>
                  <input type="radio" name="payment-method" value="wallet" id="wallet" checked> Ví wallet
                </label>
                <label>
                  <input type="radio" name="payment-method" value="cod" id="cod"> Thanh toán khi nhận
                </label> <br>
                
                
                <textarea class="address" style="display: block"; name="address" id="address" rows="3"><?php
                    echo $_SESSION["address"] ?? "";
                ?></textarea>
                <small id="address-error">Khong dc trong</small>
                <br>
                <button class="btn payment-btn" type="button" onclick="beforePayment()">Thanh toán</button>
              </form>

        </div>

        <div class="display-none">
            <div class="validate-payment">
            <h2>Xác nhận thanh toán</h2>
            <div class="product-payment-list">
                
                    <!-- if(isset($_SESSION["cart"]) && !empty($_SESSION["cart"]))
                    {
                        foreach($_SESSION["cart"] as $cartKey => $product)
                        {
                            echo '<p class="validate-product"><span>'.$product["productQuantity"].' </span class="validate-quantity">'.$product["productName"].'</p>';
                        }
                    }
                 -->
                
                    <!-- <p class="validate-product"><span class="validate-quantity">3 </span>Tên sản phâm</p>
                    <p class="validate-product"><span>3 </span class="validate-quantity">Tên sản phâm</p>
                    <p class="validate-product"><span>3 </span class="validate-quantity">Tên sản phâm</p> -->
                </div>
    
                <h3 id="validate-total-price"><?php echo number_format($_SESSION["order-price"], 0, ",", "."); ?> VNĐ</h3>
    
                <p id="validate-address"></p>
                <p id="validate-payment-method">Phương thức thanh toán</p>
    
                <button type="button" class="validate-btn btn" onclick="<?php echo 'Payment('.$_SESSION["wallet"].', '.$_SESSION["order-price"].')';?>; displayDeliveryContainer();">Xác nhận</button>
                <p class="payment-error">Error</p>
                <button class="closes-btn btn" onclick="hienHinh('')">X</button>
            </div>
            
        </div>
        <div class="payment-success">Thanh toán thành công</div>
        <!-- thong tin dat hang -->
         <div class="delivery-container">
            <div class="delivery-head">
                <h3>Mã đơn</h3>
                <h3>Trạng thái</h3>
                <h3>Giá</h3>
                <h3>Thời gian tạo</h3>
                <h3 class="center">Thao tác</h3>
            </div>
                
            <?php
                if(!empty($ordersData))
                {
                    foreach($ordersData as $order)
                    {
                        echo '
                            <div class="delivery-item" id="'.$order["id"].'">
                                <p class="delivery-id">'.$order["id"].'</p>
                                <p class="delivery-status" id="'.$order["id"].'-status">'.$order["status"].'</p>
                                <p class="delivery-price">'.number_format($order["price"], 0, ',', '.').' VNĐ</p>
                                <p class="delivery-create-time">'.$order["create_time"].'</p>
                                <div class="delivery-icons">
                                    <button type="button" onclick=\'showOrderDetails("'.$order["id"].'")\'><i class="fa-solid fa-eye" title="Xem chi tiết đơn"></i></button>
                                    <button id="cancel-btn" type="button" onclick=\'cancelOrder("'.$order["id"].'")\'><i class="fa-solid fa-trash" title="Hủy đơn"></i></button>
                                </div>
                            </div>
                        ';
                    }
                    
                }
                else
                {
                
                    echo "<script> document.querySelector('.delivery-container').style.display = 'none';</script>";
                }
            ?>

         </div>


        <div class="payment-detail">
            
         </div>
        
    </main>
    <?php
    require "includes/footer.php";
    ?>
    
    <script src="js/payment.js"></script>
   
    
</body>
</html>