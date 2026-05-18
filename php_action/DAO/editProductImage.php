<?php
require_once '../../Vista/core.php';

// --- CORRECCIÓN 1: Definir la estructura de respuesta por defecto ---
$valid = array('success' => false, 'messages' => array(), 'imgPath' => '');

// --- CORRECCIÓN 2: El 'if' principal ahora comprueba los datos ---
if($_POST && isset($_POST['editProductImageId'])) {
    $productId = (int)$_POST['editProductImageId'];
    
    if ($productId <= 0) { 
        $valid['success'] = false; 
        $valid['messages'][] = "ID de producto inválido."; 
    } else {
        if (isset($_FILES['editProductImage'])) {
            // Verificar si hay error en la subida
            if ($_FILES['editProductImage']['error'] != 0) {
                $valid['success'] = false;
                $valid['messages'][] = "Error al subir el archivo (Código: " . $_FILES['editProductImage']['error'] . ")";
                
                // Salir del script aquí si hay error de subida
                $connect->close(); 
                header('Content-Type: application/json'); 
                echo json_encode($valid);
                exit;
            }
            
            $type = explode('.', $_FILES['editProductImage']['name']); 
            $type = strtolower(end($type));
            $uploadDir = '../../assests/images/stock/';
            
            // Verificar y crear el directorio si no existe
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    $valid['success'] = false;
                    $valid['messages'][] = "Error al crear el directorio de destino.";
                    
                    $connect->close(); 
                    header('Content-Type: application/json'); 
                    echo json_encode($valid);
                    exit;
                }
            }
            
            // Verificar permisos de escritura
            if (!is_writable($uploadDir)) {
                $valid['success'] = false;
                $valid['messages'][] = "El directorio de destino no tiene permisos de escritura.";
                
                $connect->close(); 
                header('Content-Type: application/json'); 
                echo json_encode($valid);
                exit;
            }
            
            $fileName = uniqid(rand()) . '.' . $type;
            $url = $uploadDir . $fileName; 
            $dbUrl = 'assests/images/stock/' . $fileName;
            
            if(in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
                // Validar tamaño del archivo (máximo 5MB)
                if ($_FILES['editProductImage']['size'] > 5242880) {
                    $valid['success'] = false;
                    $valid['messages'][] = "El archivo es demasiado grande. Máximo 5MB permitido.";
                    
                    $connect->close(); 
                    header('Content-Type: application/json'); 
                    echo json_encode($valid);
                    exit;
                }

                // Obtener la imagen anterior
                $sql = "SELECT product_image FROM product WHERE product_id = $productId";
                $result = $connect->query($sql);
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $old_image = '../' . $row['product_image'];
                    if(file_exists($old_image) && $row['product_image'] != '') {
                        @unlink($old_image); // Usar @ para suprimir errores si el archivo no existe
                    }
                }

                // Mover el nuevo archivo
                if(move_uploaded_file($_FILES['editProductImage']['tmp_name'], $url)) {
                    // Verificar que el archivo se movió correctamente
                    if(file_exists($url)) {
                        $sql_update_img = "UPDATE product SET product_image = '$dbUrl' WHERE product_id = $productId";
                        if($connect->query($sql_update_img) === TRUE) {
                            $valid['success'] = true;
                            $valid['messages'][] = "Imagen actualizada exitosamente.";
                            $valid['imgPath'] = $dbUrl;
                        } else {
                            $valid['success'] = false;
                            $valid['messages'][] = "Error al actualizar BD: " . $connect->error;
                            if (file_exists($url)) @unlink($url); // Borrar el archivo subido si falla la BD
                        }
                    } else {
                        $valid['success'] = false;
                        $valid['messages'][] = "Error: El archivo no se guardó correctamente.";
                    }
                } else {
                    $valid['success'] = false;
                    $error = error_get_last();
                    $valid['messages'][] = "Error al mover archivo: " . ($error ? $error['message'] : 'Desconocido');
                }
            } else {
                $valid['success'] = false;
                $valid['messages'][] = "Tipo de archivo no permitido. Solo se permiten archivos .gif, .jpg, .jpeg y .png";
            }
        } else { 
            $valid['success'] = false; 
            $valid['messages'][] = "No se seleccionó imagen o hubo un error en la subida."; 
        }
    }
} else { 
    // --- CORRECCIÓN 3: El 'else' que faltaba ---
    $valid['success'] = false;
    $valid['messages'][] = "Error de solicitud: No se recibieron los datos esperados (ID de producto).";
}

// --- CORRECCIÓN 4: Enviar la respuesta JSON siempre al final ---
$connect->close(); 
header('Content-Type: application/json'); 
echo json_encode($valid);
?>