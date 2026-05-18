<?php
session_start();
require_once(__DIR__ . '/../../Vista/core.php');

$id_usuario = $_SESSION['userId'];
$id_conversacion = $_GET['id'];

$sql = "SELECT id_remitente, contenido 
        FROM mensajes 
        WHERE id_conversacion = ?
        ORDER BY fecha ASC";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $id_conversacion);
$stmt->execute();
$res = $stmt->get_result();

$data = [];

while ($m = $res->fetch_assoc()) {
    $data[] = [
        "texto" => $m["contenido"],
        "mio"   => $m["id_remitente"] == $id_usuario
    ];
}

echo json_encode($data);
