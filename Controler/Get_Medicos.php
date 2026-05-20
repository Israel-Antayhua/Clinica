<?php
header('Content-Type: application/json');
include("../ConexionDB/conexion.php");

$id = $_GET['id'];

$sql = "SELECT m.id, u.usuario AS nombre
        FROM medicos m
        INNER JOIN usuarios u ON m.id = u.id
        WHERE m.id_especialidad = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$medicos = [];

while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
}

echo json_encode($medicos);
?>