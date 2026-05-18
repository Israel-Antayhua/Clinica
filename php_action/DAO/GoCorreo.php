<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/PHPMailer-master/src/Exception.php';
require '../lib/PHPMailer-master/src/PHPMailer.php';
require '../lib/PHPMailer-master/src/SMTP.php';
require '../Vista/core.php';


function enviarCorreo($para, $asunto, $mensajeHtml) {
    $mail = new PHPMailer(true);

    try {
        // CONFIGURA TU SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;

        $mail->Username = 'anderachu7@gmail.com';
        $mail->Password = 'enmc pjru ocep lbio';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // REMITENTE
        $mail->setFrom('anderachu7@gmail.com', 'Sistema de Tickets');

        // DESTINATARIO
        $mail->addAddress($para);

        // CONTENIDO
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensajeHtml;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
function enviarCorreoAsignacion($connect,$user_id_asignado, $serie_ticket, $asunto, $mensaje) {
    
    // Obtener correo del técnico
    $sql = "SELECT email, username FROM users WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $user_id_asignado);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return; // No existe usuario
    }

    $row = $result->fetch_assoc();
    $correo = $row['email'];
    $usuario = $row['username'];

    // Construir mensaje del correo
    $subject = "Nuevo Ticket Asignado: $serie_ticket";
    $body = "
        Hola $usuario,<br><br>
        Se te ha asignado un nuevo ticket.<br><br>
        <strong>Ticket:</strong> $serie_ticket <br>
        <strong>Asunto:</strong> $asunto <br>
        <strong>Descripción:</strong> $mensaje <br><br>
        Por favor ingresa al sistema para revisarlo.
    ";

    enviarCorreo($correo, $subject, $body);
}
?>

