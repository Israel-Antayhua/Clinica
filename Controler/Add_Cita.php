<?php
session_start();
include("../ConexionDB/conexion.php");
include("../Dao/CitaDAO.php");

$dao = new CitaDAO($conexion);

// 📥 datos del formulario
if ($_SESSION['rol'] == "medico") {
    $id_medico = $_SESSION['id_medico'];
    $id_usuario = $_POST['id_paciente'];
} else {
    $id_usuario = $_SESSION['id_usuario'];
    $id_medico = $_POST['id_medico'];
}
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$monto = $_POST['monto'];

if ($dao->existeCruce($fecha, $hora, $id_medico)) {
    echo "ocupado";
    exit;
}
$resultado = $dao->insertarCita($id_usuario, $id_medico, $monto, $fecha, $hora);

if ($resultado === true) {

    $_SESSION['swal'] = [

        'icon' => 'success',
        'title' => 'Correcto',
        'text' => 'Especialidad registrado correctamente'

    ];
    header("Location: ../Vistas/citas.php?msg=ok");

} else {

    $_SESSION['swal'] = [

        'icon' => 'error',
        'title' => 'Error',
        'text' => $resultado

    ];

}
