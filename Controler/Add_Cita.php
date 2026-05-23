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

/* =========================
   VALIDAR CRUCE
========================= */
/* =========================
   CAMBIAR HORA (AJAX O FORM)
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'cambiar_hora') {
    if ($dao->existeCruce($fecha, $hora, $id_medico)) {
        $_SESSION['swal'] = [
            'icon' => 'warning',
            'title' => 'Horario ocupado',
            'text' => 'El médico ya tiene una cita en ese horario'
        ];

        header("Location: ../Vistas/citas.php");
        exit;
    }

    $id = $_POST['id'];

    $sql = "UPDATE citas SET hora = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $hora, $id);

    $resultado = $stmt->execute();

    $_SESSION['swal'] = [
        'icon' => $resultado ? 'success' : 'error',
        'title' => $resultado ? 'Actualizado' : 'Error',
        'text' => $resultado ? 'Hora actualizada correctamente' : 'Error al actualizar'
    ];

    if ($_SESSION['rol'] == 'paciente') {
            header("Location: ../Vistas/citas.php?msg=ok");
            exit;
        } else {
            header("Location: ../Vistas/agenda.php?msg=ok");
            exit;
        }
}
/* =========================
   INSERTAR CITA
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    if ($dao->existeCruce($fecha, $hora, $id_medico)) {
        $_SESSION['swal'] = [
            'icon' => 'warning',
            'title' => 'Horario ocupado',
            'text' => 'El médico ya tiene una cita en ese horario'
        ];

        header("Location: ../Vistas/citas.php");
        exit;
    }

    $resultado = $dao->insertarCita($id_usuario, $id_medico, $monto, $fecha, $hora);

    if ($resultado === true) {

        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Correcto',
            'text' => 'Cita registrada correctamente'
        ];
        if ($_SESSION['rol'] == 'paciente') {
            header("Location: ../Vistas/citas.php?msg=ok");
            exit;
        } else {
            header("Location: ../Vistas/agenda.php?msg=ok");
            exit;
        }
    } else {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Error',
            'text' => $resultado
        ];

        if ($_SESSION['id_usuario'] == 'paciente') {
            header("Location: ../Vistas/citas.php?msg=Error");
            exit;
        } else {
            header("Location: ../Vistas/agenda.php?msg=error");
            exit;
        }
    }
}
