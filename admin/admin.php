<?php
    session_start();
    require "../includes/database.php";
    //LOAD NOI DUNG TUY THEO TRANG
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

    //LOAD THONG TIN THỐNG KÊ
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

    //LOAD DANH MUC CON PHU HOP VOI DANH MUC CHA
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
       if(isset($_POST["action"]) && $_POST["action"] == "displaySubcategories")
       {
            $categoryID = $_POST["categoryID"];
            $sql = "SELECT id FROM subcategories WHERE categoriy_ID = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $categoryID);
            $statement->execute();
            $subcategoriesID = $statement->fetchAll();
            ob_clean();
            foreach($subcategoriesID as $subcategoryID)
            {
                echo '<option value="'.$subcategoryID["id"].'">'.$subcategoryID["id"].'</option>';
            }
            exit();
       }
    }
    
    //TIM KIEM SAN PHAM
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
                    <td>'.number_format($product["price"], 0, ',', '.').' VNĐ</td>
                    <td class="nowrap">
                        <button id="update-btn" onclick="display(); displayProductDetails(\''.$product["id"].'\', \'update\')"><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                        <button type="button" onclick="deleteProduct(\''.$product["id"].'\')"><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                        <button type="button" onclick="display();displayProductDetails(\''.$product["id"].'\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                    </td></tr>';
            }
            exit();
        }
    }

    //XEM SAN PHAM
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["productID"]) && $_POST["action"] == "showProduct")
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
                <input disabled type="text" value="'.$product["id"].'">
            </div>
            <div class="properties">
                <label for="">Tên sản phẩm</label>
                <input id="name" type="text" value="'.$product["name"].'">
            </div>
            
            <div class="properties">
                <label for="">Giá</label>
                <input id="price" type="text" value="'.number_format($product["price"], 0, ',', '.').'"> VNĐ
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
                $defaultSizes = ['S', 'M', 'L', 'XL']; // Danh sách size mặc định

                foreach ($defaultSizes as $size) { // Duyệt qua từng size mặc định
                    $checked = in_array($size, $sizes) ? 'checked' : ''; // Nếu có trong $sizes thì checked
                    echo '<input type="checkbox" name="size" value="'.$size.'" '.$checked.'> '.$size.' ';
                }
                    
              
                
            echo '</div>
    
            <div class="properties">
                <label for="">Số lượng</label>
                <input id="quantity" type="text" value="'.$product["quantity"].'">
            </div>
            <div class="properties">
                <label for="">Danh mục</label>
                <select id="categories" onchange="displaySubcategories()">';
                $sql = "select id from categories";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $categoriesData = $statement->fetchAll();
                foreach($categoriesData as $cate)
                {
                    if($cate["id"] == $product["category_id"])
                    {
                        echo '<option value="'.$cate["id"].'" selected>'.$cate["id"].'</option>';
                    }
                    else
                    {
                        echo '<option value="'.$cate["id"].'">'.$cate["id"].'</option>';
                    }
                    
                }
            echo 
            '</select>
            </div>
            <div class="properties">
                <label for="">Danh mục con</label>
                <select id="subcategories">
                    <option value="'.$product["subcategory_id"].'">'.$product["subcategory_id"].'</select>
                </select>
            </div>
            <div class="properties">
                <label for="">Mô tả</label>
                <textarea id="describe" type="text" rows="8" >Áo thun Line với đường nét đánh bông tạo điểm nhấn nhẹ nhàng nhưng đậm chất sporty. Form dáng regular rộng rãi, phù hợp với nhiều vóc dáng. Chất liệu cotton nguyên chất 250gsm, tạo cảm giác dễ chịu, thấm hút mồ hôi tốt và thoáng mát. </textarea>
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
                <button id="update-btn" class="btn" type="button" onclick="updateProduct(\''.$product["id"].'\')"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
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
            $name = $_POST["productName"];
            $price = $_POST["productPrice"];
            $hot = $_POST["productHot"] == True ? 1 : 0;
            $size = $_POST["productSize"];
            $describe = $_POST["productDescribe"];
            $quantity = $_POST['productQuantity'];
            $categoryID = $_POST["categoryID"];
            $subcategoryID = $_POST["subcategoryID"];
            $id = $subcategoryID.substr(md5(uniqid(true)), 0, 7);
            $sql = "insert into products(id, name, price, hot, size, `describe`, quantity, category_id, subcategory_id) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $id);
            $statement->bindParam(2, $name);
            $statement->bindParam(3, $price);
            $statement->bindParam(4, $hot);
            $statement->bindParam(5, $size);
            $statement->bindParam(6, $describe);
            $statement->bindParam(7, $quantity);
            $statement->bindParam(8, $categoryID);
            $statement->bindParam(9, $subcategoryID);
            $statement->execute();

            $path = "../images/products/";
            ob_clean();
            foreach($_FILES["productImages"]["tmp_name"] as $key => $tmp_name)
            {
                $fileName = basename($_FILES["productImages"]["name"][$key]);
                $filePath = $path.$fileName;
                if(move_uploaded_file($tmp_name, $filePath))
                {
                    $sql = "insert into product_images(image, product_id) values (?, ?)";
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(1, $fileName);
                    $statement->bindParam(2, $id);
                    $statement->execute();
                    
                    echo "thêm thanh công ảnh ".$fileName;
                }
                else
                {
                    echo "lỗi khi thêm ".$fileName;
                }    
            }
            $sql = "update product_images set is_main = 1 where id = (select min(id) from product_images where product_id = ?)";
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(1, $id);
                    $statement->execute();
            
            echo "thanh cong";
            exit();
        }
    }

    //XOA SAN PHAM
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "deleteProduct")
        {
            ob_clean();
            $productID = $_POST["productID"];
            try
            {
                $sql = "delete from products where id = ?";
                $statement = $connection->prepare($sql);
                $statement->bindParam(1, $productID);
                $statement->execute();
                $respone["success"] = 1;
                $respone["message"] = "Xóa thành công";
            }
            catch(Exception $e)
            {
                $respone["success"] = 0;
                $respone["message"] = "xoa that bai". $e->getMessage();
            }
            
            echo json_encode($respone);
            exit();
        }
    }

    //SUA SAN PHAM
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["productID"]) && $_POST["action"] == "updateProduct")
        {

            $name = $_POST["productName"];
            $price = str_replace('.', '', $_POST['productPrice']);
            $hot = $_POST["productHot"] == True ? 1 : 0;
            $size = $_POST["productSize"];
            $describe = $_POST["productDescribe"];
            $quantity = $_POST['productQuantity'];
            $categoryID = $_POST["categoryID"];
            $subcategoryID = $_POST["subcategoryID"];
            $id = $_POST["productID"];

            $sql = 'UPDATE products 
            SET NAME = ?, `DESCRIBE` = ?, price = ?, hot = ?, size = ?, quantity = ?, category_id = ?, subcategory_id = ?
            WHERE id = ?';

            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $name);
            $statement->bindParam(2, $describe);
            $statement->bindParam(3, $price);
            $statement->bindParam(4, $hot);
            $statement->bindParam(5, $size);
            $statement->bindParam(6, $quantity);
            $statement->bindParam(7, $categoryID);
            $statement->bindParam(8, $subcategoryID);
            $statement->bindParam(9, $id);
            $statement->execute();

            ob_clean();
            echo "update thanh cong ".$id;
            exit();
        }
    }

    //XEM NGUOI DUNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "showCostomer")
        {
            $userID = $_POST["userID"];
            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $userID);
            $statement->execute();
            $userData = $statement->fetch();
            ob_clean();
            echo 
            '   <h3>Thông tin khách hàng</h3>
                <div class="properties">
                    <label for="">Mã khách hàng</label>
                    <input id="id" type="text" value="'.$userData["id"].'">
                </div>
                <div class="properties">
                    <label for="">Tên khách hàng</label>
                    <input id="name" type="text" value="'.$userData["fullName"].'">
                </div>
                <div class="properties">
                    <label for="">Tên đăng nhập</label>
                    <input id="username" type="text" value="'.$userData["username"].'">     
                </div>
                <div class="properties">
                    <label for="">Mật khẩu</label>
                    <input id="password" type="text" value="'.$userData["password"].'">
                </div>
                <div class="properties">
                    <label for="">Email</label>
                    <input id="email" type="text" value="'.$userData["email"].'">
                </div>
                <div class="properties">
                    <label for="">Số điện thoại</label>
                    <input id="phone" type="text" value="'.$userData["phone"].'">
                </div>
                <div class="properties">
                    <label for="">Địa chỉ</label>
                    <textarea id="address" type="text" rows="5" >'.$userData["address"].'</textarea>
                </div>
                <div class="properties" id="image-container">
                    <label for="">Hình ảnh</label>
                    <img id="current-image" src="../images/avata/'.$userData["image"].'" width="75px" height="75px">
                </div>
                <div class="properties">
                    <label for="">Ví</label>
                    <input id="wallet" type="text" value="'.number_format($userData["wallet"], 0, ',', '.').'"> VNĐ
                </div>
                <div class="properties">
                    <label for="">Thời gian tạo</label>
                    <input id="create-time" type="text" value="'.$userData["createTime"].'">
                </div>
                <div class="operation">
                    <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Hủy</button>
                    <button id="update-btn" class="btn" type="button" onclick="updateCostomer(\''.$userData["id"].'\')"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
                </div>
                    ';
            
            exit();
        }
    }

    //THEM NGUOI DUNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "addCostomer")
        {
            $fullName = $_POST["fullName"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $wallet = $_POST["wallet"];
            $image = $_FILES["image"];

            $sql = 'INSERT INTO users(fullName, username, `password`, email, phone, address, image, wallet)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $fullName);
            $statement->bindParam(2, $username);
            $statement->bindParam(3, $password);
            $statement->bindParam(4, $email);
            $statement->bindParam(5, $phone);
            $statement->bindParam(6, $address);
            $statement->bindParam(7, $image["name"]);
            $statement->bindParam(8, $wallet);
            $statement->execute();

            $path = "../images/avata/";
            $fileName = basename($image["name"]);
            $filePath = $path.$fileName;
            move_uploaded_file($image["tmp_name"], $filePath);
            ob_clean();
            echo "Them thanh cong";
            exit();
        }
    }

    //XOA NGUOI DUNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "deleteCostomer")
        {
            $userID = $_POST["userID"];
            $sql = "DELETE FROM users WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $userID, PDO::PARAM_INT); 
            $statement->execute();
            ob_clean();
            echo $userID;
            exit();
        }
    }

    //SUA NGUOI DUNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "updateCostomer")
        {
            $fullName = $_POST["fullName"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $wallet = str_replace('.', '', $_POST["wallet"]);
            $id = $_POST["userID"];

            $sql = "UPDATE users
                    SET fullName = ?, username = ?, `PASSWORD` = ?,
                    email = ?, phone = ?, address = ?, wallet = ?
                    WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $fullName);
            $statement->bindParam(2, $username);
            $statement->bindParam(3, $password);
            $statement->bindParam(4, $email);
            $statement->bindParam(5, $phone);
            $statement->bindParam(6, $address);
            $statement->bindParam(7, $wallet);
            $statement->bindParam(8, $id);
            $statement->execute();
            
            if(isset($_FILES["image"]))
            {
                $sql = "update users set image = ? where id = ?";
                $statement = $connection->prepare($sql);
                $statement->bindParam(1, $_FILES["image"]["name"]);
                $statement->bindParam(2, $id);
                $statement->execute();

                $path = "../images/avata/";
                $fileName = basename(($_FILES["image"]["name"]));
                $filePath = $path.$fileName;
                move_uploaded_file($_FILES["image"]["tmp_name"], $filePath);
            }
            ob_clean();
            echo "Da sua thanh cong ".$id;
            exit();
        }
    }

    //TIM KIẾM NGƯỜI DÙNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["searchCostomer"]))
        {
            $searchQuery = "%".$_POST["searchCostomer"]."%";
            $sql = "select * from users where username like ? or fullName like ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $searchQuery);
            $statement->bindParam(2, $searchQuery);
            $statement->execute();
            $Data = $statement->fetchAll();
            ob_clean();
            echo '<tr><th>Mã người dùng</th>
                        <th>Tên đăng nhập</th>
                        <th>Mật khẩu</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ví</th></tr>';
                        foreach($Data as $user)
                        {
                        echo '<tr>
                        <td>'.$user["id"].'</td>
                        <td>'.$user["username"].'</td>
                        <td>'.$user["password"].'</td>
                        <td>'.$user["phone"].'</td>
                        <td>'.$user["address"].'</td>
                        <td>'.number_format($user["wallet"], 0, ',', '.').' VNĐ</td>
                        <td class="nowrap">
                            <button onclick="displayCostomerDetails(\''.$user["id"].'\', \'updateCostomer\')"><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                            <button type="button" onclick="deleteCostomer('.$user["id"].', this)"><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                            <button onclick="displayCostomerDetails(\''.$user["id"].'\', \'showCostomer\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                        </td>
                        </tr>';
                        }
            exit();
        }
    }
    
    //XEM DƠN HÀNG
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "showOrder")
        {
            $orderID = $_POST["orderID"];
            $sql = "select orders.*, payments.status as paymentStatus, payments.method 
            FROM orders LEFT JOIN payments ON orders.id = payments.order_ID
            WHERE orders.id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->execute();
            $order = $statement->fetch();

            $sql = "SELECT order_items.* , products.`name`
            FROM order_items LEFT JOIN products ON order_items.product_ID = products.id
            WHERE order_items.order_ID = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID);
            $statement->execute();
            $orderItems = $statement->fetchAll();
            ob_clean();
            echo '<h3>Thông tin đơn hàng</h3>
                    <div class="properties">
                        <label for="">Mã đơn hàng</label>
                        <input id="id" type="text" value="'.$order["id"].'" disabled>
                    </div>
                    <div class="properties">
                        <label for="">Mã người dùng</label>
                        <input id="user-id" type="text" value="'.$order["user_ID"].'" disabled>     
                    </div>
                    <div class="properties">
                        <label for="">Giá</label>
                        <input id="price" type="text" value="'.number_format($order["price"], 0, ',', '.').'"> VNĐ
                    </div>
                    <div class="properties">
                        <label for="">Trạng thái đơn</label>
                        <select id="status">';
                            $status = ["Chờ xác nhận", "Đang giao", "Giao hàng thành công", "Đã hủy"];
                            foreach($status as $st)
                            {
                                if($st == $order["status"])
                                {
                                    echo '<option value="'.$order["status"].'" selected>'.$order["status"].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$st.'">'.$st.'</option>';
                                }
                                
                            }     
                        echo '</select>
                        
                    </div>
                    <div class="properties">
                        <label for="">Thời gian tạo</label>
                        <input id="create-time" type="text" value="'.$order["create_time"].'" disabled>
                    </div>
                    <div class="properties">
                        <label for="">Phương thức thanh toán</label>
                        <select id="method">';
                            $method = ["cod", "wallet"];
                            foreach($method as $mt)
                            {
                                if($mt == $order["method"])
                                {
                                    echo '<option value="'.$order["method"].'" selected>'.$order["method"].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$mt.'">'.$mt.'</option>';
                                }
                                
                            }     
                        echo '</select>
                    </div>
                    <div class="properties">
                        <label for="">Trạng thái thanh toán</label>
                        <select id="payment-status">';
                            $paymentStatus = ["Đã thanh toán", "Chưa thanh toán"];
                            foreach($paymentStatus as $pst)
                            {
                                if($pst == $order["paymentStatus"])
                                {
                                    echo '<option value="'.$order["paymentStatus"].'" selected>'.$order["paymentStatus"].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$pst.'">'.$pst.'</option>';
                                }
                                
                            }     
                        echo '</select>
                    </div>
                    <div class="product-container-properties">
                    <b>Các sản phẩm</b>
                        <table>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Kích cở</th>
                                <th>Giá</th>
                            </tr>';
                            foreach($orderItems as $item)
                            {
                                echo '
                                <tr>
                                    <td>'.$item["name"].'</td>
                                    <td>'.$item["quantity"].'</td>
                                    <td>'.$item["size"].'</td>
                                    <td>'.number_format($item["price"], 0, ',', '.').' VNĐ</td>
                                </tr>';
                            }
                            
                        echo '</table>
                    </div>
                    <div class="operation">
                        <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Hủy</button>
                        <button class="btn" id="update-btn" type="button" onclick="updateOrder(\''.$order["id"].'\')"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
                    </div>';
            exit();
        }
    }

    //SUA DON HANG
    if($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST["action"]) && $_POST["action"] == "updateOrder")
        {
            $orderID = $_POST["orderID"];
            $price = str_replace('.', '', $_POST["orderPrice"]);
            $status = $_POST["orderStatus"];
            $method = $_POST["orderMethod"];
            $paymentStatus = $_POST["paymentStatus"];

            $sql = "update orders 
            SET price = ?, `status` = ?
            WHERE id = ?
            ";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $price);
            $statement->bindParam(2, $status);
            $statement->bindParam(3, $orderID);
            $statement->execute();

            $sql = "update payments set method = ?, status = ? where order_ID = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $method);
            $statement->bindParam(2, $paymentStatus);
            $statement->bindParam(3, $orderID);
            $statement->execute();

            ob_clean();
            echo "Cap nhat thanh cong";
            exit();
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["action"]) && $_POST["action"] == "deleteOrder")
        {
            $orderID = $_POST["orderID"];
            $sql = "DELETE FROM orders WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->bindParam(1, $orderID, PDO::PARAM_INT); 
            $statement->execute();
            ob_clean();
            echo $orderID;
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
            <img src="../images/avata/<?php echo $_SESSION["image"] ?>" alt="Avata" style="width: 100px; height: 100px;">
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
            if(!isset($_GET["page"]))// LOAD NỘI DUNG CỦA TRANG THỐNG KÊ
            {
                echo '
                <h3>Thông tin chung</h3>
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
                    echo '
                    <tr>
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
            if(isset($_GET["page"]) && $_GET["page"] == "order") // LOAD NỘI DUNG CỦA TRANG QLORDER
            {
                echo '
                
                <h3>Danh sách đơn hàng</h3>
                <label for="search">
                    Tìm kiếm <input type="text" name="search" id="search" onkeyup=""> 
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
                    <button onclick="displayOrderDetails(\''.$order["id"].'\', \'update\')"><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                    <button onclick="deleteOrder(\''.$order["id"].'\', this)"><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                    <button  onclick="displayOrderDetails(\''.$order["id"].'\', \'show\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                </td>
                </tr>';
                }
            }
            if(isset($_GET["page"]) && $_GET["page"] == "costomer")// LOAD NỘI DUNG CỦA TRANG QLKH
            {
                echo '
                <div>
                    <div class="add-product">
                        <button class="nowrap" type="button" onclick="display(); clearCostomer()" id="add-product-btn"><i class="fa-solid fa-plus"></i><p>Thêm người dùng</p></button>
                    </div>
                </div> 
                <h3>Danh sách người dùng</h3>
                <label for="search">
                    Tìm kiếm <input type="text" name="search" id="search" onkeyup="searchCostomer()"> 
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
                <td>'.number_format($user["wallet"], 0, ',', '.').' VNĐ</td>
                <td class="nowrap">
                    <button onclick="displayCostomerDetails(\''.$user["id"].'\', \'updateCostomer\')"><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                    <button type="button" onclick="deleteCostomer('.$user["id"].', this)"><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                    <button onclick="displayCostomerDetails(\''.$user["id"].'\', \'showCostomer\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                </td>
                </tr>';
                }
            }
            if(isset($_GET["page"]) && $_GET["page"] == "product")// LOAD NỘI DUNG CỦA TRANG QLSP
            {
                displayProducts($Data);
            }
                function displayProducts($Data)
                {
                    echo '<div>
                    <div class="add-product">
                        
                            <button class="nowrap" type="button" onclick="clearProduct(); display();" id="add-product-btn"><i class="fa-solid fa-plus"></i><p>Thêm sản phẩm mới</p></button>
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
                        <td>'.number_format($product["price"], 0, ',', '.').' VNĐ</td>
                        <td class="nowrap">
                            <button type="button" onclick="display(); displayProductDetails(\''.$product["id"].'\', \'update\')"><i class="fa-solid fa-wrench" title="Chỉnh sửa"></i></button>
                            <button type="button" onclick="deleteProduct(\''.$product["id"].'\', this)"><i class="fa-solid fa-trash" title="Xóa sản phẩm"></i></button>
                            <button onclick="display(); displayProductDetails(\''.$product["id"].'\', \'showProduct\')"><i class="fa-solid fa-eye" title="Xem chi tiết"></i></button>
                        </td>
                        </tr>';
                    }
                }
                
                
        ?>
        </table>
        
    </div>
    <form action="includes/admin.php" enctype="multipart/form-data">
            <?php
            if(isset($_GET["page"]) && $_GET["page"] == "product") //LOAD NOI DUNG FORM PRODUCT
            {
                $sql = "select id from subcategories";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $subCategoriesData = $statement->fetchAll();
                $sql = "select id from categories";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $categoriesData = $statement->fetchAll();
                
                echo '
                <h3>Thông tin sản phẩm</h3>
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
                    <select id="categories" name="categories" onchange="displaySubcategories()">
                        <option value="" disabled selected>Chọn danh mục</option>';
                foreach($categoriesData as $cate)
                {
                    echo '<option value="'.$cate["id"].'">'.$cate["id"].'</option>';
                }
                echo '</select>
                </div>
                <div class="properties">
                    <label for="">Danh mục con</label>
                    <select id="subcategories" name="subcategories">
                        <option value="">Vui lòng chọn danh mục</option>
                    </select>
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
            
            if(isset($_GET["page"]) && $_GET["page"] == "costomer") // LOAD NOI DUNG FORM KHACH HANG
            {
                echo '
                <h3>Thông tin khách hàng</h3>
                <div class="properties">
                    <label for="">Tên khách hàng</label>
                    <input id="name" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Tên đăng nhập</label>
                    <input id="username" type="text">     
                </div>
                <div class="properties">
                    <label for="">Mật khẩu</label>
                    <input id="password" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Email</label>
                    <input id="email" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Số điện thoại</label>
                    <input id="phone" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Địa chỉ</label>
                    <textarea id="address" type="text" rows="5" ></textarea>
                </div>
                <div class="properties">
                    <label for="">Hình ảnh</label>
                    <input id="image" type="file" accept="image/*">
                </div>
                <div class="properties">
                    <label for="">Ví</label>
                    <input id="wallet" type="text" value="">
                </div>
                <div class="operation">
                    <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Hủy</button>
                    <button class="btn" type="button" onclick="addCostomer()"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
                </div>
                    ';
            }

            if(isset($_GET["page"]) && $_GET["page"] == "costomer") // LOAD NOI DUNG FORM ORDERS
            {
                echo '
                <h3>Thông tin đơn hàng</h3>
                <div class="properties">
                    <label for="">Mã đơn hàng</label>
                    <input id="id" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Mã người dùng</label>
                    <input id="user-id" type="text">     
                </div>
                <div class="properties">
                    <label for="">Giá</label>
                    <input id="price" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Trạng thái đơn</label>
                    <input id="status" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Thời gian tạo</label>
                    <input id="create-time" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Phương thức thanh toán</label>
                    <input id="method" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Trạng thái thanh toán</label>
                    <input id="payment-status" type="text" value="">
                </div>
                <div class="properties">
                    <label for="">Ví</label>
                    <input id="wallet" type="text" value="">
                </div>
                <div class="operation">
                    <button class="btn" type="button" onclick="undisplay()"><i class="fa-solid fa-xmark"></i> Hủy</button>
                    <button class="btn" type="button" onclick=""><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
                </div>
                    ';
            }
            ?>
    </form>
    <script src="../js/admin.js"></script>
</body>
</html>