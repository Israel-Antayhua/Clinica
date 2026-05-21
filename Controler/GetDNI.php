<?php
session_start();
include '../ConexionDB/conexion.php';

$dni = $_GET['dni'];

$sql = "SELECT CONCAT(nombres,' ',apellidos) AS nombre,
        id_paciente
        FROM pacientes
        WHERE dni = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("s", $dni);

$stmt->execute();

$result = $stmt->get_result();

$paciente = $result->fetch_assoc();

header('Content-Type: application/json');

echo json_encode($paciente);
?>
