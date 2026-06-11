<?php
session_start();
header('Content-Type: application/json');
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

include '../ConexionDB/conexion.php';

$env = parse_ini_file(__DIR__ . '/../.env');
$smtpUser = $env['SMTP_USER'];
$smtpPass = $env['SMTP_PASS'];
$smtpHost = $env['SMTP_HOST'];
$smtpPort = $env['SMTP_PORT'];

$usuario  = $_POST['usuario'];
$password = $_POST['password'];
$modo_dev = true;

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
        // 🔐 generar código
        $codigo = rand(100000, 999999);

        $_SESSION['otp'] = $codigo;
        $_SESSION['otp_time'] = time();
        $_SESSION['rol_temp'] = $datos['rol'];

        if ($modo_dev) {
            echo json_encode([
                "status" => "otp_sent",
                "debug_otp" => $modo_dev ? $codigo : null
            ]);
            exit;
        } else {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $smtpPort;

            $mail->setFrom('ClinicaMaisonSante@gmail.com', 'Clinica Maison Sante');
            $mail->addAddress($usuario);

            $mail->Subject = "Codigo de verificacion";
            $mail->Body = "Tu código es: $codigo";

            $mail->send();
            echo json_encode(["status" => "otp_sent"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Usuario no existe"]);
}

$conexion->close();
