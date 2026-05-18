<?php 
    require_once '../includes/header.php';
    require_once '../php_action/db_connect.php';
?>

<?php
// 1. SEGURIDAD (Cualquier usuario logueado)
if ( !isset($_SESSION['userId']) ) {
    echo '<div class="alert alert-danger text-center">Acceso Denegado. Debe iniciar sesión.</div>';
    require_once 'includes/footer.php';
    exit();
}

// 2. LÓGICA DE BORRADO (Solo Admin)
if ( isset($_POST['id_del']) && $_SESSION['userId'] == 1 ) {
    $id_a_borrar = (int)$_POST['id_del'];
    if ($id_a_borrar > 0) {
        $sql_delete = "DELETE FROM ticket WHERE id = $id_a_borrar";
        if ($connect->query($sql_delete) === TRUE) {
            // Mensaje Éxito (corto para no interrumpir mucho)
            echo '<div class="alert alert-info alert-dismissible fade in" role="alert" style="position:fixed; top:70px; right:10px; z-index:1000; width: auto;"> <button type="button" class="close" data-dismiss="alert">&times;</button> Ticket eliminado.</div>';
        } else {
             // Mensaje Error
            echo '<div class="alert alert-danger alert-dismissible fade in" role="alert" style="position:fixed; top:70px; right:10px; z-index:1000; width: auto;"> <button type="button" class="close" data-dismiss="alert">&times;</button> Error al eliminar.</div>';
        }
    }
}

// 3. CONTADORES (Sin cambios)
$sql_counts = "SELECT
                    COUNT(*) AS total_all,
                    SUM(CASE WHEN estado_ticket = 'Pendiente' THEN 1 ELSE 0 END) AS total_pend,
                    SUM(CASE WHEN estado_ticket = 'En proceso' THEN 1 ELSE 0 END) AS total_proceso,
                    SUM(CASE WHEN estado_ticket = 'Resuelto' THEN 1 ELSE 0 END) AS total_res
                FROM ticket";
$result_counts = $connect->query($sql_counts);
// Manejar posible error en la consulta de conteo
$counts = $result_counts ? $result_counts->fetch_assoc() : ['total_all' => 0, 'total_pend' => 0, 'total_proceso' => 0, 'total_res' => 0];
$num_total_all = $counts['total_all'];
$num_total_pend = $counts['total_pend'];
$num_total_proceso = $counts['total_proceso'];
$num_total_res = $counts['total_res'];
?>

