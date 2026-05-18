<?php require_once '../includes/header.php';
    require_once '../php_action/db_connect.php';
require_once '../php_action/DAO/GoCorreo.php'; ?>

<?php
// 1. SEGURIDAD (Cualquier usuario logueado)
// Asegúrate que $connect esté disponible
 // Incluir si no está en header.php o core.php
if ( !isset($_SESSION['userId']) ) {
    if(isset($connect) && $connect) $connect->close(); // Cerrar conexión si existe
    echo '<div class="alert alert-danger text-center">Acceso Denegado. Debe iniciar sesión.</div>';
    require_once 'includes/footer.php';
    exit();
}

$ticket_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// $original_status = ''; // Ya no se necesita

// 2. LÓGICA DE ACTUALIZACIÓN (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_edit'])) {

    $id_edit = (int)$_POST['id_edit'];
    // Solo procesamos si el ID del POST coincide con el ID del GET
    if ($id_edit === $ticket_id && $ticket_id > 0) {

        $estado_edit = $connect->real_escape_string($_POST['estado_ticket']);
        $solucion_edit = $connect->real_escape_string($_POST['solucion_ticket']);

        $user_id_asignado_edit = $_POST['user_id_asignado'];
        $sql_user_id = ($user_id_asignado_edit == 'NULL' || $user_id_asignado_edit == 0 || $user_id_asignado_edit == '') ? "NULL" : (int)$user_id_asignado_edit;

        // ** MODIFICADO: Manejar valor vacío para Cliente/Contacto **
        $client_name_edit_input = isset($_POST['client_name_ticket']) ? trim($_POST['client_name_ticket']) : '';
        $client_contact_edit_input = isset($_POST['client_contact_ticket']) ? trim($_POST['client_contact_ticket']) : '';

        // Si el nombre del cliente está vacío (''), guardar NULL en la BD
        if ($client_name_edit_input === '') {
            $sql_client_name = "NULL";
            $sql_client_contact = "NULL"; // Si no hay cliente, tampoco hay contacto
        } else {
            $sql_client_name = "'" . $connect->real_escape_string($client_name_edit_input) . "'";
            $sql_client_contact = "'" . $connect->real_escape_string($client_contact_edit_input) . "'";
        }
        // ** FIN MODIFICACIÓN **

        // ** Actualizar tabla ticket **
        $sql_update_ticket = "UPDATE ticket
                              SET
                                  estado_ticket = '$estado_edit',
                                  solucion = '$solucion_edit',
                                  user_id_asignado = $sql_user_id,
                                  client_name_ticket = $sql_client_name,
                                  client_contact_ticket = $sql_client_contact
                              WHERE id = $id_edit";

        $update_success = $connect->query($sql_update_ticket);

        if ($update_success) {
            // Mensaje Éxito
            $sql_fetch_ticket = "SELECT * FROM ticket WHERE id = $ticket_id";
            $result_fetch = $connect->query($sql_fetch_ticket);
            if ($result_fetch && $result_fetch->num_rows > 0) {
                $reg = $result_fetch->fetch_assoc();
            }
            $serie   = isset($reg['serie']) ? $reg['serie'] : 'prueba';
            $asunto  = isset($reg['asunto']) ? $reg['asunto'] : '';
            $mensaje = isset($reg['mensaje']) ? $reg['mensaje'] : '';
            if ($user_id_asignado_edit !== null) {
                echo '<div class="alert alert-info alert-dismissible fade in col-sm-4 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:1000;">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">×</span></button>
                     <h4 class="text-center">TICKET Actualizado</h4>
                     <p class="text-center">El ticket fue actualizado con éxito.</p>
                 </div>';
                if (isset($_POST['enviar_correo']) && $_POST['enviar_correo'] == 1) {
                enviarCorreoAsignacion($connect, $user_id_asignado_edit, $serie, $asunto, $mensaje);
                }
                }
        } else {
            // Error al actualizar ticket
             echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:1000;">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">×</span></button>
                     <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                     <p class="text-center">No se pudo actualizar el ticket: ' . $connect->error . '</p>
                 </div>';
        }
    } else {
         echo '<div class="alert alert-danger text-center">Error: ID de ticket inválido para la actualización.</div>';
    }
    // No cerramos conexión aquí si la página sigue cargando datos GET
} // Fin del bloque POST

