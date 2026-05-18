<?php
session_start();
require_once(__DIR__ . '/../../Vista/core.php');

$yo = $_SESSION['userId'];
$id_conversacion = $_POST['id_conversacion'];
$contenido = $_POST['contenido'];

$sql = "INSERT INTO mensajes (id_conversacion, id_remitente, contenido)
        VALUES (?, ?, ?)";

$stmt = $connect->prepare($sql);
$stmt->bind_param("iis", $id_conversacion, $yo, $contenido);
$stmt->execute();

echo "ok";
