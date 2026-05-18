<?php
require_once '../../Vista/core.php';
$valid['success'] = array('success' => false, 'messages' => array());
if($_POST) {
    $productName    = isset($_POST['productName']) ? trim($_POST['productName']) : '';
    $quantity       = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $rate           = isset($_POST['rate']) ? $_POST['rate'] : '';
    $brandName      = isset($_POST['brandName']) ? (int)$_POST['brandName'] : 0;
    $categoryName   = isset($_POST['categoryName']) ? (int)$_POST['categoryName'] : 0;
    $productStatus  = isset($_POST['productStatus']) ? (int)$_POST['productStatus'] : 0;

    if(empty($productName) || empty($quantity) || empty($rate) || $brandName <= 0 || $categoryName <= 0 || ($productStatus !== 1 && $productStatus !== 2)) {
         $valid['success'] = false; $valid['messages'] = "Por favor, complete todos los campos correctamente.";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
         $valid['success'] = false; $valid['messages'] = "La cantidad debe ser un número positivo.";
    } elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $rate) || $rate < 0) {
         $valid['success'] = false; $valid['messages'] = "El precio debe ser un número válido (ej: 1200.00).";
    } else {
        $url = '';
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
            $type = explode('.', $_FILES['productImage']['name']); $type = strtolower(end($type));
            $uploadDir = '../../assests/images/stock/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid(rand()) . '.' . $type;
            $url = $uploadDir . $fileName;
            $dbUrl = 'assests/images/stock/' . $fileName;
            if(in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
                if(!move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {
                    $valid['success'] = false; $valid['messages'] = "Error al subir la imagen.";
                    $connect->close(); header('Content-Type: application/json'); echo json_encode($valid); exit();
                } $url = $dbUrl;
            } else {
                 $valid['success'] = false; $valid['messages'] = "Tipo de imagen no permitido.";
                 $connect->close(); header('Content-Type: application/json'); echo json_encode($valid); exit();
            }
        } else { $url = '../../assests/images/stock/default.png'; }

        $productNameEsc = $connect->real_escape_string($productName);
        $urlEsc = $connect->real_escape_string($url);
        $sql = "INSERT INTO product (product_name, product_image, brand_id, categories_id, quantity, rate, active, status)
                VALUES ('$productNameEsc', '$urlEsc', $brandName, $categoryName, '$quantity', '$rate', $productStatus, 1)";
        if($connect->query($sql) === TRUE) {
            $valid['success'] = true; $valid['messages'] = "Producto agregado exitosamente";
        } else { $valid['success'] = false; $valid['messages'] = "Error al agregar el producto: " . $connect->error; }
    }
    $connect->close(); header('Content-Type: application/json'); echo json_encode($valid);
} else { /* Error método no permitido */ }
?>