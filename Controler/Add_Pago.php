<?php

include '../ConexionDB/conexion.php';
include '../DAO/PagoDAO.php';

$pagoDAO = new Pago($conexion);

$id_cita  = 1;
$monto    = 80;
$estado   = 'Pagado';
$charge   = 'chr_test_123456';
$metodo   = 'VISA';

$guardar = $pagoDAO->agregarPago(
    $id_cita,
    $monto,
    $estado,
    $charge,
    $metodo
);

if ($guardar) {

    echo "Pago registrado";

} else {

    echo "Error";

}