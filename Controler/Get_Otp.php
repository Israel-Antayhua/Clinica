<?php
session_start();
include '../ConexionDB/conexion.php';
header('Content-Type: application/json');
$sql = "SELECT u.correo as correo FROM pacientes p
INNER JOIN usuarios u On p.id_usuario = u.id WHERE p.id_paciente = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$to = $result['correo'];
require '../Sesion/codigo.php';
$data = json_decode(file_get_contents("php://input"), true);
$titulo = $data['titulo'];
$asunto = $data['asunto'];
$result = enviarCorreo(
    $to,
    $asunto,
    $titulo
);
echo json_encode($result);
