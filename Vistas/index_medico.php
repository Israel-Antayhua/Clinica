<?php
session_start();
include '../includes/cabecera.php';
date_default_timezone_set('America/Lima');
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
include '../ConexionDB/conexion.php';
if (isset($_SESSION['swal']));
if ($_SESSION['rol'] == 'medico'): ?>
    <!-- CONTENIDO PRINCIPAL -->
    <div class="container-fluid py-4">

        <!-- BIENVENIDA -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    Bienvenido Dr. <?php echo $_SESSION['usuario']; ?>
                </h2>

                <p class="text-secondary mb-0">
                    Panel principal de atención médica
                </p>

            </div>

            <div class="bg-white shadow-sm rounded-4 px-5 py-3"
                style="min-width:200px;">

                <div class="small text-secondary">
                    Fecha actual
                </div>

                <div class="fw-semibold fs-5">
                    <?php echo date('d/m/Y'); ?>
                </div>

            </div>

        </div>

        <!-- CARDS -->
        <div class="row g-4 mb-4">
            <!-- CITAS HOY -->
            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-secondary small">
                                    Citas Hoy
                                </div>

                                <h2 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT COUNT(*) AS cantidad
                                    FROM citas
                                    WHERE fecha = ? AND id_medico = ?");
                                    $cont_citas->bind_param("si", $fecha, $_SESSION['id_medico']);
                                    $cont_citas->execute();
                                    $result = $cont_citas->get_result();
                                    $fila = $result->fetch_assoc();
                                    ?>
                                    <?php
                                    if ($fila['cantidad'] > 0) {
                                        echo $fila['cantidad'];
                                    } else {
                                        echo $fila['cantidad'];
                                    } ?>
                                </h2>

                            </div>

                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:60px;height:60px;">

                                <i class="bi bi-calendar-check fs-3 text-primary"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- PACIENTES -->
            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-secondary small">
                                    Pacientes Mes
                                </div>

                                <h2 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT COUNT(DISTINCT id_paciente) AS total
                                    FROM citas
                                    WHERE id_medico = ? 
                                    AND fecha < CURDATE()");
                                    $cont_citas->bind_param("i", $_SESSION['id_medico']);
                                    $cont_citas->execute();
                                    $result = $cont_citas->get_result();
                                    $fila = $result->fetch_assoc();
                                    ?>
                                    <?php echo $fila['total'] ?? 'Sin citas'; ?>
                                </h2>

                            </div>

                            <div class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:60px;height:60px;">

                                <i class="bi bi-people fs-3 text-success"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- PROXIMA CITA -->
            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-secondary small">
                                    Próxima Cita
                                </div>

                                <h5 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT fecha, hora
                                        FROM citas
                                        WHERE (
                                            fecha = CURDATE() AND hora > CURTIME()
                                        ) OR fecha > CURDATE()
                                        AND id_medico = ?
                                        ORDER BY fecha ASC, hora ASC
                                        LIMIT 1;
                                    ");
                                    $cont_citas->bind_param("i", $_SESSION['id_medico']);
                                    $cont_citas->execute();
                                    $result = $cont_citas->get_result();
                                    $fila = $result->fetch_assoc();
                                    ?>
                                    <?php echo $fila['hora'] ?? 'Sin citas'; ?>
                                </h5>

                            </div>

                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:60px;height:60px;">

                                <i class="bi bi-clock-history fs-3 text-warning"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- PENDIENTES -->
            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-secondary small">
                                    Pendientes
                                </div>

                                <h2 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT COUNT(*) AS cantidad
                                    FROM citas
                                    WHERE hora > CURTIME()
                                    AND fecha = CURDATE()
                                    AND id_medico = ?
                                    ");
                                    $cont_citas->bind_param("i", $_SESSION['id_medico']);
                                    $cont_citas->execute();
                                    $result = $cont_citas->get_result();
                                    $fila = $result->fetch_assoc();
                                    ?>
                                    <?php
                                    if ($fila['cantidad'] > 0) {
                                        echo $fila['cantidad'];
                                    } else {
                                        echo $fila['cantidad'];
                                    } ?>
                                </h2>

                            </div>

                            <div class="bg-danger bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:60px;height:60px;">

                                <i class="bi bi-exclamation-circle fs-3 text-danger"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- AGENDA + PANEL -->
        <div class="row g-4">

            <!-- AGENDA -->
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h5 class="fw-bold mb-0">
                                Agenda del Día
                            </h5>

                            <a href="agenda.php"
                                class="btn btn-primary rounded-4 px-4 d-inline-flex align-items-center">
                                Ir a Agenda
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>

                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr class="text-center">

                                        <th>Hora</th>
                                        <th>Paciente</th>
                                        <th>Especialidad</th>
                                        <th>Estado</th>
                                        <th>Accion</th>

                                    </tr>

                                </thead>

                                <tbody>
                                    <?php
                                    $agenda = $conexion->prepare("SELECT c.*,p.nombres AS nombre_paciente,e.nombre AS especialidad
                                    FROM citas c 
                                    INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                                    INNER JOIN medicos m  ON c.id_medico = m.id 
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    WHERE fecha = ? AND id_medico = ?
                                    ORDER BY hora ASC");
                                    $agenda->bind_param("si", $fecha, $_SESSION['id_medico']);
                                    $agenda->execute();

                                    $resultado = $agenda->get_result();
                                    ?>
                                    <?php if ($resultado->num_rows > 0): ?>

                                        <?php while ($f = $resultado->fetch_assoc()): ?>
                                            <?php
                                            $estado = strtolower($f['estado']);
                                            $color = "primary";

                                            if ($estado == "confirmada") $color = "success";
                                            if ($estado == "pendiente") $color = "warning";
                                            if ($estado == "cancelada") $color = "danger";
                                            ?>
                                            <tr class="text-center">

                                                <td><?php echo $f['hora']; ?></td>

                                                <td><?php echo htmlspecialchars($f['nombre_paciente']); ?></td>

                                                <td><?php echo $f['especialidad']; ?></td>

                                                <td>

                                                    <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> rounded-pill px-3 py-2">
                                                        <?php echo $f['estado']; ?>
                                                    </span>

                                                </td>

                                                <td>

                                                    <button class="btn btn-sm btn-light border rounded-3">

                                                        <i class="bi bi-eye"></i>

                                                    </button>

                                                </td>

                                            </tr>
                                        <?php endwhile; ?>

                                    <?php else: ?>

                                        <div class="alert alert-warning text-center">
                                            No hay citas para esta fecha
                                        </div>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PANEL DERECHO -->
            <div class="col-lg-4">

                <!-- PROXIMAS CITAS -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Próximas Citas
                        </h5>
                        <?php
                        $agenda = $conexion->prepare("SELECT c.*,p.nombres AS nombre_paciente,e.nombre AS especialidad
                        FROM citas c 
                        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                        INNER JOIN medicos m  ON c.id_medico = m.id 
                        INNER JOIN especialidades e ON m.id_especialidad = e.id
                        WHERE fecha = CURDATE()
                        AND hora > CURTIME() 
                        AND id_medico = ?
                        ORDER BY hora ASC");
                        $agenda->bind_param("i", $_SESSION['id_medico']);
                        $agenda->execute();

                        $resultado = $agenda->get_result();
                        ?>
                        <?php if ($resultado->num_rows > 0): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php while ($f = $resultado->fetch_assoc()): ?>

                                    <div class="border rounded-4 p-3">

                                        <div class="fw-semibold">
                                            <?php echo htmlspecialchars($f['nombre_paciente']); ?>
                                        </div>

                                        <small class="text-secondary">
                                            <?php echo $f['hora']; ?>
                                        </small>

                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>

                            <div class="alert alert-warning text-center">
                                No hay citas para hoy
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                <!-- ALERTAS -->
                <div class="card border-0 shadow-sm rounded-4 my-2">

                    <div class="card-body">

                        <!-- TITULO -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h5 class="fw-bold mb-0">

                                Próximo Paciente

                            </h5>

                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:50px; height:50px;">

                                <i class="bi bi-person-heart text-primary fs-5"></i>

                            </div>

                        </div>

                        <!-- CONTENIDO -->
                        <div class="border rounded-4 p-4 bg-light bg-opacity-50">

                            <!-- PERFIL -->
                            <div class="d-flex align-items-center gap-3 mb-4">

                                <div class="bg-white shadow-sm rounded-circle d-flex justify-content-center align-items-center"
                                    style="width:65px; height:65px;">

                                    <i class="bi bi-person-fill fs-2 text-secondary"></i>

                                </div>
                                <?php
                                $cont_citas = $conexion->prepare("SELECT c.*,CONCAT(p.nombres,' ',p.apellidos)AS nombre,
                                    p.correo, p.celular,TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad
                                    FROM citas c
                                    INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                                    WHERE (
                                            fecha = CURDATE() AND hora > CURTIME()
                                        ) OR fecha > CURDATE()
                                    AND id_medico = ?
                                    ORDER BY fecha ASC, hora ASC
                                    LIMIT 1
                                    ");
                                $cont_citas->bind_param("i", $_SESSION['id_medico']);
                                $cont_citas->execute();
                                $result = $cont_citas->get_result();
                                $fila = $result->fetch_assoc();
                                ?>
                                <div>
                                    <input type="hidden" id="fechaCita" value="<?php echo htmlspecialchars($fila['fecha'] ?? ''); ?>">
                                    <input type="hidden" id="horaCita" value="<?php echo htmlspecialchars($fila['hora'] ?? ''); ?>">

                                    <h5 class="fw-bold mb-1">

                                        <?php echo htmlspecialchars($fila['nombre'] ?? ''); ?>

                                    </h5>

                                    <small class="text-secondary">

                                        Paciente programado

                                    </small>

                                </div>

                            </div>

                            <!-- DATOS -->
                            <div class="d-flex flex-column gap-3">

                                <!-- EDAD -->
                                <div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-calendar2-heart text-danger"></i>

                                        <span class="fw-semibold">
                                            Edad
                                        </span>

                                    </div>

                                    <span class="text-secondary">
                                        <?php echo htmlspecialchars($fila['edad'] ?? ''); ?> años
                                    </span>

                                </div>

                                <!-- CORREO -->
                                <div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-envelope text-primary"></i>

                                        <span class="fw-semibold">
                                            Correo
                                        </span>

                                    </div>

                                    <span class="text-secondary small">
                                        <?php echo htmlspecialchars($fila['correo'] ?? ''); ?>
                                    </span>

                                </div>

                                <!-- CELULAR -->
                                <div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-telephone text-success"></i>

                                        <span class="fw-semibold">
                                            Celular
                                        </span>

                                    </div>

                                    <span class="text-secondary">
                                        <?php echo htmlspecialchars($fila['celular'] ?? ''); ?>
                                    </span>

                                </div>

                                <!-- HORA -->
                                <div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-clock text-warning"></i>

                                        <span class="fw-semibold">
                                            Hora
                                        </span>

                                    </div>

                                    <span class="text-secondary">
                                        <?php echo htmlspecialchars($fila['hora'] ?? ''); ?>
                                    </span>

                                </div>

                                <!-- TIEMPO RESTANTE -->
                                <div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-hourglass-split text-info"></i>

                                        <span class="fw-semibold">
                                            Tiempo restante
                                        </span>

                                    </div>
                                    <span class="text-secondary small" id="tiempoRestante">
                                    </span>
                                    <input type="hidden"
                                        id="horaCita"
                                        value="<?php echo $fila['hora'] ?? ''; ?>">
                                </div>

                                <!-- ESTADO -->
                                <!--<div class="d-flex justify-content-between align-items-center border rounded-4 px-3 py-2 bg-white">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-check-circle text-success"></i>

                                        <span class="fw-semibold">
                                            Estado
                                        </span>

                                    </div>

                                    <span class="badge bg-warning rounded-pill px-3 py-2">

                                        Pendiente

                                    </span>

                                </div>-->

                            </div>

                            <!-- BOTON -->
                            <div class="mt-4">

                                <button class="btn btn-primary w-100 rounded-4 py-2">

                                    <i class="bi bi-eye me-2"></i>

                                    Ver Perfil del Paciente

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script src="../js/time.js"></script>
<?php endif; ?>