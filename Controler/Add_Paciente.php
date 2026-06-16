<?php
include("../ConexionDB/conexion.php");
include("../Dao/PacienteDAO.php");

$dao = new PacienteDAO($conexion);

$data = [
    "usuario" => $_POST["usuario"],
    "password" => $_POST["password"],
    "nombres" => $_POST["nombres"],
    "apellidos" => $_POST["apellidos"],
    "dni" => $_POST["dni"],
    "fecha_nacimiento" => $_POST["fecha_nacimiento"],
    "celular" => $_POST["celular"],
    "direccion" => $_POST["direccion"]
];

$resultado = $dao->registrarPaciente($data);

if ($resultado) {
    echo json_encode([
        "status" => "ok",
        "message" => "Usuario registrado"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrio un problema, ".$resultado
    ]);
}