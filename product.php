<?php
    require "includes/header.php";
    $sql = "select * from subcategories";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $subcategoriesData = $statement->fetchAll();


    $sql = "select count(*) from products";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $productQuantity = $statement->fetchColumn();
    $page = isset($_GET["page"]) ? (int)$_GET['page'] : 1;
    if($page < 1) $page = 1;
    $offset = (int)(($page - 1) * 8);
    $totalPages = ceil($productQuantity/8);

    $sql = "SELECT products.*, product_images.image 
    FROM products 
    LEFT JOIN product_images ON products.id = product_images.product_id AND product_images.is_main = 1
    GROUP BY products.id
    LIMIT 8 OFFSET :offset";  
    $statement = $connection->prepare($sql);
    $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    $productList = $statement->fetchAll();

    

    if(isset($_GET["subcategoryID"])) // loc danh sach dua tren subcategoryID
    {
        $sql = "select count(*) from products where products.subcategory_id = ?"; // tạo lại trang 
        $statement = $connection->prepare($sql);
        $statement->bindParam(1, $_GET["subcategoryID"]);
        $statement->execute();
        $productQuantity = $statement->fetchColumn();
        $page = isset($_GET["page"]) ? (int)$_GET['page'] : 1;
        if($page < 1) $page = 1;
        $offset = (int)(($page - 1) * 8);
        $totalPages = ceil($productQuantity/8);
        
        $sql = "SELECT products.*, product_images.image
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id AND product_images.is_main = 1 
        WHERE products.subcategory_id = :id
        GROUP BY products.id
         LIMIT 8 OFFSET :offset"; 
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $_GET["subcategoryID"]);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $productList = $statement->fetchAll();

        
    }

    if(isset($_GET["categoryID"]))
    {
        $sql = "select count(*) from products where products.category_id = ?"; // tạo lại trang 
        $statement = $connection->prepare($sql);
        $statement->bindParam(1, $_GET["categoryID"]);
        $statement->execute();
        $productQuantity = $statement->fetchColumn();
        $page = isset($_GET["page"]) ? (int)$_GET['page'] : 1;
        if($page < 1) $page = 1;
        $offset = (int)(($page - 1) * 8);
        $totalPages = ceil($productQuantity/8);


        $sql = "SELECT products.*, product_images.image
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id AND product_images.is_main = 1 
        WHERE products.category_id = :id
        GROUP BY products.id
        LIMIT 8 OFFSET :offset";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $_GET["categoryID"]);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $productList = $statement->fetchAll();

    }

    if(isset($_GET["searchInput"]))
    {

        $sql = "select count(*) from products where products.category_id = ?"; // tạo lại trang 
        $statement = $connection->prepare($sql);
        $statement->bindParam(1, $_GET["categoryID"]);
        $statement->execute();
        $productQuantity = $statement->fetchColumn();
        $page = isset($_GET["page"]) ? (int)$_GET['page'] : 1;
        if($page < 1) $page = 1;
        $offset = (int)(($page - 1) * 8);
        $totalPages = ceil($productQuantity/8);


        $searchInput = $_GET["searchInput"];
        $searchQuery = "%".$searchInput."%";
            $sql = "select products.*, product_images.image from products
            left join product_images on products.id = product_images.product_id and product_images.is_main = 1 where products.id like :search or products.name like :search LIMIT 8 OFFSET :offset";
        $statement = $connection->prepare($sql);
        $statement->bindParam(":search", $searchQuery);
        $statement->bindParam(":search", $searchQuery);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $productList = $statement->fetchAll();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>San pham</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+IT+Moderna:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/product.css">
</head>
<body>
    <main>
        <div class="categories">

            <?php
                foreach($categoriesData as $category) 
                {
                    echo '
                        <div class="dropdown AK-category">
                            <button class="dropdown-btn"><a href=product.php?categoryID='.$category["id"].'>'.$category["name"].'</a> <i class="fa-solid fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <ul>';                      
                                foreach($subcategoriesData as $subcategory)
                                {
                                    if($category["id"] == $subcategory["categoriy_id"])
                                    echo '<li><a href="product.php?subcategoryID='.$subcategory["id"].'">'.$subcategory["name"].'</a></li>';
                                }
                                echo '</ul>
                            </div>
                        </div>
                    ';
                }
            ?>
           
        </div>

        <form class="search" method="get" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <input id="search-input" name="searchInput" type="text">
            <button id="search-btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
            </form>

        <div class="products-container">
            <div class="category-name"><?php 
            if(isset($_GET["subcategoryID"]))
            {
                foreach($subcategoriesData as $sub)
                {
                    if($sub["id"] == $_GET["subcategoryID"])
                    {
                        echo '<h3>'.$sub["name"].'</h3>';
                    }
                }
            }
            
            ?></div>
            <?php
            if(empty($productList))
            {
                echo "<h3>Chưa có sản phẩm nào</h3>";
            }
            foreach($productList as $product)
            {
                echo ' 
                <a href="product-detail.php?productID='.$product["id"].'"><div class="card">
                <img src="images/products/'.$product["image"].'" alt="'.$product["name"].'" width="100px" height="100px">
                <p>'.$product["name"].'</p>
                <h3>'.number_format($product["price"], 0, ',', '.').' VNĐ</h3>
                </div></a>';
            }
                
            ?>
            <!-- <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>

            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>

            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>
            <div class="card">
                <img src="images/AK1-1.png" alt="" width="100px" height="100px">
                <p>Áo khoác dù phối xéo</p>
                <h3>32 $</h3>
            </div>-->
        </div> 

        <nav class="turn-page">
            <?php
                for($i = 1; $i <= $totalPages ; $i++)
                {
                   if(isset($_GET["categoryID"]))
                   {

                    if($i == $page) //neu la trang hien tai
                    {
                        
                        echo '<a style="text-decoration: underline;" href="product.php?page='.$i.'&categoryID='.$_GET["categoryID"].'">'.$i.'</a>
                ';//in co gach chan
                    }
                    else
                    {
                        echo '
                    <a href="product.php?page='.$i.'&categoryID='.$_GET["categoryID"].'">'.$i.'</a>
                ';//khong co gach chan
                    }
                    
            
                   }
                   else{
                    if($i == $page) //neu la trang hien tai
                    {
                        
                        echo '<a href="product.php?page='.$i.'" style="text-decoration: underline;">'.$i.'</a>';//in co gach chan
                    }
                    else
                    {
                        echo '<a href="product.php?page='.$i.'">'.$i.'</a>';//khong co gach chan
                    }
                   }
                    
                }
            ?>
        </nav>
        <?php
        require "includes/footer.php";
    ?>
    </main>
</body>
</html>