// 3. LÓGICA PARA MOSTRAR DATOS (GET)
$reg = null;
$client_ordered_products = []; // Productos históricos del cliente
$client_list_for_select = []; // Lista final de clientes para el <select>

if ($ticket_id > 0) {
    // Obtener datos del ticket actual
    $sql_fetch_ticket = "SELECT * FROM ticket WHERE id = $ticket_id";
    $result_fetch = $connect->query($sql_fetch_ticket);
    if ($result_fetch && $result_fetch->num_rows > 0) {
        $reg = $result_fetch->fetch_assoc();

        // Obtener productos históricos del cliente actual (si existe)
        if (!empty($reg['client_name_ticket'])) {
            $client_name_for_query = $connect->real_escape_string($reg['client_name_ticket']);
            $sql_client_products = "SELECT DISTINCT p.product_id, p.product_name, p.quantity as current_stock FROM product p JOIN order_item oi ON p.product_id = oi.product_id JOIN orders o ON oi.order_id = o.order_id WHERE o.client_name = '$client_name_for_query' ORDER BY p.product_name ASC";
            $client_prod_result = $connect->query($sql_client_products);
            if ($client_prod_result) { while($prod_row = $client_prod_result->fetch_assoc()){ $client_ordered_products[] = $prod_row; } }
        }

        // --- LÓGICA PARA FILTRAR CLIENTES DEL SELECT ---
        // 1. Obtener TODOS los clientes únicos de 'orders'
        $all_clients_from_orders = [];
        $sql_all_clients = "SELECT DISTINCT client_name, client_contact FROM orders WHERE client_name IS NOT NULL AND client_name != ''";
        $all_clients_result = $connect->query($sql_all_clients);
        if ($all_clients_result) { while($client_row = $all_clients_result->fetch_assoc()) { $all_clients_from_orders[$client_row['client_name']] = $client_row['client_contact']; } }

        // 2. Obtener clientes con tickets abiertos (excluyendo el ticket actual)
        $clients_with_open_tickets = [];
        $sql_open_tickets = "SELECT DISTINCT client_name_ticket FROM ticket WHERE estado_ticket IN ('Pendiente', 'En proceso') AND client_name_ticket IS NOT NULL AND client_name_ticket != '' AND id != $ticket_id"; // Excluir ticket actual
        $open_tickets_result = $connect->query($sql_open_tickets);
        if ($open_tickets_result) { while($ticket_row = $open_tickets_result->fetch_assoc()) { $clients_with_open_tickets[] = $ticket_row['client_name_ticket']; } }

        // 3. Construir la lista final para el select
        foreach ($all_clients_from_orders as $name => $contact) {
            // Incluir si NO está en la lista de tickets abiertos O si es el cliente actual de ESTE ticket
            if (!in_array($name, $clients_with_open_tickets) || (isset($reg['client_name_ticket']) && $name == $reg['client_name_ticket'])) {
                $client_list_for_select[$name] = $contact;
            }
        }
        // Asegurarse de que el cliente actual esté en la lista (si existe y no estaba ya en orders)
        if (isset($reg['client_name_ticket']) && !empty($reg['client_name_ticket']) && !array_key_exists($reg['client_name_ticket'], $client_list_for_select)) {
             $client_list_for_select[$reg['client_name_ticket']] = isset($reg['client_contact_ticket']) ? $reg['client_contact_ticket'] : '';
        }
        // Ordenar alfabéticamente
        ksort($client_list_for_select);
        // --- FIN LÓGICA FILTRADO ---

    }
}
// Obtener lista de usuarios: admin (user_id==1) ve todos, usuarios normales solo se ven a sí mismos
$users_list = [];
$currentUserId = isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 0;
if ($currentUserId === 1) {
    $sql_get_users = "SELECT user_id, username FROM users ORDER BY username ASC";
} else {
    $sql_get_users = "SELECT user_id, username FROM users WHERE user_id = $currentUserId";
}
$users_result = $connect->query($sql_get_users);
if ($users_result && $users_result->num_rows > 0) { while($user_row = $users_result->fetch_assoc()) { $users_list[] = $user_row; } }


