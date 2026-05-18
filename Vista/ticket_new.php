<?php 
require_once '../includes/header.php';
require_once '../php_action/db_connect.php';
require_once '../php_action/DAO/GoCorreo.php';

// Seguridad: Solo usuarios logueados
if ( !isset($_SESSION['userId']) ) {
    echo '<div class="alert alert-danger text-center">Debe iniciar sesión para crear un ticket.</div>';
    require_once '../includes/footer.php';
    exit();
}

$error_message = ''; // Para mostrar errores

// --- Lógica para procesar el formulario (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Iniciar Transacción
    $connect->autocommit(FALSE);
    $success = true;
    $new_ticket_id = 0;
    
    // Capturar el order_id del campo oculto (puede ser 0)
    $order_id_to_link = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

    // 1. Recoger datos y preparar para 'ticket'
    $fecha_actual = date("Y-m-d H:i:s");
    $serie_ticket = "TKT-" . date("YmdHis");
    $estado_ticket = "Pendiente";
    $user_id_asignado = (isset($_POST['user_id_asignado']) && $_POST['user_id_asignado'] != 'NULL' && $_POST['user_id_asignado'] > 0) ? (int)$_POST['user_id_asignado'] : null;
    $client_name = $_POST['client_name_ticket'];
    $client_contact = $_POST['client_contact_ticket'];
    $departamento = $_POST['departamento_ticket'];
    $asunto = $_POST['asunto_ticket'];
    $mensaje = $_POST['mensaje_ticket'];
    $solucion = ""; 

    // 2. Insertar en 'ticket' con Prepared Statements
    $sql_insert_ticket = "INSERT INTO ticket 
                            (fecha, serie, estado_ticket, user_id_asignado, client_name_ticket, client_contact_ticket, departamento, asunto, mensaje, solucion)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $connect->prepare($sql_insert_ticket);
    $stmt->bind_param("sssissssss", 
        $fecha_actual, $serie_ticket, $estado_ticket, $user_id_asignado,
        $client_name, $client_contact, $departamento, $asunto, $mensaje, $solucion
    );

    if ($stmt->execute()) {
        $new_ticket_id = $connect->insert_id; // Obtenemos el ID del ticket recién creado
    } else {
        $success = false;
        $error_message = "Error al crear ticket: " . $stmt->error;
    }
    $stmt->close();

    // 3. 
    // Si no se pasó un order_id (es 0) Y SÍ tenemos un nombre de cliente,
    // buscamos el pedido MÁS RECIENTE de ese cliente.
    if ($success && $order_id_to_link == 0 && !empty($client_name)) {
        
        // --- CORRECCIÓN 1: Usar la columna correcta 'order_status' ---
        $sql_find_order = "SELECT order_id FROM orders WHERE client_name = ? AND order_status = 1 ORDER BY order_id DESC LIMIT 1";
        
        $stmt_find = $connect->prepare($sql_find_order);
        
        if ($stmt_find) {
            $stmt_find->bind_param("s", $client_name);
            $stmt_find->execute();
            $result_find = $stmt_find->get_result();
            
            if ($result_find->num_rows > 0) {
                $order_row = $result_find->fetch_assoc();
                $order_id_to_link = (int)$order_row['order_id']; // ¡Encontramos el ID del pedido!
            }
            $stmt_find->close();
        }
    }

    // 4. Insertar en 'ticket_order' si AHORA SÍ tenemos un order_id
    if ($success && $order_id_to_link > 0 && $new_ticket_id > 0) {
        
        $sql_insert_link = "INSERT INTO ticket_order (ticket_id, order_id) VALUES (?, ?)";
        $stmt_link = $connect->prepare($sql_insert_link);
        $stmt_link->bind_param("ii", $new_ticket_id, $order_id_to_link);
        
        if (!$stmt_link->execute()) {
            $success = false;
            $error_message = "Error al vincular ticket al pedido: " . $stmt_link->error;
        }
        $stmt_link->close();
    }

    // 5. Finalizar transacción y mostrar mensaje
    if ($success) {
        
        $connect->commit();
        if (isset($_POST['enviar_correo']) && $_POST['enviar_correo'] == 1) {
        enviarCorreoAsignacion($connect, $user_id_asignado, $serie_ticket, $asunto, $mensaje);
        }
        
        // Mensaje Éxito
        echo '
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center">¡Ticket Creado!</h4>
            <p class="text-center">El nuevo ticket para <strong>'.htmlspecialchars($client_name).'</strong> con serie <strong>' . $serie_ticket . '</strong> ha sido creado.</p>';
        
        if($order_id_to_link > 0) {
             echo '<p class="text-center">Y ha sido vinculado automáticamente al <strong>Pedido #'.$order_id_to_link.'</strong> (el más reciente de este cliente).</p>';
        } else {
             echo '<p class="text-center text-warning">No se encontró un pedido activo al cual vincular este ticket.</p>';
        }
        
        echo '<p class="text-center"><a href="tickets.php" class="alert-link">Volver a la lista de tickets</a></p>
        </div>
        ';

    } else {
        $connect->rollback(); // Revertir todos los cambios si algo falló
        
        // Mensaje Error
        echo '
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center">OCURRIÓ UN ERROR</h4>
            <p class="text-center">No se pudo crear el ticket: ' . htmlspecialchars($error_message) . '</p>
        </div>
        ';
    }
    
    $connect->autocommit(TRUE); // Restaurar autocommit

} else {
    // --- Si no es POST, mostramos el formulario ---

    $order_id_from_get = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
    $client_name_from_order = '';
    $client_contact_from_order = '';
    $asunto_from_order = '';

    if ($order_id_from_get > 0) {
        // --- CORRECCIÓN 2: Usar la columna correcta 'order_status' ---
        $sql_order_client = "SELECT client_name, client_contact FROM orders WHERE order_id = $order_id_from_get AND order_status = 1";
        $order_client_result = $connect->query($sql_order_client);
        
        if ($order_client_result && $order_client_result->num_rows > 0) {
            $order_data = $order_client_result->fetch_assoc();
            $client_name_from_order = $order_data['client_name'];
            $client_contact_from_order = $order_data['client_contact'];
            $asunto_from_order = "Referente al Pedido #" . $order_id_from_get;
        } else {
            $order_id_from_get = 0; // El pedido no existe o no está activo
        }
    }

    $users_list = [];
    $currentUserId = isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 0;
    if ($currentUserId === 1) {
        $sql_get_users = "SELECT user_id, username FROM users ORDER BY username ASC";
    } else {
        $sql_get_users = "SELECT user_id, username FROM users WHERE user_id = $currentUserId";
    }
    $users_result = $connect->query($sql_get_users);
    if ($users_result && $users_result->num_rows > 0) {
        while($user_row = $users_result->fetch_assoc()) {
            $users_list[] = $user_row;
        }
    }

    $existing_clients = [];
    if ($order_id_from_get == 0) {
        $all_clients_from_orders = [];
        
        // --- CORRECCIÓN 3: Usar la columna correcta 'order_status' (Esta era la línea 181 del error) ---
        $sql_all_clients = "SELECT DISTINCT client_name, client_contact
                            FROM orders
                            WHERE client_name IS NOT NULL AND client_name != ''
                            AND order_status = 1"; // '1' = Pedido Activo
                            
        $all_clients_result = $connect->query($sql_all_clients);
        if ($all_clients_result) {
            while($client_row = $all_clients_result->fetch_assoc()) {
                $all_clients_from_orders[$client_row['client_name']] = $client_row['client_contact'];
            }
        }

        $clients_with_open_tickets = [];
        $sql_open_tickets = "SELECT DISTINCT client_name_ticket
                                FROM ticket
                                WHERE estado_ticket IN ('Pendiente', 'En proceso')
                                AND client_name_ticket IS NOT NULL
                                AND client_name_ticket != ''";
        $open_tickets_result = $connect->query($sql_open_tickets);
        if ($open_tickets_result) {
            while($ticket_row = $open_tickets_result->fetch_assoc()) {
                $clients_with_open_tickets[] = $ticket_row['client_name_ticket'];
            }
        }

        foreach ($all_clients_from_orders as $name => $contact) {
            if (!in_array($name, $clients_with_open_tickets)) {
                $existing_clients[$name] = $contact;
            }
        }
        ksort($existing_clients);
    }
?>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <img src="./img/new_ticket.png" alt="Nuevo Ticket" class="img-responsive">
        </div>
        <div class="col-sm-9">
            <h3 class="text-info">Crear Nueva Tarea / Ticket Interno</h3>
            <p>Complete el formulario para registrar una nueva tarea o incidencia.</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" id="formNewTicket" role="form" action="ticket_new.php" method="POST">
                
                <input type="hidden" name="order_id" value="<?php echo $order_id_from_get; ?>">

                <h4 class="text-primary">Información del Cliente</h4>
                
                <?php if ($order_id_from_get > 0): ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Cliente (Desde Pedido #<?php echo $order_id_from_get; ?>)</label>
                        <div class="col-sm-10">
                            <div class='input-group'>
                                <input type="text" class="form-control" name="client_name_ticket" value="<?php echo htmlspecialchars($client_name_from_order); ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contacto Cliente</label>
                        <div class="col-sm-10">
                            <div class='input-group'>
                                <input type="text" class="form-control" name="client_contact_ticket" id="client_contact_input" value="<?php echo htmlspecialchars($client_contact_from_order); ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Cliente/Empresa</label>
                        <div class="col-sm-10">
                            <div class='input-group'>
                                <select class="form-control" name="client_name_ticket" id="client_name_select" required onchange="fillContact()">
                                    <option value="">-- Seleccione Cliente (Sin Tickets Abiertos) --</option>
                                    <?php foreach($existing_clients as $name => $contact): ?>
                                        <option value="<?php echo htmlspecialchars($name); ?>" data-contact="<?php echo htmlspecialchars($contact); ?>">
                                            <?php echo htmlspecialchars($name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (empty($existing_clients)): ?>
                                        <option value="" disabled>-- No hay clientes disponibles --</option>
                                    <?php endif; ?>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            </div>
                           <small class="text-muted">Solo se muestran clientes sin tickets en estado 'Pendiente' o 'En Proceso'.</small>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Contacto Cliente</label>
                        <div class="col-sm-10">
                            <div class='input-group'>
                                <input type="text" class="form-control" name="client_contact_ticket" id="client_contact_input" placeholder="Contacto (se autocompleta al seleccionar cliente)" required readonly>
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>


                <h4 class="text-primary">Detalles del Ticket</h4>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Asignar a Técnico</label>
                    <div class='col-sm-10'>
                        <div class="input-group">
                            <select class="form-control" name="user_id_asignado">
                                <option value="NULL">-- Sin Asignar (Pendiente) --</option>
                                <?php
                                foreach ($users_list as $user) {
                                    echo "<option value='{$user['user_id']}'>".htmlspecialchars($user['username'])."</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Departamento Interno</label>
                    <div class="col-sm-10">
                        <div class='input-group'>
                            <input type="text" class="form-control" name="departamento_ticket" placeholder="Ej: Soporte Técnico, Redes, Desarrollo" required>
                            <span class="input-group-addon"><i class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Asunto</label>
                    <div class="col-sm-10">
                        <div class='input-group'>
                            <input type="text" class="form-control" name="asunto_ticket" placeholder="Título breve de la tarea o problema" required value="<?php echo htmlspecialchars($asunto_from_order); ?>">
                            <span class="input-group-addon"><i class="fa fa-paperclip"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Descripción / Mensaje</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="mensaje_ticket" placeholder="Describe detalladamente la tarea o el problema..." required></textarea>
                    </div>
                </div>

                <br>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 text-center">
                        <button type="submit" class="btn btn-info">Crear Ticket</button>
                        <a href="tickets.php" class="btn btn-default">Cancelar</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
var select = document.getElementById('client_name_select');
if (select) {
    function fillContact() {
        var contactInput = document.getElementById('client_contact_input');
        var selectedOption = select.options[select.selectedIndex];
        var contact = selectedOption.getAttribute('data-contact');
        contactInput.value = contact ? contact : '';
        contactInput.readOnly = select.value !== ""; 
    }
    document.addEventListener('DOMContentLoaded', fillContact);
}
document.getElementById('formNewTicket').addEventListener('submit', function(e) {

    let enviar = confirm("¿Deseas enviar un correo al técnico asignado?");

    // Si el usuario cancela, NO enviamos correo
    if (!enviar) {
        return; // solo sigue el submit sin correo
    }

    // Si confirma, agregamos un input oculto
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "enviar_correo";
    input.value = "1";
    this.appendChild(input);
});
</script>

<?php
} // <-- FIN DEL 'ELSE'
require_once '../includes/footer.php';
?>