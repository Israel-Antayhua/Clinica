<?php
session_start();
require_once(__DIR__ . '/../../Vista/core.php');

$yo = $_SESSION['userId'];
$otro = $_GET['otro'];

$sql = "SELECT id_conversacion FROM conversaciones
        WHERE (usuario1=? AND usuario2=?) 
        OR (usuario1=? AND usuario2=?)";

$stmt = $connect->prepare($sql);
$stmt->bind_param("iiii", $yo, $otro, $otro, $yo);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode(["id_conversacion" => $row['id_conversacion']]);
    exit;
}

// Crear conversación si no existe
$sql = "INSERT INTO conversaciones (usuario1, usuario2) VALUES (?, ?)";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ii", $yo, $otro);
$stmt->execute();

echo json_encode(["id_conversacion" => $stmt->insert_id]);
