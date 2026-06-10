<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$codigo = $_POST['codigo'] ?? '';
$otp = $_SESSION['otp'] ?? null;

if (!$otp) {
    echo json_encode([
        "status" => "error",
        "message" => "No hay código activo"
    ]);
    exit;
}

if ($codigo == $otp) {

    $_SESSION['usuario'] = $_SESSION['temp_user'];

    unset($_SESSION['otp']);
    unset($_SESSION['temp_user']);

    echo json_encode([
        "status" => "ok"
    ]);
    exit;
}

echo json_encode([
    "status" => "error",
    "message" => "Código incorrecto"
]);
exit;
