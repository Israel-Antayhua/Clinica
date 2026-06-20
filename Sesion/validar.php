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

        $result = enviarCorreo(
            $usuario,
            "Codigo de Verificacion de Inicio de Sesion",
            "Verificación de seguridad",
            "
    <div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>
        
        <div style='max-width:500px; margin:auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);'>
            
            <div style='background:#0d6efd; color:white; padding:15px 20px; text-align:center;'>
                <h2 style='margin:0;'>🔐 Verificación de Seguridad</h2>
            </div>

            <div style='padding:25px; color:#333;'>
                
                <p style='font-size:15px;'>
                    Hola, hemos recibido una solicitud para iniciar sesión en tu cuenta.
                </p>

                <p style='font-size:15px;'>
                    Para continuar, utiliza el siguiente código de verificación OTP:
                </p>

                <div style='text-align:center; margin:25px 0;'>
                    <span style='display:inline-block; font-size:28px; letter-spacing:5px; font-weight:bold; background:#f1f3f5; padding:12px 25px; border-radius:8px; color:#0d6efd;'>
                        {{codigo}}
                    </span>
                </div>

                <p style='font-size:14px; color:#555;'>
                    ⚠️ Este código es válido por <b>5 minutos</b> y no debe ser compartido con nadie.
                </p>

                <p style='font-size:14px; color:#555;'>
                    Si no fuiste tú quien intentó iniciar sesión, puedes ignorar este mensaje de forma segura.
                </p>

                <hr style='margin:20px 0; border:none; border-top:1px solid #eee;'>

                <p style='font-size:12px; color:#888; text-align:center;'>
                    Sistema de Seguridad - Clínica | Todos los derechos reservados
                </p>

            </div>
        </div>
    </div>
    "
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
