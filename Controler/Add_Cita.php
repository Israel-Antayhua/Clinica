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
function validarCruce($dao, $fecha, $hora, $id_medico)
{
    if ($dao->existeCruce($fecha, $hora, $id_medico)) {
        if ($_SESSION['rol'] == 'paciente') {
            echo "ocupado|El médico ya tiene una cita en ese horario";
        } else {
            echo "ocupado|El horario ya está ocupado";
        }
        exit;
    }
    return "ok";
}
/* =========================
   CAMBIAR HORA (AJAX O FORM)
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'cambiar_hora') {

    $cruce = validarCruce($dao, $fecha, $hora, $id_medico);

    if ($cruce !== "ok") {
        echo $cruce;
        exit; // 🔴 IMPORTANTE: detener todo
    }


    $id = $_POST['id'];

    $sql = "UPDATE citas SET hora = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $hora, $id);

    $resultado = $stmt->execute();

    if ($resultado) {
        echo "ok|Hora actualizada correctamente";
    } else {

        echo "error|Error al actualizar la hora";
    }

    exit;
}
/* =========================
   INSERTAR CITA
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {

    $cruce = validarCruce($dao, $fecha, $hora, $id_medico);

    if ($cruce !== "ok") {
        echo $cruce;
        exit; // 🔴 IMPORTANTE: detener todo
    }

    $resultado = $dao->insertarCita($id_usuario, $id_medico, $monto, $fecha, $hora);

    if ($resultado === true) {
        if ($_SESSION['rol'] == 'paciente') {
            echo "ok|Se registro la cita|../Vistas/citas.php";
        } else {
            echo "ok|Se registro la cita|../Vistas/agenda.php";
        }
    } else {
        if ($_SESSION['rol'] == 'paciente') {
            echo "error|$resultado|../Vistas/citas.php";
        } else {
            echo "error|$resultado|../Vistas/agenda.php";
        }
    }
    exit;
}
