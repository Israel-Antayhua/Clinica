<?php
session_start();
header('Content-Type: application/json');

require '../Sesion/codigo.php';
$data = json_decode(file_get_contents("php://input"), true);
$titulo = $data['titulo'];
$asunto = $data['asunto'];
$cuerpo = $data['cuerpo'];
$result = enviarCorreo(
    $_SESSION['usuario'],
    $asunto,
    $titulo,
    $cuerpo
);
echo json_encode($result);
