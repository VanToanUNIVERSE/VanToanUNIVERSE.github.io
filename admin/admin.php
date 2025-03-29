<?php
    require "../includes/database.php";
    //LOAD SAN PHAM LEN TRANG TU CSDL
    $sql = "select products.*, product_images.image from products
            left join product_images on products.id = product_images.product_id and product_images.is_main = 1"; 
    
    if(isset($_GET["page"]) && $_GET["page"] == "costomer")
    {
        $sql = "select * from users"; 
    }
    if(isset($_GET["page"]) && $_GET["page"] == "order")
    {
        $sql = "select orders.*, payments.status as paymentStatus from orders left join payments on orders.id = payments.order_ID order by create_time desc"; 
    }
    $statement = $connection->prepare($sql);
    $statement->execute();
    $Data = $statement->fetchAll();

    //LOAD THONG TIN CHUNG
    if(!isset($_GET["page"]))
    {
        $sql = "select count(*) from users";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $totalUser = $statement->fetchColumn();

        $sql = "select count(*) from products";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $totalproduct = $statement->fetchColumn();

        $sql = "select count(*) from orders";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $totalOrder = $statement->fetchColumn();

        $sql = "select sum(price) from orders";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $totalRevenue = $statement->fetchColumn();

        $sql = "SELECT orders.*, users.fullName FROM orders
        LEFT JOIN users ON orders.user_ID = users.id
        WHERE STATUS = 'Chờ xác nhận' ORDER BY create_time DESC ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $ordersData = $statement->fetchAll();
    }
    

    //TIM KIEM
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["searchQuery"]))
        {
            $searchQuery = "%".$_POST["searchQuery"]."%";
            $sql = "select products.*, product_images.image from products
            left join product_images on products.id = product_images.product_id and product_images.is_main = 1 where products.id like ? or products.name like ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $searchQuery);
            $statement->bindParam(2, $searchQuery);
            $statement->execute();
            $Data = $statement->fetchAll();
            ob_clean();
            echo '<tr><th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Giá tiền</th>
                        <th>Chức năng</th></tr>';
            foreach($Data as $product)
            {
                echo '<tr><td>'.$product["id"].'</td>
                    <td>'.$product["name"].'</td>
                    <td><img src="../images/products/'.$product["image"].'" alt="'.$product["name"].'" width="100px" height="100px"></td>
                    <td>'.$product["quantity"].'</td>
                    <td>'.number_format($product["price"]).' VNĐ</td>
                    <td class="nowrap">
                        <button><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                        <button><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                        <button type="button" onclick="display();displayProductDetails(\''.$product["id"].'\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                    </td></tr>';
            }
            exit();
        }
    }

    //HIEN THI PRODUCTDETAILS
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["productID"]) && $_POST["action"] == "show")
        {
            $productID = $_POST["productID"];
            $sql = "select * from products where id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $productID);
            $statement->execute();
            $product = $statement->fetch();
            $sql = "select * from product_images where product_id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $productID);
            $statement->execute();
            $imageData = $statement->fetchAll();
            $sizes = explode(",", $product["size"]);
            
            ob_clean();
            echo '
                <h3>Thông tin sản phẩm</h3>
            <div class="properties">
                <label for="">Mã sản phẩm</label>
                <input type="text" value="'.$product["id"].'">
            </div>
            <div class="properties">
                <label for="">Tên sản phẩm</label>
                <input type="text" value="'.$product["name"].'">
            </div>
            
            <div class="properties">
                <label for="">Giá</label>
                <input type="text" value="'.number_format($product["price"]).'"> VNĐ
            </div>
            <div class="properties checkbox">
                <label for="">Hot</label>';
                if($product["hot"] == 1)
                {
                    echo '<input type="checkbox" id="hot" checked>';
                }
                else
                {
                    echo '<input type="checkbox" id="hot">';
                }
                    
              
                
            echo '</div>
            
            <div class="properties checkbox">
                <label for="">Size: </label>';
                
                foreach ($sizes as $size) {
                    $checked = in_array($size, $sizes) ? 'checked' : '';
                    echo '<input type="checkbox" name="size[]" value="'.$size.'" '.$checked.'> '.$size;
                }
                    
              
                
            echo '</div>
    
            <div class="properties">
                <label for="">Số lượng</label>
                <input type="text" value="'.$product["quantity"].'">
            </div>
            <div class="properties">
                <label for="">Danh mục</label>
                <input type="text" value="'.$product["category_id"].'">
            </div>
            <div class="properties">
                <label for="">Danh mục con</label>
                <input type="text" value="'.$product["subcategory_id"].'">
            </div>
            <div class="properties">
                <label for="">Mô tả</label>
                <textarea type="text" rows="8" >Áo thun Line với đường nét đánh bông tạo điểm nhấn nhẹ nhàng nhưng đậm chất sporty. Form dáng regular rộng rãi, phù hợp với nhiều vóc dáng. Chất liệu cotton nguyên chất 250gsm, tạo cảm giác dễ chịu, thấm hút mồ hôi tốt và thoáng mát. </textarea>
            </div>
            <div class="properties">
                <label for="">Hình ảnh</label>';
                foreach($imageData as $image)
                {
                    echo '<img src="../images/products/'.$image["image"].'" width="100px">';
                }
                
            echo '</div>
            <div class="operation">
                <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Đóng</button>
                
            </div>
            ';
            exit();
        }
    }

    //THEM SAN PHAM
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "add")
        {
            $id = $_POST["productID"];
            $name = $_POST["productName"];
            $price = $_POST["productPrice"];
            $hot = $_POST["productHot"] == True ? 1 : 0;
            $size = $_POST["productSize"];
            $describe = $_POST["productDescribe"];
            $quantity = $_POST['productQuantity'];
            $sql = "insert into products(id, name, price, hot, size, `describe`, quantity) values (?, ?, ?, ?, ?, ?, ?)";

            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $id);
            $statement->bindParam(2, $name);
            $statement->bindParam(3, $price);
            $statement->bindParam(4, $hot);
            $statement->bindParam(5, $size);
            $statement->bindParam(6, $describe);
            $statement->bindParam(7, $quantity);
            $statement->execute();
            ob_clean();
            echo "thanh cong";
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/admin-index.css">
    <link rel="stylesheet" href="../css/admin-product.css">
