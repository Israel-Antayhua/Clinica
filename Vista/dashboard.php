<?php
require_once '../includes/header.php';
 // Asegúrate que la conexión $connect se inicializa

// Establecer locale a español para la fecha
setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.1252', 'es_PE.utf8');

// --- Consultas para Estadísticas ---

// Productos
$sqlTotalProd = "SELECT COUNT(*) AS total FROM product WHERE status = 1";
$queryTotalProd = $connect->query($sqlTotalProd);
$countProduct = ($queryTotalProd && $queryTotalProd->num_rows > 0) ? $queryTotalProd->fetch_assoc()['total'] : 0;

$sqlLowStock = "SELECT COUNT(*) AS total FROM product WHERE quantity <= 3 AND status = 1";
$queryLowStock = $connect->query($sqlLowStock);
$countLowStock = ($queryLowStock && $queryLowStock->num_rows > 0) ? $queryLowStock->fetch_assoc()['total'] : 0;

// Pedidos (¡MODIFICADO!)
// Ahora calculamos total_pedidos, total_pagado, total_facturado y total_pendiente
$sqlOrders = "SELECT
                COUNT(*) AS total_pedidos,
                SUM(paid) AS total_pagado,
                SUM(grand_total) AS total_facturado,
                SUM(due) AS total_pendiente
              FROM orders
              WHERE order_status = 1"; // Solo pedidos activos
$queryOrders = $connect->query($sqlOrders);
$orderData = ($queryOrders && $queryOrders->num_rows > 0) ? $queryOrders->fetch_assoc() : [
    'total_pedidos' => 0,
    'total_pagado' => 0.0,
    'total_facturado' => 0.0,
    'total_pendiente' => 0.0
];
$countOrder = $orderData['total_pedidos'];
$totalPaidRevenue = (float)$orderData['total_pagado'];
$totalGrandTotal = (float)$orderData['total_facturado'];
$totalDue = (float)$orderData['total_pendiente'];
// Podríamos recalcular el pendiente por si acaso: $totalDue = $totalGrandTotal - $totalPaidRevenue;

// Tickets (Sin cambios)
$sqlTickets = "SELECT COUNT(*) AS total_tickets, SUM(CASE WHEN estado_ticket = 'Pendiente' THEN 1 ELSE 0 END) AS pending_tickets, SUM(CASE WHEN estado_ticket = 'En proceso' THEN 1 ELSE 0 END) AS process_tickets FROM ticket";
$queryTickets = $connect->query($sqlTickets);
$ticketCounts = ($queryTickets && $queryTickets->num_rows > 0) ? $queryTickets->fetch_assoc() : ['total_tickets' => 0, 'pending_tickets' => 0, 'process_tickets' => 0];
$countTotalTickets = $ticketCounts['total_tickets'];
$countPendingTickets = $ticketCounts['pending_tickets'];
$countProcessTickets = $ticketCounts['process_tickets'];

// Pedidos por Usuario (Solo para Admin - Sin cambios)
$userwiseOrders = [];
if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) {
    $userwiseSql = "SELECT u.username, SUM(o.grand_total) as totalorder FROM orders o INNER JOIN users u ON o.user_id = u.user_id WHERE o.order_status = 1 GROUP BY o.user_id ORDER BY totalorder DESC";
    $userwiseQuery = $connect->query($userwiseSql);
    if ($userwiseQuery) { while ($orderResult = $userwiseQuery->fetch_assoc()) { $userwiseOrders[] = $orderResult; } }
}

// Cerrar conexión DESPUÉS de todas las consultas
$connect->close();
?>

