<?php
// Conectar a la BD sin forzar redirección de sesión (evita enviar HTML en respuestas AJAX)
require_once '../../Vista/core.php';

// Evitar salida accidental (warnings/notices) que rompan el JSON
ob_start();

// ** CONSULTA SQL MODIFICADA **
// Se añaden LEFT JOINs para buscar un ticket asociado en 'ticket_order' y 'ticket'
$sql = "SELECT
            o.order_id,
            o.order_date,
            o.client_name,
            o.client_contact,
            o.payment_status,
            tor.ticket_id,
            t.serie AS ticket_serie
        FROM
            orders o
        LEFT JOIN
            ticket_order tor ON o.order_id = tor.order_id
        LEFT JOIN
            ticket t ON tor.ticket_id = t.id
        WHERE
            o.order_status = 1
        ORDER BY
            o.order_id DESC"; // Ordenar por ID descendente

// Ejecutar consulta principal (con JOINs). Si falla, intentar consulta de reserva sin JOINs
$output = array('data' => array());

$result = $connect->query($sql);
if ($result === false) {
    // Error en la consulta SQL con JOINs (tabla ticket/ticket_order puede no existir)
    error_log("fetchOrder.php - SQL error (main query): " . $connect->error);
    // Intentar consulta de reserva sólo con orders
    $fallbackSql = "SELECT o.order_id, o.order_date, o.client_name, o.client_contact, o.payment_status FROM orders o WHERE o.order_status = 1 ORDER BY o.order_id DESC";
    $result = $connect->query($fallbackSql);
    if ($result === false) {
        // Falla también la consulta de reserva: devolver error JSON (no HTML)
        error_log("fetchOrder.php - SQL error (fallback query): " . $connect->error);
        http_response_code(500);
        // Limpiar buffer de salida antes de enviar JSON
        $buf = ob_get_clean();
        if (!empty($buf)) {
            error_log("fetchOrder.php - Unexpected output before JSON: " . $buf);
        }
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database query failed'));
        $connect->close();
        exit();
    }
    // Si fallback funciona, añadimos una advertencia en el log
    error_log("fetchOrder.php - Using fallback query without ticket JOINs.");
}

// Si llegamos aquí, $result es un mysqli_result válido (puede ser vacío)
if($result && $result->num_rows > 0) { // Comprobar si la consulta fue exitosa

    $paymentStatus = "";
    $x = 1; // Contador para la columna #

    while($row = $result->fetch_assoc()) { // Usar fetch_assoc para nombres de columna
        $orderId = $row['order_id'];

        // Contar el total de items para este pedido
        $countOrderItemSql = "SELECT count(*) as total_items FROM order_item WHERE order_id = $orderId";
        $itemCountResult = $connect->query($countOrderItemSql);
        $itemCount = ($itemCountResult && $itemCountResult->num_rows > 0) ? $itemCountResult->fetch_assoc()['total_items'] : 0;

        // Definir la etiqueta de estado de pago (traducida)
        if($row['payment_status'] == 1) {
            $paymentStatus = "<label class='label label-success'>Pago Completo</label>";
        } else if($row['payment_status'] == 2) {
            $paymentStatus = "<label class='label label-warning'>Pago Parcial</label>";
        } else { // Asumir 3 o cualquier otro valor como Pendiente
            $paymentStatus = "<label class='label label-danger'>Pendiente</label>";
        }

        // ** NUEVO: Generar la celda de "Ticket Asociado" **
        $ticketInfo = "";
        if (!empty($row['ticket_id'])) {
            // Si hay un ticket, mostrar un enlace a la página de edición de ticket
            $ticketInfo = '<a href="ticket_edit.php?id='.$row['ticket_id'].'" target="_blank" class="btn btn-xs btn-default" data-toggle="tooltip" title="Ver Ticket">
                              <i class="glyphicon glyphicon-tag"></i> '.htmlspecialchars($row['ticket_serie'] ?: 'ID: '.$row['ticket_id']).'
                           </a>';
        } else {
            // Si no hay ticket, mostrar "N/A" (No Aplica)
            $ticketInfo = '<span class="text-muted">N/A</span>';
        }
        // ** FIN NUEVO **

        // Botón de acciones (traducido en los tooltips/texto)
        $button = '<div class="btn-group">
          <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acción <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="orders.php?o=editOrd&i='.$orderId.'" id="editOrderModalBtn'.$orderId.'"> <i class="glyphicon glyphicon-edit"></i> Editar</a></li>
            <li><a type="button" data-toggle="modal" id="paymentOrderModalBtn'.$orderId.'" data-target="#paymentOrderModal" onclick="paymentOrder('.$orderId.')"> <i class="glyphicon glyphicon-save"></i> Registrar Pago</a></li>
            <li><a type="button" onclick="printOrder('.$orderId.')"> <i class="glyphicon glyphicon-print"></i> Imprimir</a></li>
            <li><a type="button" data-toggle="modal" data-target="#removeOrderModal" id="removeOrderModalBtn'.$orderId.'" onclick="removeOrder('.$orderId.')"> <i class="glyphicon glyphicon-trash"></i> Eliminar</a></li>
          </ul>
        </div>';

        // ** MODIFICADO: Añadido $ticketInfo al array de salida **
        $output['data'][] = array(
            $x, // #
            htmlspecialchars(date("d/m/Y", strtotime($row['order_date']))), // Fecha Pedido (formateada)
            htmlspecialchars($row['client_name']),       // Nombre Cliente
            htmlspecialchars($row['client_contact']),    // Contacto
            $itemCount,                                 // Total Items
            $paymentStatus,                             // Estado Pago
            $ticketInfo,                                // <-- NUEVA COLUMNA DE DATOS (índice 6)
            $button                                     // Opción (índice 7)
            );
        $x++;
    } // /while

} // /if num_rows

$connect->close();
header('Content-Type: application/json');
echo json_encode($output);
?>