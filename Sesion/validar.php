<?php
session_start();
include '../ConexionDB/conexion.php';

$usuario  = $_POST['usuario'];
$password = $_POST['password'];


$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {

    $datos = $resultado->fetch_assoc();

    if (password_verify($password, $datos['password'])) {

        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];
        $id_usuario = $datos['id'];
        if ($datos['rol'] == "paciente") {
            $sql2 = "SELECT id_paciente FROM pacientes WHERE id_usuario = ?";
            $stmt = $conexion->prepare($sql2);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();

            $result = $stmt->get_result();
            $paciente = $result->fetch_assoc();
            $_SESSION['id_usuario'] = $paciente['id_paciente'];
        } else{
            $sql = "SELECT id
             FROM medicos
             WHERE id_usuario = ?";

            $stmt2 = $conexion->prepare($sql);
            $stmt2->bind_param("i", $id_usuario);
            $stmt2->execute();

            $result2 = $stmt2->get_result();
            $medico = $result2->fetch_assoc();

            $_SESSION['id_medico'] = $medico['id'];
        }
        header("Location: ../index.php");
        exit;
    } else {
        echo "<script>
            alert('Contraseña incorrecta');
            window.location.href='../Sesion/login.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Usuario no existe');
        window.location.href='../Sesion/login.php';
    </script>";
}

$conexion->close();
