<?php
session_start();
include("../ConexionDB/conexion.php");
include("../Dao/EspeciaDAO.php");

$dao = new EspeciaDAO($conexion);
/* =========================
   Modificar Tabla (AJAX)
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $sql = "UPDATE especialidades 
            SET nombre = ?, precio_consulta = ? 
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sdi", $nombre, $precio, $id);
    $stmt->execute();

    $_SESSION['swal'] = [
        'icon' => 'success',
        'title' => 'Actualizado',
        'text' => 'Especialidad actualizada correctamente'
    ];
    echo json_encode([
        "status" => "ok",
        "id" => $id,
        "nuevo_precio" => $precio,
        "nuevo_nombre" => $nombre
    ]);
    exit;
}

/* =========================
   CAMBIAR ESTADO (AJAX)
========================= */
if (isset($_POST['accion']) && $_POST['accion'] == 'estado') {

    $id = $_POST['id'];

    $nuevoEstado = $dao->cambiarEstado($id);

    echo json_encode([
        "success" => $nuevoEstado ? true : false,
        "estado" => $nuevoEstado
    ]);
    exit;
}

/* =========================
   REGISTRAR ESPECIALIDAD
========================= */

if (isset($_POST['accion']) && $_POST['accion'] == 'insertar') {

    $data = [
        "nombre" => $_POST["nombre"],
        "precio" => $_POST["precio_consulta"]
    ];

    $resultado = $dao->registrarEspecia($data);

    echo json_encode([
        "status" => "ok",
        "nombre" => $data['nombre'],
        "precio" => $data['precio']
    ]);
    exit;
}