</head>
<body>
    <div class="management-container">
        <div class="info-container">
            <img src="../images/avata/defaultAvata.png" alt="Avata" width="100px" height="100px">
            <p>Nguyễn Văn Toàn</p>
        </div>
        <hr>
        <div class="management">
            <a href="admin.php"><p>Bảng điều khiền</p></a>
            <a href="admin.php?page=costomer"><p>Quản lý khách hàng</p></a>
            <a href="admin.php?page=product"><p>Quản lý sản phẩm</p></a>
            <a href="admin.php?page=order"><p>Quản lý đơn hàng</p></a>
            <a href="../includes/logout.php">Đăng xuất</a>
        </div>
    </div> 
    
    <div class="products-container">
        

            <?php
                if(!isset($_GET["page"]))
                {
                    echo '<h3>Thông tin chung</h3>
        <hr>
        <div class="dashboard">
            <div class="total total-costomer">
                <i class="fa-solid fa-user"></i>
                <div>
                    <p class="title">Tổng khách hàng</p>
                    
                         <p class="content">'.$totalUser.' Khách hàng</p>
                    
                    
                </div>
                
            </div>
            <div class="total total-product">
                <i class="fa-solid fa-shirt"></i>
                <div>
                    <p class="title">Tổng sản phẩm</p>
                    <p class="content">'.$totalproduct.' Sản phẩm</p>        
                </div>
                
            </div>
            <div class="total total-order">
                <i class="fa-solid fa-cart-shopping"></i>
                <div>
                    <p class="title">Tổng đơn</p>
                    
                        <p class="content">'.$totalOrder.' Đơn hàng</p>
                  
                </div>
            </div>
            <div class="total total-revenue">
                <i class="fa-solid fa-money-bill"></i>
                <div>
                    <p class="title">Tổng doanh thu</p>
                    
                        <p class="content">'.number_format($totalRevenue).' VNĐ</p>
                    
                </div>
            </div>
        </div>

        <div class="orders-container">
            <h3>Đơn hàng mới</h3>
            
            <table>
                <tr>
                    <th>Mã đơn</th>
                    <th>Tên khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái đơn</th>
                    <th>Thời gian</th>
                </tr>';
                    foreach($ordersData as $order)
                    {
                        echo '<tr>
                    <td>'.$order["id"].'</td>
                    <td>'.$order["fullName"].'</td>
                    <td>'.number_format($order["price"]).' VNĐ</th>
                    <td class="unsafe">'.$order["status"].'</th>
                    <td>'.date("d/m/Y H:i:s", strtotime($order['create_time'])).'</th>
                </tr>';
                    }
                
            echo '</table>
        </div>';
                }
                if(isset($_GET["page"]) && $_GET["page"] == "order")
                {
                    echo '<div>
            <div class="add-product">
                <i class="fa-solid fa-plus"></i>
                <p>Thêm đơn hàng mới</p>
            </div>
        </div> 
        <h3>Danh sách đơn hàng</h3>
        <label for="search">
            Tìm kiếm <input type="text" name="search" id="search" onkeyup="searchProduct()"> 
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </label>
        <table>';


                    echo '<tr>
                    <th>Mã đơn hàng</th>
                    <th>Mã người dùng</th>
                    <th>Giá</th>
                    <th>Trạng thái đơn</th>
                    <th>Địa chỉ</th>
                    <th>Thời gian tạo</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Chức năng</th>
                    </tr>';
                    foreach($Data as $order)
                    {
                        echo '<tr>
                    <td>'.$order["id"].'</td>
                    <td>'.$order["user_ID"].'</td>
                    <td>'.number_format($order["price"]).' VNĐ</td>';
                    if($order["status"] == "Chờ xác nhận")
                    {
                        echo '<td class="unsafe">'.$order["status"].'</td>';
                    }
                    else
                    {
                        echo '<td class="safe">'.$order["status"].'</td>';
                    }
                    
                    echo '<td>'.$order["address"].'</td>
                    <td>'.$order["create_time"].'</td>
                    <td>'.$order["paymentStatus"].'</td>
                    <td class="nowrap">
                        <button><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                        <button><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                        <button><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                    </td>
                    </tr>';
                    }
                }
                if(isset($_GET["page"]) && $_GET["page"] == "costomer")
                {
                    echo '<div>
            <div class="add-product">
                <i class="fa-solid fa-plus"></i>
                <p>Thêm người dùng mới</p>
            </div>
        </div> 
        <h3>Danh sách người dùng</h3>
        <label for="search">
            Tìm kiếm <input type="text" name="search" id="search"> 
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </label>
        <table>';


                    echo '<tr>
                    <th>Mã người dùng</th>
                    <th>Tên đăng nhập</th>
                    <th>Mật khẩu</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Ví</th>
                    <th>Chức năng</th>
                    </tr>';
                    foreach($Data as $user)
                {
                    echo '<tr>
                    <td>'.$user["id"].'</td>
                    <td>'.$user["username"].'</td>
                    <td>'.$user["password"].'</td>
                    <td>'.$user["phone"].'</td>
                    <td>'.$user["address"].'</td>
                    <td>'.number_format($user["wallet"]).' VNĐ</td>
                    <td class="nowrap">
                        <button><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                        <button><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                        <button><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                    </td>
                    </tr>';
                    }
                }
                
                if(isset($_GET["page"]) && $_GET["page"] == "product")
                {
                    echo '<div>
                    <div class="add-product">
                        
                        <button class="nowrap" type="button" onclick="display()" id="add-product-btn"><i class="fa-solid fa-plus"></i><p>Thêm sản phẩm mới</p></button>
                    </div>
                </div> 
                <h3>Danh sách sản phẩm</h3>
                <label for="search">
                    Tìm kiếm <input type="text" name="search" id="search" onkeyup="searchProduct()"> 
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </label>
                <table>';

                    echo '<th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Giá tiền</th>
                        <th>Chức năng</th>';
                foreach($Data as $product)
                {
                    echo '<tr>
                    <td>'.$product["id"].'</td>
                    <td>'.$product["name"].'</td>
                    <td><img src="../images/products/'.$product["image"].'" alt="'.$product["name"].'" width="100px" height="100px"></td>
                    <td>'.$product["quantity"].'</td>
                    <td>'.number_format($product["price"]).' VNĐ</td>
                    <td class="nowrap">
                        <button><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                        <button><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                        <button onclick="display(); displayProductDetails(\''.$product["id"].'\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                    </td>
                    </tr>';
                    }
                }
                
            ?>
        </table>


        <form action="includes/admin.php">
            <?php
            if(isset($_GET["page"]) && $_GET["page"] == "product")
            {
                $sql = "select id from subcategories";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $subCategoriesData = $statement->fetchAll();
                $sql = "select id from categories";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $categoriesData = $statement->fetchAll();
                
                echo '<h3>Thông tin sản phẩm</h3>
            <div class="properties">
                <label for="">Mã sản phẩm</label>
                <input id="id" type="text" value="AKD01">
            </div>
            <div class="properties">
                <label for="">Tên sản phẩm</label>
                <input id="name" type="text" value="Áo khoác dù phối xéo AKD01">
            </div>
            
            <div class="properties">
                <label for="">Giá</label>
                <input id="price" type="text" value="200000"> VNĐ
            </div>
            <div class="properties checkbox">
                <label for="">Hot</label>
                <input id="hot" type="checkbox" id="hot">     
            </div>
            <div class="properties checkbox">
                <label for="">Size: </label>
                    <input type="checkbox" clas="checkbox" name="size" value="S"> S
                    <input type="checkbox" clas="checkbox" name="size" value="M"> M
                    <input type="checkbox" clas="checkbox" name="size" value="L"> L
                    <input type="checkbox" clas="checkbox" name="size" value="XL"> XL  
            </div>
            <div class="properties">
                <label for="">Số lượng</label>
                <input id="quantity" type="text" value="49">
            </div>
            <div class="properties">
                <label for="">Danh mục</label>
                <select id="categories" name="categories">';

                foreach($categoriesData as $cate)
                {
                    echo '<option value="'.$cate["id"].'">'.$cate["id"].'</option>';
                }
                echo '</select>

            </div>
            <div class="properties">
                <label for="">Danh mục con</label>

                <select id="subcategories" name="subcategories">';
                foreach($subCategoriesData as $sub)
                {
                    echo '<option value="'.$sub["id"].'">'.$sub["id"].'</option>';
                }
                echo '</select>
            </div>
            <div class="properties">
                <label for="">Mô tả</label>
                <textarea id="describe" type="text" rows="8" >Áo thun Line với đường nét đánh bông tạo điểm nhấn nhẹ nhàng nhưng đậm chất sporty. Form dáng regular rộng rãi, phù hợp với nhiều vóc dáng. Chất liệu cotton nguyên chất 250gsm, tạo cảm giác dễ chịu, thấm hút mồ hôi tốt và thoáng mát. </textarea>
            </div>
            <div class="operation">
                <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Hủy</button>
                <button class="btn" type="button" onclick="addProduct();"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
            </div>
';
            }
            ?>
            
        </form>
    </div>

    
    <script src="../js/admin.js"></script>
</body>
</html>