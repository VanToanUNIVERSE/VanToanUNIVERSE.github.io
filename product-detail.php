<?php
    require "includes/header.php";
    $sql = "
    select products.*, GROUP_CONCAT(product_images.image order by products.id separator ',') as images
    from products
    left join product_images on products.id = product_images.product_id
    group by id
    ";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $productsData = $statement->fetchAll();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $productID = $_POST["productID"];
        $productName = $_POST["productName"];
        $productQuantity = $_POST["productQuantity"];
        $productPrice = $_POST["productPrice"];
        $productSize = $_POST["productSize"];
        $productImage = $_POST["productImage"];
        
        $cartKey = $productID . "_" . $productSize;
        
        if(!isset($_SESSION["cart"]))
        {
            $_SESSION["cart"] = [];
        }
        if(isset($_SESSION["cart"][$cartKey]))
        {
            $_SESSION["cart"][$cartKey]["productQuantity"] += 1;
        }
        else
        {
            $_SESSION["cart"][$cartKey] = 
            [
                "productID" => $productID,
                "productName" => $productName,
                "productQuantity" => $productQuantity,
                "productPrice" => $productPrice,
                "productSize" => $productSize,
                "productImage" => $productImage

            ];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/products-detail.css">
</head>
<body>
    <main class="main">
        <div class="hero">
            <div class="left">
                <h2 class="logo">Amazing shop</h2>
    
                <div class="details">
                    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">


                    
                    
                    <?php
                    foreach($productsData as $product)
                    {
                        
                        if($product["id"] == $_GET["productID"])
                        {
                            $sizes = explode(",", $product["size"]);
                            echo 
                            '<h3 class="product-name">'.$product["name"].'</h3>
                            <p>Describe: '.$product["describe"].'</p>
                            <h3 class="product-cost">'.number_format($product["price"], 2, ',', '.').' VNĐ</h3>
                            <div class="size-chose">
                                <label for="size">Chọn kích thước: </label>
                                <select id="size" name="size">';
                                foreach($sizes as $size)
                                {
                                    echo '<option value="'.$size.'">'.$size.'</option>';
                                }   
                                    
                                echo '</select>
                            </div>';
                        }
                        
                    }
                       
                    ?>
                    
                    <!-- <button type="button" name="submit" onclick="addToCart('ADK01','Ao thun', 2, 200000, 'M');">Thêm vào giỏ hàng</button> -->

                    <?php
                        foreach($productsData as $product)
                        {
                            
                            if($product["id"] == $_GET["productID"])
                            {
                                $images = explode(",", $product["images"]);
                                $images = array_reverse($images);
                                $productNamez = json_encode($product["name"]);

                                echo '<button type="button" name="submit" onclick=\'addToCart('.json_encode($product["id"]).','.$productNamez.', 1, '.$product["price"].', "'.$images[0].'");\'>Thêm vào giỏ hàng</button>';
                            }
                        }
                    ?>
                    <span id="submit-message"><i class="fa-solid fa-circle-check"></i></span>
                    </form>
                </div>
    
            </div>
            <div class="right">
                <div class="wrap">
                    <button class="btn before"><i class="fa-solid fa-forward-step"></i></button>
                    <div class="product-image-container">  
                        <?php
                            foreach($productsData as $product)
                            {
                                if($product["id"] == $_GET["productID"])
                                {
                                    $images = explode(",", $product["images"]);
                                    $images = array_reverse($images);
                                    foreach($images as $image)
                                    {
                                        echo '<img src="images/products/'.$image.'" alt="'.$product["name"].'" width="100px" height="100px">';
                                    }
                                }
                            }
                        ?>                   
                        
                    </div>
                    <button class="btn after"><i class="fa-solid fa-forward-step"></i></button>
                </div>
                

                <!-- <div class="other-products">
                    <div class="card">
                        <img src="images/products/AKD01.png" alt="product image" width="100px" height="100px">
                        <p>product cost</p>
                    </div>
                    <div class="card">
                        <img src="images/products/AKD01.png" alt="product image" width="100px" height="100px">
                        <p>product cost</p>
                    </div>
                    <div class="card">
                        <img src="images/products/AKD01.png" alt="product image" width="100px" height="100px">
                        <p>product cost</p>
                    </div>
                </div> -->
    
            </div>
    
        </div>
        
        
        <?php
            require "includes/footer.php";
        
        ?>
    </main>
    <script src="js/product-detail.js"></script>
</body>
</html>