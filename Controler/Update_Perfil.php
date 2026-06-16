<?php
session_start();
include '../ConexionDB/conexion.php';
include '../DAO/PerfilDAO.php';

$perfilDAO = new PerfilDAO($conexion);

$idPaciente = $_SESSION['id_usuario'];

$nombres = trim($_POST['nombres']);
$apellidos = trim($_POST['apellidos']);
$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
$correo = trim($_POST['correo']);

$modificar = $perfilDAO->actualizarPerfil($nombres,$apellidos,$telefono,$direccion,$correo,$idPaciente);

if ($modificar) {

    echo json_encode([
        "status" => "ok",
        "message" => "Perfil actualizado"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo actualizar"
    ]);
}