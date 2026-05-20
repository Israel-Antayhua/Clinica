<?php

include '../ConexionDB/conexion.php';

$id = $_GET['id'];

$sql1 = "SELECT id 
         FROM pagos 
         WHERE id_cita = $id";

$resultado1 = mysqli_query($conexion, $sql1);
$row1 = mysqli_fetch_assoc($resultado1);

if (!$row1) {
    die("No existe pago para esta cita");
}

$id_pago = $row1['id'];

$sql = "
SELECT 
    p.*,
    e.nombre AS especialidad,
    c.fecha,
    c.hora,
    CONCAT(pa.nombres, ' ',pa.apellidos) AS nombre_paciente,
    pa.celular AS celular,
    m.nombre AS nombre_medico

FROM pagos p

INNER JOIN citas c
ON p.id_cita = c.id
INNER JOIN pacientes pa
ON c.id_paciente = pa.id_paciente
INNER JOIN medicos m
ON c.id_medico = m.id
INNER JOIN especialidades e
ON m.id_especialidad = e.id

WHERE p.id = '$id_pago'
";

$r = $conexion->query($sql);

$f = $r->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Comprobante</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-lg border-0 mx-auto rounded-4"
            style="max-width:750px; overflow:hidden;">

            <!-- HEADER CLÍNICA -->
            <div class="p-4 text-white text-center"
                style="background: linear-gradient(135deg, #0d6efd, #20c997);">

                <h2 class="fw-bold mb-1">
                    Maison de Santé
                </h2>

                <small class="opacity-75">
                    Centro Médico - Comprobante de Atención
                </small>

            </div>

            <div class="card-body p-5 bg-light">

                <!-- BLOQUE PACIENTE -->
                <div class="mb-4 p-3 bg-white rounded-4 shadow-sm">

                    <h6 class="text-primary fw-bold mb-3">
                        🧑‍⚕️ Datos del Paciente
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Paciente:</strong><br>
                            <?php echo $f['nombre_paciente']; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Celular:</strong><br>
                            <?php echo $f['celular']; ?>
                        </div>
                    </div>

                </div>

                <!-- BLOQUE MÉDICO -->
                <div class="mb-4 p-3 bg-white rounded-4 shadow-sm">

                    <h6 class="text-success fw-bold mb-3">
                        👨‍⚕️ Atención Médica
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <strong>Médico:</strong><br>
                            <?php echo "Dr. " . htmlspecialchars($f['nombre_medico']); ?>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Especialidad:</strong><br>
                            <?php echo $f['especialidad']; ?>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Fecha:</strong><br>
                            <?php echo $f['fecha']; ?>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Hora:</strong><br>
                            <?php echo $f['hora']; ?>
                        </div>

                    </div>

                </div>

                <!-- BLOQUE PAGO -->
                <div class="mb-4 p-3 bg-white rounded-4 shadow-sm">

                    <h6 class="text-danger fw-bold mb-3">
                        💳 Información de Pago
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <strong>Monto:</strong><br>
                            <span class="fs-5 fw-bold text-success">
                                S/ <?php echo $f['monto']; ?>
                            </span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Método:</strong><br>
                            <?php echo $f['metodo_pago']; ?>
                        </div>

                        <div class="col-md-12 mb-2">
                            <strong>Código de operación:</strong><br>
                            <code><?php echo $f['culqi_charge_id']; ?></code>
                        </div>

                    </div>

                </div>

                <!-- BOTÓN -->
                <div class="text-center mt-4">

                    <button onclick="window.print()"
                        class="btn btn-lg btn-primary rounded-4 px-5 shadow-sm">

                        🖨️ Imprimir comprobante

                    </button>

                </div>

            </div>

        </div>

    </div>

</body>

</html>