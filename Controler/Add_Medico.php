<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("../ConexionDB/conexion.php");
include("../Dao/MedicoDAO.php");

$dao = new MedicoDAO($conexion);

/* =========================
   REGISTRAR / EDITAR MÉDICO
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'guardar') {

    $data = [
        "id" => $_POST["id"] ?? null,
        "nombre" => $_POST["nombre"],
        "telefono" => $_POST["telefono"],
        "usuario" => $_POST["usuario"],
        "password" => $_POST["password"] ?? "",
        "id_especialidad" => $_POST["id_especialidad"]
    ];
    // EDITAR
    if (!empty($data["id"])) {

        $resultado = $dao->editarMedico($data);

        $_SESSION['swal'] = [
            'icon' => $resultado ? 'success' : 'error',
            'title' => $resultado ? 'Actualizado' : 'Error',
            'text' => $resultado ? 'Médico actualizado correctamente' : $resultado
        ];

    } 
    // REGISTRAR
    else {

        $resultado = $dao->registrarMedico($data);
        
        $_SESSION['swal'] = [
            'icon' => $resultado ? 'success' : 'error',
            'title' => $resultado ? 'Correcto' : 'Error',
            'text' => $resultado ? 'Médico registrado correctamente' : $resultado
        ];
    }

    header("Location: ../Vistas/mantenimiento.php");
    exit;
}
