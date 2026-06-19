<?php
include("../ConexionDB/conexion.php");

$id = $_GET['id'];

$sql = "SELECT m.id, m.nombre AS nombre
        FROM medicos m
        INNER JOIN usuarios u ON m.id_usuario = u.id
        WHERE m.id_especialidad = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$medicos = [];

while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($medicos);
?>