<?php
require_once(__DIR__ . '/../../Vista/core.php');

$id_actual = $_SESSION['userId'];

$sql = "SELECT user_id AS id, username FROM users WHERE user_id != ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $id_actual);
$stmt->execute();
$res = $stmt->get_result();

$usuarios = [];
while ($row = $res->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
