<?php
include("../ConexionDB/conexion.php");
include("../Dao/MedicoDAO.php");

$dao = new MedicoDAO($conexion);

$data = [
    "usuario" => $_POST["usuario"],
    "password" => $_POST["password"],
    "telefono" => $_POST["telefono"],
    "id_especialidad" => $_POST["id_especialidad"]
];

$resultado = $dao->registrarMedico($data);

if ($resultado === true) {
    header("Location: ../Vistas/mantenimiento.php?msg=ok");
    exit;
} else {
    echo $resultado;
}