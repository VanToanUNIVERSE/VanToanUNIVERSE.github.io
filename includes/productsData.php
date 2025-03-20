<?php
    $sql = "SELECT products.*, product_images.image 
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id and product_images.is_main = 1
        GROUP BY products.id";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $productList = $statement->fetchAll(); //dulieu cua danh sach san pham 
?>