<?php
// 1. Incluimos la conexión
include '../ConexionDB/conexion.php';

$estilo_pagina = "../css/estilos.css";

include '../includes/cabecera.php';

// 2. Recibimos los datos del formulario
$nombre       = $_POST['nombre'];
$fecha        = $_POST['fecha'];
$hora         = $_POST['hora'];
$especialidad = $_POST['especialidad'];

// ==========================================
// NUEVO: VALIDACIÓN DE CRUCE DE CITAS
// ==========================================
// Le preguntamos a MySQL: ¿Hay alguna cita con la misma fecha, hora y especialidad?
$buscar_cruce = "SELECT * FROM citas WHERE fecha = '$fecha' AND hora = '$hora' AND especialidad = '$especialidad'";
$resultado_cruce = $conexion->query($buscar_cruce);

// Si el número de filas es mayor a 0, significa que YA existe una cita idéntica
if ($resultado_cruce->num_rows > 0) {
    // Frenamos el registro y le avisamos al paciente
    echo "<script>
            alert('Lo sentimos, este horario ya está reservado para la especialidad de $especialidad. Por favor, elige otra hora o fecha.');
            window.location.href='index.php';
          </script>";
} else {
    // SI NO HAY CRUCE: Procedemos a guardar la cita normalmente
    $sql = "INSERT INTO citas (nombre_paciente, fecha, hora, especialidad) 
            VALUES ('$nombre', '$fecha', '$hora', '$especialidad')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>
                alert('¡Cita registrada con éxito!');
                window.location.href='../index.php';
              </script>";
    } else {
        echo "Error al registrar la cita: " . $conexion->error;
    }
}

// Cerramos la conexión
$conexion->close();
?>