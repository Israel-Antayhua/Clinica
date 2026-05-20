<?php

session_start();
include("../ConexionDB/conexion.php");
include("../Dao/CitaDAO.php");

$dao = new CitaDAO($conexion);

// 📥 datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$id_medico = $_POST['id_medico'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

if ($dao->existeCruce($fecha, $hora, $id_medico)) {

    echo "<script>
        alert('Este médico ya tiene una cita en ese horario');
        window.location.href='index.php';
    </script>";
} else {

    $resultado = $dao->insertarCita($id_usuario, $id_medico, $fecha, $hora);
    if ($resultado === true) {
        echo "<script>
        alert('Cita registrada correctamente');
        window.location.href='../Vistas/citas.php';
    </script>";
    } else {
        echo $resultado;
        exit;
    }
}
