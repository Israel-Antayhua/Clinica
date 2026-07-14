<?php
session_start();
date_default_timezone_set('America/Lima');
header('Content-Type: application/json');

include '../ConexionDB/conexion.php';
require 'codigo.php';

$usuario  = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {

    $datos = $resultado->fetch_assoc();

    if (
        !empty($datos['bloqueado_hasta']) &&
        strtotime($datos['bloqueado_hasta']) > time()
    ) {
        echo json_encode([
            "status" => "blocked",
            "message" => "Tu cuenta está bloqueada temporalmente. Intenta en un rato."
        ]);
        exit;
    }

    if (password_verify($password, $datos['password'])) {
        $_SESSION['rol'] = $datos['rol'];
        $id_usuario = $datos['id'];
        if ($datos['rol'] == "paciente" || $datos['rol'] == "Paciente") {
            $sql2 = "SELECT id_paciente, nombres FROM pacientes WHERE id_usuario = ?";
            $stmt = $conexion->prepare($sql2);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();

            $result = $stmt->get_result();
            $paciente = $result->fetch_assoc();
            $_SESSION['temp_user'] = $paciente['nombres'];
            $_SESSION['id_usuario'] = $paciente['id_paciente'];
        } else {
            $sql = "SELECT id, nombre
             FROM medicos
             WHERE id_usuario = ?";

            $stmt2 = $conexion->prepare($sql);
            $stmt2->bind_param("i", $id_usuario);
            $stmt2->execute();

            $result2 = $stmt2->get_result();
            $medico = $result2->fetch_assoc();

            $_SESSION['temp_user'] = $medico['nombre'];
            $_SESSION['id_medico'] = $medico['id'];
        }

        $_SESSION['rol_temp'] = $datos['rol'];

        $result = enviarCorreo(
            $usuario,
            "OTP",
            "Codigo de Iniciar Sesion"
        );
                $update = $conexion->prepare("
            UPDATE usuarios
            SET intentos_fallidos = 0,
                bloqueado_hasta = NULL
            WHERE id = ?
        ");
        $update->bind_param("i", $datos['id']);
        $update->execute();
        echo json_encode($result);
    } else {
        $intentos = $datos['intentos_fallidos'] + 1;

        if ($intentos >= 3) {

            $update = $conexion->prepare("
                UPDATE usuarios
                SET intentos_fallidos = 0,
                    bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 1 MINUTE)
                WHERE id = ?
            ");
            $update->bind_param("i", $datos['id']);
            $update->execute();

            echo json_encode([
                "status" => "blocked",
                "message" => "Has superado los 3 intentos. Tu cuenta fue bloqueada durante 1 minutos."
            ]);
            exit;
        } else {

            $update = $conexion->prepare("
                UPDATE usuarios
                SET intentos_fallidos = ?
                WHERE id = ?
            ");
            $update->bind_param("ii", $intentos, $datos['id']);
            $update->execute();

            echo json_encode([
                "status" => "error",
                "message" => "Correo o contraseña incorrectos. Intento $intentos de 3."
            ]);
            exit;
        }
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Correo o contraseña incorrectos."
    ]);
    exit;
}

$conexion->close();
