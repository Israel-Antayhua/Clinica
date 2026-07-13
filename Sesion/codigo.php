<?php
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

function enviarCorreo($to, $asunto, $titulo)
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

    if ($asunto == "OTP") {
        $cuerpo = "
            <div style='font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;'>

                <div style='max-width:580px;margin:auto;background:#ffffff;border-radius:15px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,.12);'>

                    <div style='background:linear-gradient(135deg,#0d6efd,#3b82f6);padding:22px;text-align:center;color:#fff;'>
                        <h2 style='margin:0;'>🔐 Verificación de Inicio de Sesión</h2>
                    </div>

                    <div style='padding:30px;color:#333;'>

                        <p style='font-size:16px;margin-top:0;'>
                            Hola,
                        </p>

                        <p style='font-size:15px;line-height:1.7;'>
                            Hemos recibido una <strong>solicitud para iniciar sesión</strong> en tu cuenta de la
                            <strong>Clínica Maison Sante</strong>.
                        </p>

                        <p style='font-size:15px;line-height:1.7;'>
                            Para continuar, ingresa el siguiente código de verificación:
                        </p>

                        <div style='text-align:center;margin:30px 0;'>
                            <span style='display:inline-block;background:#eef5ff;color:#0d6efd;font-size:32px;font-weight:bold;letter-spacing:8px;padding:15px 35px;border-radius:10px;border:2px dashed #0d6efd;'>
                                {$codigo}
                            </span>
                        </div>

                        <div style='background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:15px;margin-top:20px;'>
                            <p style='margin:0;font-size:14px;color:#555;'>
                                ⚠️ Este código es válido durante <strong>5 minutos</strong>. No lo compartas con nadie.
                            </p>
                        </div>

                        <div style='background:#f8f9fa;border-left:4px solid #0d6efd;padding:15px 18px;border-radius:8px;margin-top:20px;'>
                            <strong>🕒 Fecha y hora de la solicitud</strong><br>
                            " . date('d/m/Y h:i A') . "
                        </div>

                        <p style='font-size:14px;color:#555;line-height:1.6;margin-top:20px;'>
                            Si no intentaste iniciar sesión, puedes ignorar este mensaje. Tu cuenta permanecerá protegida y el acceso no se completará sin este código.
                        </p>

                        <hr style='border:none;border-top:1px solid #e5e5e5;margin:30px 0;'>

                        <p style='text-align:center;font-size:12px;color:#888;margin:0;'>
                            Clínica Maison Sante<br>
                            Sistema de Seguridad
                        </p>
                    </div>
                </div>
            </div>";
    } elseif ($asunto == "Cambio de horario de cita") {
        $cuerpo = "
            <div style='font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;'>

                <div style='max-width:580px;margin:auto;background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,.12);'>

                    <div style='background:linear-gradient(135deg,#198754,#20c997);padding:20px;text-align:center;color:#fff;'>
                        <h2 style='margin:0;'>📅 Solicitud de Cambio de Horario</h2>
                    </div>

                    <div style='padding:30px;color:#333;'>

                        <p style='font-size:16px;margin-top:0;'>
                            Hola,
                        </p>

                        <p style='font-size:15px;line-height:1.7;'>
                            Hemos recibido una <strong>solicitud para modificar el horario de tu cita médica</strong>.
                            Para proteger tu cuenta, necesitamos verificar que esta solicitud fue realizada por ti.
                        </p>

                        <div style='background:#f8f9fa;border-left:4px solid #198754;padding:15px 18px;border-radius:8px;margin:20px 0;'>
                            <strong>🕒 Fecha y hora de la solicitud</strong><br>
                            " . date('d/m/Y h:i A') . "
                        </div>

                        <p style='font-size:15px;'>
                            Ingresa el siguiente código de verificación para confirmar el cambio:
                        </p>

                        <div style='text-align:center;margin:30px 0;'>
                            <span style='display:inline-block;background:#eef6ff;color:#198754;font-size:32px;font-weight:bold;letter-spacing:8px;padding:15px 35px;border-radius:10px;border:2px dashed #198754;'>
                                {$codigo}
                            </span>
                        </div>

                        <div style='background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:15px;margin-top:20px;'>
                            <p style='margin:0;font-size:14px;color:#555;'>
                                ⚠️ Este código es válido durante <strong>5 minutos</strong>. No lo compartas con ninguna persona.
                            </p>
                        </div>

                        <p style='font-size:14px;color:#555;margin-top:20px;line-height:1.6;'>
                            Si no realizaste esta solicitud, simplemente ignora este correo. Tu cita <strong>no será modificada</strong> sin la verificación del código.
                        </p>

                        <hr style='border:none;border-top:1px solid #e5e5e5;margin:30px 0;'>

                        <p style='text-align:center;font-size:12px;color:#888;margin:0;'>
                            Clínica Maison Sante<br>
                            Sistema de Gestión de Citas
                        </p>
                    </div>
                </div>
            </div>";
    } elseif ($asunto == "Cancelación de cita") {
        $cuerpo = "
            <div style='font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;'>

                <div style='max-width:580px;margin:auto;background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,.12);'>

                    <div style='background:linear-gradient(135deg,#dc3545,#ff6b6b);padding:20px;text-align:center;color:#fff;'>
                        <h2 style='margin:0;'>❌ Solicitud de Cancelación de Cita</h2>
                    </div>

                    <div style='padding:30px;color:#333;'>

                        <p style='font-size:16px;margin-top:0;'>
                            Hola,
                        </p>

                        <p style='font-size:15px;line-height:1.7;'>
                            Hemos recibido una <strong>solicitud para cancelar tu cita médica</strong>.
                            Antes de realizar esta acción, necesitamos verificar tu identidad.
                        </p>

                        <div style='background:#f8f9fa;border-left:4px solid #dc3545;padding:15px 18px;border-radius:8px;margin:20px 0;'>
                            <strong>🕒 Fecha y hora de la solicitud</strong><br>
                            " . date('d/m/Y h:i A') . "
                        </div>

                        <p style='font-size:15px;'>
                            Ingresa el siguiente código de verificación para confirmar la cancelación:
                        </p>

                        <div style='text-align:center;margin:30px 0;'>
                            <span style='display:inline-block;background:#fff5f5;color:#dc3545;font-size:32px;font-weight:bold;letter-spacing:8px;padding:15px 35px;border-radius:10px;border:2px dashed #dc3545;'>
                                {$codigo}
                            </span>
                        </div>

                        <div style='background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:15px;margin-top:20px;'>
                            <p style='margin:0;font-size:14px;color:#555;'>
                                ⚠️ Este código es válido durante <strong>5 minutos</strong>. No lo compartas con ninguna persona.
                            </p>
                        </div>

                        <p style='font-size:14px;color:#555;margin-top:20px;line-height:1.6;'>
                            Si no realizaste esta solicitud, ignora este correo. Tu cita <strong>no será cancelada</strong> sin ingresar este código de verificación.
                        </p>

                        <hr style='border:none;border-top:1px solid #e5e5e5;margin:30px 0;'>

                        <p style='text-align:center;font-size:12px;color:#888;margin:0;'>
                            Clínica Maison Sante<br>
                            Sistema de Gestión de Citas
                        </p>

                    </div>

                </div>

            </div>";
    }

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

        return [
            "status" => "otp_sent",
            "codigo" => $codigo
        ];
    } catch (Exception $e) {
        return [
            "status" => "error",
            "message" => $mail->ErrorInfo ?? $e->getMessage()
        ];
    }
}
