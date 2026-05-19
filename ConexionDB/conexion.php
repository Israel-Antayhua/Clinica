<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario  = "root";     // Usuario por defecto de XAMPP
$clave    = "";         // Por defecto en XAMPP viene sin contraseña
$base_datos = "clinica_db";

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar si la conexión falló
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}
?>