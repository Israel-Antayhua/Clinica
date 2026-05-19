<?php
// Iniciamos la sesión para poder guardar los datos del usuario
session_start();
include '../ConexionDB/conexion.php';

$usuario  = $_POST['usuario'];
$password = $_POST['password'];

// Buscamos si existe el usuario con esa contraseña
$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$resultado = $conexion->query($consulta);

// Si encuentra exactamente 1 fila, las credenciales son correctas
if ($resultado->num_rows == 1) {
    $datos_usuario = $resultado->fetch_assoc();
    
    // Guardamos el nombre y el rol en la sesión de la computadora
    $_SESSION['usuario'] = $datos_usuario['usuario'];
    $_SESSION['rol']     = $datos_usuario['rol'];
    
    // Lo redirigimos a la página principal del sistema
    header("Location: ../index.php");
} else {
    // Si los datos son incorrectos, mandamos un aviso y lo regresamos al login
    echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location.href='../Sesion/login.php';
          </script>";
}

$conexion->close();
?>