<style type="text/css">
    .ui-datepicker-calendar { display: none; }
    .panel-body h3, .panel-body h4 { margin-top: 0; }
    .panel-footer a { color: inherit; display: block; text-decoration: none; }
    .panel-footer .glyphicon { margin-right: 5px; }
    /* Estilos para el panel de ingresos */
    .revenue-panel .value { font-size: 1.5em; font-weight: bold; }
    .revenue-panel .label-text { font-size: 0.9em; color: #777; }
</style>

<link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">

<div class="row">
    <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) : ?>
    <div class="col-lg-3 col-md-6"> <div class="panel panel-primary">
            <div class="panel-heading"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-th-large fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countProduct; ?></h3> <div>Total Productos</div> </div> </div> </div>
            <a href="product.php"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) : ?>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-alert fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countLowStock; ?></h3> <div>Productos Stock Bajo</div> </div> </div> </div>
             <a href="product.php"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>
     <?php endif; ?>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-shopping-cart fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countOrder; ?></h3> <div>Total Pedidos Activos</div> </div> </div> </div>
            <a href="orders.php?o=manord"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>

     <div class="col-lg-3 col-md-6">
        <div class="panel panel-info revenue-panel"> <div class="panel-heading">
                 <div class="row">
                    <div class="col-xs-3"><i class="glyphicon glyphicon-usd fa-3x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="value">S/ <?php echo number_format($totalGrandTotal, 2); ?></div>
                        <div class="label-text">Total Facturado</div>
                    </div>
                </div>
            </div>
             <div class="panel-body"> <div class="row text-center">
                    <div class="col-xs-6">
                        <div class="value text-success">S/ <?php echo number_format($totalPaidRevenue, 2); ?></div>
                        <div class="label-text">Total Pagado</div>
                    </div>
                    <div class="col-xs-6">
                         <div class="value text-danger">S/ <?php echo number_format($totalDue, 2); ?></div>
                        <div class="label-text">Saldo Pendiente</div>
                    </div>
                </div>
             </div>
             <a href="report.php"> <div class="panel-footer">
                    <span class="pull-left">Ver Reportes</span>
                    <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

</div> <hr>

<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="panel panel-default">
             <div class="panel-heading" style="background-color: #f5f5f5; color: #333;"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-tags fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countTotalTickets; ?></h3> <div>Total Tickets</div> </div> </div> </div>
             <a href="tickets.php?ticket=all"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="panel panel-warning">
             <div class="panel-heading"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-envelope fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countPendingTickets; ?></h3> <div>Tickets Pendientes</div> </div> </div> </div>
             <a href="tickets.php?ticket=pending"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>

     <div class="col-md-4 col-sm-6">
        <div class="panel panel-info">
             <div class="panel-heading"> <div class="row"> <div class="col-xs-3"><i class="glyphicon glyphicon-folder-open fa-3x"></i></div> <div class="col-xs-9 text-right"> <h3><?php echo $countProcessTickets; ?></h3> <div>Tickets En Proceso</div> </div> </div> </div>
             <a href="tickets.php?ticket=process"> <div class="panel-footer"> <span class="pull-left">Ver Detalles</span> <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span> <div class="clearfix"></div> </div> </a>
        </div>
    </div>

</div> <div class="row">
    <div class="col-md-4">
         <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color:#eee;"> <i class="glyphicon glyphicon-calendar"></i> Fecha Actual </div>
            <div class="panel-body text-center"> <h4><?php echo ucfirst(strftime('%A')); ?></h4> <p><?php echo strftime('%d de %B, %Y'); ?></p> </div>
         </div>
    </div>

    <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1 && !empty($userwiseOrders)) : ?>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading"> <i class="glyphicon glyphicon-user"></i> Resumen de Pedidos por Usuario</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <thead> <tr> <th>Usuario</th> <th class="text-right">Total Pedidos (S/)</th> </tr> </thead>
                        <tbody>
                            <?php foreach ($userwiseOrders as $orderResult) : ?>
                                <tr> <td><?php echo htmlspecialchars($orderResult['username']); ?></td> <td class="text-right">S/ <?php echo number_format($orderResult['totalorder'], 2); ?></td> </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div> <script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#navDashboard').addClass('active');
        // Inicialización de FullCalendar comentada si no se usa aquí
        /* $('#calendar').fullCalendar({ ... }); */
    });
</script>
<?php require_once '../includes/footer.php'; ?>