<div class="container">
  <div class="row">
    <div class="col-sm-2">
      <img src="./img/msj.png" alt="Tickets" class="img-responsive animated tada hidden-xs"> </div>
    <div class="col-sm-10">
      <h3 class="text-info">Gestión de Tickets Internos</h3>
      <p>Aquí se muestran todas las tareas y tickets internos del sistema. Puede filtrar por estado usando las pestañas.</p>
    </div>
  </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <?php $ticket_view = isset($_GET['ticket']) ? $_GET['ticket'] : 'all'; ?>
                <li class="<?php echo ($ticket_view == 'all') ? 'active' : ''; ?>">
                    <a href="tickets.php?ticket=all"><i class="fa fa-list"></i>&nbsp;&nbsp;Todos <span class="badge"><?php echo $num_total_all; ?></span></a>
                </li>
                <li class="<?php echo ($ticket_view == 'pending') ? 'active' : ''; ?>">
                    <a href="tickets.php?ticket=pending"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Pendientes <span class="badge"><?php echo $num_total_pend; ?></span></a>
                </li>
                <li class="<?php echo ($ticket_view == 'process') ? 'active' : ''; ?>">
                    <a href="tickets.php?ticket=process"><i class="fa fa-folder-open"></i>&nbsp;&nbsp;En Proceso <span class="badge"><?php echo $num_total_proceso; ?></span></a>
                </li>
                <li class="<?php echo ($ticket_view == 'resolved') ? 'active' : ''; ?>">
                    <a href="tickets.php?ticket=resolved"><i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;Resueltos <span class="badge"><?php echo $num_total_res; ?></span></a>
                </li>
            </ul>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <div class="panel-heading">
                    <h3 class="panel-title">Lista de Tickets <?php echo ucfirst($ticket_view); ?></h3>
                 </div>
                 <div class="panel-body">
                    <div class="table-responsive">
                        <?php
                            // 4. LÓGICA DE LISTADO Y PAGINACIÓN
                            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                            $regpagina = 15;
                            $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                            // Base de la consulta con JOIN a users
                            $sql_base = "FROM ticket t LEFT JOIN users u ON t.user_id_asignado = u.user_id";
                            $where_clause = ""; // Clause WHERE separada

                            if ($ticket_view == "pending") { $where_clause = " WHERE t.estado_ticket='Pendiente'"; }
                            elseif ($ticket_view == "process") { $where_clause = " WHERE t.estado_ticket='En proceso'"; }
                            elseif ($ticket_view == "resolved") { $where_clause = " WHERE t.estado_ticket='Resuelto'"; }

                            // Contar registros con el filtro aplicado
                            $total_registros_query = $connect->query("SELECT COUNT(t.id) AS total $sql_base $where_clause");
                            // Manejar posible error en conteo
                            $total_registros = $total_registros_query ? $total_registros_query->fetch_assoc()['total'] : 0;
                            $numeropaginas = ceil($total_registros / $regpagina);

                            // ** MODIFICADO: Añadido t.client_name_ticket a la selección **
                            $sql_listado = "SELECT t.id, t.fecha, t.serie, t.estado_ticket, t.departamento, t.asunto,
                                                   t.client_name_ticket, u.username
                                            $sql_base
                                            $where_clause
                                            ORDER BY t.id DESC
                                            LIMIT $inicio, $regpagina";

                            $resultado_listado = $connect->query($sql_listado);

                            if ($resultado_listado && $resultado_listado->num_rows > 0):
                        ?>
                        <table class="table table-hover table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Serie</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Asunto</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Asignado a</th>
                                    <th class="text-center" style="min-width: 90px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $ct = $inicio + 1;
                                    while ($row = $resultado_listado->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $ct; ?></td>
                                    <td class="text-center"><?php echo date("d/m/Y", strtotime($row['fecha'])); // Formato fecha corto ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['serie']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['client_name_ticket'] ?: '-'); // Muestra cliente o guión ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['asunto']); ?></td>
                                    <td class="text-center">
                                        <?php // Añadir etiquetas de color según estado
                                            $estado = $row['estado_ticket'];
                                            $label_class = 'label-default'; // Por defecto
                                            if ($estado == 'Pendiente') $label_class = 'label-danger';
                                            elseif ($estado == 'En proceso') $label_class = 'label-warning';
                                            elseif ($estado == 'Resuelto') $label_class = 'label-success';
                                            echo "<span class='label $label_class'>$estado</span>";
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars($row['username'] ?: '<span class="text-muted">Nadie</span>'); ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="../../lib/pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip" title="Imprimir PDF"><i class="fa fa-print"></i></a>
                                        <a href="ticket_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar Ticket"><i class="fa fa-pencil"></i></a>
                                        <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1): // Solo Admin puede borrar ?>
                                            <form action="tickets.php?ticket=<?php echo $ticket_view; ?>&pagina=<?php echo $pagina; ?>" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id_del" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar Ticket" onclick="return confirm('¿Está seguro de eliminar este ticket?');"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $ct++; endwhile; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">No hay tickets registrados en esta categoría.</div>
                        <?php endif; ?>
                    </div> <?php if ($numeropaginas > 1): ?>
                        <div class="text-center">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm">
                                    <?php if ($pagina == 1): ?><li class="disabled"><span>&laquo;</span></li>
                                    <?php else: ?><li><a href="tickets.php?ticket=<?php echo $ticket_view; ?>&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li><?php endif; ?>

                                    <?php for ($i = 1; $i <= $numeropaginas; $i++ ): ?>
                                        <?php if ($pagina == $i): ?><li class="active"><span><?php echo $i; ?><span class="sr-only">(current)</span></span></li>
                                        <?php else: ?><li><a href="tickets.php?ticket=<?php echo $ticket_view; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if ($pagina == $numeropaginas): ?><li class="disabled"><span>&raquo;</span></li>
                                    <?php else: ?><li><a href="tickets.php?ticket=<?php echo $ticket_view; ?>&pagina=<?php echo $pagina + 1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li><?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                    </div> </div> </div> </div> </div><script type="text/javascript">
 $(function () {
     $('[data-toggle="tooltip"]').tooltip()
 })
</script>

<?php require_once '../includes/footer.php'; ?>