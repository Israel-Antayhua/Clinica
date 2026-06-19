<?php
session_start();
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

    if (password_verify($password, $datos['password'])) {

        $_SESSION['rol'] = $datos['rol'];
        $id_usuario = $datos['id'];
        if ($datos['rol'] == "paciente") {
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

        $result = enviarCorreo($usuario,"Código de Inicio de Sesion","Verificación de login",
        "Tu código OTP es: <b>{{codigo}}</b>"
        );

        echo json_encode($result);
    } else {
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Usuario no existe"]);
}

$conexion->close();
