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
if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_otp') {


    if (!isset($_SESSION['otp']) || $_POST['otp'] != $_SESSION['otp']) {
        echo "error|OTP incorrecto";
        exit;
    }

    if (time() - $_SESSION['otp_time'] > 300) {
        echo "error|OTP expirado";
        exit;
    }

    $id = $_POST['id'];
    $tipo = $_POST['tipo_accion'];

    if ($tipo == 'reprogramar') {
        $cruce = validarCruce($dao, $fecha, $hora, $id_medico);

        if ($cruce !== "ok") {
            echo $cruce;
            exit; // 🔴 IMPORTANTE: detener todo
        }

        $sqlEstado = "SELECT estado_pago FROM citas WHERE id = ?";
        $stmtEstado = $conexion->prepare($sqlEstado);
        $stmtEstado->bind_param("i", $id);
        $stmtEstado->execute();
        $resultado = $stmtEstado->get_result()->fetch_assoc();

        $estadoPago = $resultado['estado_pago'];

        // decidir nuevo estado
        $nuevoEstado = "Pendiente";

        if ($estadoPago == "Pagado") {
            $nuevoEstado = "Confirmado";
        }


        $sql = "UPDATE citas 
            SET fecha = ?, 
                hora = ?, 
                estado = ? 
            WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssi", $fecha, $hora, $nuevoEstado, $id);
        if ($stmt->execute()) {
            echo "ok|Hora actualizada correctamente";
        } else {
            echo "error|No se pudo actualizar";
        }
        exit;
    }
    if ($tipo == 'cancelar') {

        $sql = "UPDATE citas SET estado = 'Cancelada' WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "ok|Cita cancelada correctamente";
        } else {
            echo "error|Error al cancelar";
        }
        exit;
    }
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
