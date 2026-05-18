<?php
// 1. Conectamos a la base de datos
include 'conexion.php';

// 2. Capturamos el 'id' de la cita que viaja a través de la URL
$id = $_GET['id'];

// 3. Preparamos la orden SQL para borrar la fila que tenga ese ID específico
$sql = "DELETE FROM citas WHERE id = $id";

// 4. Ejecutamos la orden y verificamos
if ($conexion->query($sql) === TRUE) {
    echo "<script>
            alert('Cita eliminada correctamente');
            window.location.href='index.php';
          </script>";
} else {
    echo "Error al eliminar la cita: " . $conexion->error;
}

// 5. Cerramos la conexión
$conexion->close();
?>