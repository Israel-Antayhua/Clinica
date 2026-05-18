<?php
require_once '../../Vista/core.php';
$productId = isset($_POST['productId']) ? (int)$_POST['productId'] : 0;
$row = null;
if($productId > 0) {
    $sql = "SELECT product_id, product_name, 
            CONCAT('../', product_image) as product_image, 
            brand_id, categories_id, quantity, rate, active
            FROM product WHERE product_id = $productId AND status = 1";
    $result = $connect->query($sql);
    if($result && $result->num_rows > 0) { $row = $result->fetch_assoc(); }
}
$connect->close(); header('Content-Type: application/json'); echo json_encode($row);
?>