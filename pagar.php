<?php
include 'conexion.php';
$id = $_GET['id'];

// Actualizamos el estado del pago en la base de datos
$sql = "UPDATE citas SET estado_pago = 'Pagado' WHERE id = $id";

if ($conexion->query($sql) === TRUE) {
    echo "<script>
            alert('¡Pago procesado con éxito en Maison de Santé!');
            window.location.href='index.php?vista=pagos';
          </script>";
} else {
    echo "Error al procesar el pago: " . $conexion->error;
}
$conexion->close();
?>