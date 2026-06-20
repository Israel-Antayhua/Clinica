<?php
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

function enviarCorreo($to, $asunto, $titulo, $cuerpo)
{
    // 🔐 generar OTP si lo necesitas dentro del cuerpo
    $codigo = rand(100000, 999999);
    $env = parse_ini_file(__DIR__ . '/../.env');

    if (!$env) {
        throw new Exception("No se pudo cargar .env");
    }
    $modo_dev = true;
    $_SESSION['otp'] = $codigo;
    $_SESSION['otp_time'] = time();

    // reemplazar en el cuerpo si quieres usar {{codigo}}
     $cuerpo = str_replace("{codigo}", $codigo, $cuerpo);

    if ($modo_dev) {
        return [
            "status" => "otp_sent",
            "debug_otp" => $modo_dev ? $codigo : null
        ];
    }

    try {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $env['SMTP_HOST'] ?? '';
    $mail->SMTPAuth = true;
    $mail->Username = $env['SMTP_USER'] ?? '';
    $mail->Password = $env['SMTP_PASS'] ?? '';
    $mail->SMTPSecure = 'tls';
    $mail->Port = $env['SMTP_PORT'] ?? 587;

    $mail->setFrom('ClinicaMaisonSante@gmail.com', 'Clinica Maison Sante');
    $mail->addAddress($to);

    $mail->Subject = $asunto;
    $mail->isHTML(true);

    $mail->Body = "
        <h3>$titulo</h3>
        <div>$cuerpo</div>
    ";

    $mail->send();

    return ["status" => "otp_sent",
        "codigo" => $codigo];

} catch (Exception $e) {
    return [
        "status" => "error",
        "message" => $mail->ErrorInfo ?? $e->getMessage()
    ];
}
}