// Salir si no se encontró el ticket
if ($reg == null) {
    echo '<div class="alert alert-danger text-center">Error: No se encontró el ticket con el ID especificado.</div>';
    if(isset($connect) && $connect) $connect->close(); // Cerrar conexión antes de salir
    require_once 'includes/footer.php'; exit();
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <img src="./img/Edit.png" alt="Editar Ticket" class="img-responsive animated tada hidden-xs">
        </div>
        <div class="col-sm-9">
            <a href="tickets.php" class="btn btn-primary btn-sm pull-right"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver a la lista de Tickets</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="col-sm-12">
        <form class="form-horizontal" role="form" action="ticket_edit.php?id=<?php echo $reg['id']; ?>" method="POST" id="editTicketForm">
            <input type="hidden" name="id_edit" value="<?php echo $reg['id']; ?>">

            <h4 class="text-info">Información del Ticket y Cliente</h4> <hr>

            <div class="form-group">
                <label class="col-sm-2 control-label">Fecha Creación</label>
                <div class='col-sm-4'><input class="form-control" readonly value="<?php echo htmlspecialchars(isset($reg['fecha']) ? $reg['fecha'] : ''); ?>"></div>
                 <label class="col-sm-2 control-label">Serie</label>
                <div class='col-sm-4'><input class="form-control" readonly value="<?php echo htmlspecialchars(isset($reg['serie']) ? $reg['serie'] : ''); ?>"></div>
            </div>

             <div class="form-group">
                <label class="col-sm-2 control-label">Cliente/Empresa</label>
                <div class='col-sm-10'>
                     <div class='input-group'>
                        <select class="form-control" name="client_name_ticket" id="client_name_select" onchange="fillContact()">
                            <option value="">-- Seleccione Cliente (o Dejar Vacío) --</option>
                            <?php
                            foreach($client_list_for_select as $name => $contact):
                                // Marcar como seleccionado si coincide con el cliente actual del ticket
                                $selected = (isset($reg['client_name_ticket']) && $reg['client_name_ticket'] == $name) ? 'selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($name); ?>" data-contact="<?php echo htmlspecialchars($contact); ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($name); ?>
                                </option>
                            <?php endforeach; ?>
                             <?php if (empty($client_list_for_select) && isset($reg['client_name_ticket']) && !empty($reg['client_name_ticket'])): ?>
                                <option value="<?php echo htmlspecialchars($reg['client_name_ticket']); ?>" data-contact="<?php echo htmlspecialchars(isset($reg['client_contact_ticket']) ? $reg['client_contact_ticket'] : ''); ?>" selected>
                                    <?php echo htmlspecialchars($reg['client_name_ticket']); ?> (Actual)
                                </option>
                             <?php elseif (empty($client_list_for_select) && !(isset($reg['client_name_ticket']) && !empty($reg['client_name_ticket']))): ?>
                                 <option value="" disabled>-- No hay clientes disponibles --</option>
                            <?php endif; ?>
                        </select>
                        <span class="input-group-addon"><i class="fa fa-building"></i></span>
                    </div>
                     <small class="text-muted">Puede seleccionar "-- Seleccione Cliente --" para desvincular este ticket.</small>
                </div>
            </div>
            <div class="form-group">
                 <label class="col-sm-2 control-label">Contacto Cliente</label>
                <div class='col-sm-10'>
                     <div class='input-group'>
                        <input type="text" class="form-control" name="client_contact_ticket" id="client_contact_input" placeholder="Contacto (se autocompleta o ingrese)" value="<?php echo htmlspecialchars(isset($reg['client_contact_ticket']) ? $reg['client_contact_ticket'] : ''); ?>">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    </div>
                </div>
            </div>

             <div class="form-group">
                <label class="col-sm-2 control-label">Estado</label>
                <div class='col-sm-4'>
                    <select class="form-control" name="estado_ticket">
                        <option value="<?php echo htmlspecialchars(isset($reg['estado_ticket']) ? $reg['estado_ticket'] : ''); ?>"><?php echo htmlspecialchars(isset($reg['estado_ticket']) ? $reg['estado_ticket'] : ''); ?> (Actual)</option>
                         <?php if(!isset($reg['estado_ticket']) || $reg['estado_ticket'] != 'Pendiente') echo '<option value="Pendiente">Pendiente</option>'; ?>
                         <?php if(!isset($reg['estado_ticket']) || $reg['estado_ticket'] != 'En proceso') echo '<option value="En proceso">En proceso</option>'; ?>
                         <?php if(!isset($reg['estado_ticket']) || $reg['estado_ticket'] != 'Resuelto') echo '<option value="Resuelto">Resuelto</option>'; ?>
                    </select>
                </div>
                 <label class="col-sm-2 control-label">Asignar a Técnico</label>
                <div class='col-sm-4'>
                     <select class="form-control" name="user_id_asignado">
                         <option value="NULL">-- Sin Asignar --</option>
                         <?php foreach ($users_list as $user): $selected = (isset($reg['user_id_asignado']) && $reg['user_id_asignado'] == $user['user_id']) ? 'selected' : ''; ?>
                             <option value='<?php echo $user['user_id']; ?>' <?php echo $selected; ?>><?php echo htmlspecialchars($user['username']); ?></option>
                         <?php endforeach; ?>
                     </select>
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-2 control-label">Departamento</label>
                <div class="col-sm-4"><input type="text" readonly class="form-control" value="<?php echo htmlspecialchars(isset($reg['departamento']) ? $reg['departamento'] : ''); ?>"></div>
                <label class="col-sm-2 control-label">Asunto</label>
                <div class="col-sm-4"><input type="text" readonly class="form-control" value="<?php echo htmlspecialchars(isset($reg['asunto']) ? $reg['asunto'] : ''); ?>"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Descripción Original</label>
                <div class="col-sm-10"><textarea class="form-control" readonly rows="3"><?php echo htmlspecialchars(isset($reg['mensaje']) ? $reg['mensaje'] : ''); ?></textarea></div>
            </div>

            <h4 class="text-info">Productos Históricos del Cliente</h4> <hr>
            <div class="form-group">
                <label class="col-sm-2 control-label">Productos en Pedidos Anteriores</label>
                <div class="col-sm-10">
                    <div id="clientProductList" style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 4px; background-color: #f9f9f9;">
                        <?php if (!empty($client_ordered_products)): ?>
                            <ul class="list-unstyled">
                                <?php foreach ($client_ordered_products as $product): ?>
                                    <li>
                                        <i class="glyphicon glyphicon-tag"></i> <?php echo htmlspecialchars($product['product_name']); ?>
                                        <span class="text-muted">(Stock actual: <?php echo htmlspecialchars($product['current_stock']); ?>)</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php elseif (!empty($reg['client_name_ticket'])): ?>
                            <p class="text-muted">Este cliente no tiene productos registrados en pedidos anteriores.</p>
                        <?php else: ?>
                             <p class="text-muted">Seleccione un cliente para ver sus productos asociados.</p>
                        <?php endif; ?>
                    </div>
                     <small class="text-muted">Lista de productos asociados a pedidos anteriores de este cliente (solo informativo).</small>
                </div>
            </div>


            <h4 class="text-info">Solución y Cierre</h4> <hr>
             <div class="form-group">
                 <label class="col-sm-2 control-label">Solución Aplicada</label>
                 <div class="col-sm-10"><textarea class="form-control" rows="5" name="solucion_ticket" placeholder="Describe la solución..." required><?php echo htmlspecialchars(isset($reg['solucion']) ? $reg['solucion'] : ''); ?></textarea></div>
             </div>

             <br>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button type="submit" class="btn btn-info">Actualizar Ticket</button>
                    <a href="tickets.php" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </form>
    </div></div><script>
function fillContact() {
    var select = document.getElementById('client_name_select');
    var contactInput = document.getElementById('client_contact_input');
    var selectedOption = select.options[select.selectedIndex];
    // Obtener contacto del atributo data-contact O dejar vacío si es la opción "-- Seleccione --"
    var contact = (select.value === "") ? "" : selectedOption.getAttribute('data-contact');

    contactInput.value = contact ? contact : '';
    contactInput.readOnly = false; // Siempre editable
    contactInput.placeholder = (select.value === "") ? "Ingrese contacto (si aplica)" : (contact ? "Contacto (autocompletado, puede editar)" : "Ingrese contacto");

    // ** NUEVO: Quitar 'required' del contacto si no hay cliente seleccionado **
    // contactInput.required = (select.value !== ""); // Comentado - Decide si el contacto es obligatorio solo si hay cliente
}
// Llamar al cargar para establecer estado inicial
document.addEventListener('DOMContentLoaded', fillContact);
document.getElementById('editTicketForm').addEventListener('submit', function(e) {

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
// Cerrar conexión si sigue abierta
if(isset($connect) && $connect instanceof mysqli) {
    $connect->close();
}
require_once '../includes/footer.php';
?>