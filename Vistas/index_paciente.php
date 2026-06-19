<?php
include '../includes/cabecera.php';
date_default_timezone_set('America/Lima');
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
include '../ConexionDB/conexion.php';
if (isset($_SESSION['swal']));
if ($_SESSION['rol'] == 'paciente'): ?>
    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <!-- Bienvenida paciente -->
            <div>
                <h2 class="fw-bold mb-1 text-success">
                    👤 Bienvenido/a <?php echo $_SESSION['usuario']; ?>
                </h2>

                <p class="text-muted mb-0">
                    Consulta tus citas y estado de atención
                </p>
            </div>

            <!-- Fecha + hora -->
            <div class="card border-0 shadow-sm rounded-4 px-4 py-3 bg-light text-center"
                style="min-width:200px;">

                <div class="text-muted   mb-2">
                    📅 Fecha y hora actual
                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <!-- Fecha -->
                    <div class="fw-bold text-dark">
                        <?php echo date('d/m/Y'); ?>
                    </div>

                    <!-- Hora -->
                    <div class="fw-semibold text-success" id="reloj">
                        --:--:--
                    </div>

                </div>

            </div>

        </div>

        <script>
            function actualizarHora() {
                const ahora = new Date();

                const hora = ahora.toLocaleTimeString('es-PE', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                document.getElementById("reloj").textContent = hora;
            }

            actualizarHora();
            setInterval(actualizarHora, 1000);
        </script>

        <!-- ========================= -->
        <!-- CARDS -->
        <!-- ========================= -->

        <div class="row g-4 mb-4">

            <!-- PROXIMAS CITAS -->
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <div class="text-secondary small">
                                    Próximas citas
                                </div>

                                <h2 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT COUNT(*) AS cantidad
                                    FROM citas
                                    WHERE id_paciente = ? 
                                    AND (
                                    fecha > CURDATE()
                                    OR (
                                        fecha = CURDATE()
                                        AND hora > CURTIME()
                                    )
                                )");
                                    $cont_citas->bind_param("i", $_SESSION['id_usuario']);
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
                                style="width:55px; height:55px;">

                                <i class="bi bi-calendar-check text-primary fs-4"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PAGOS -->
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <div class="text-secondary small">
                                    Pagos pendientes
                                </div>

                                <h2 class="fw-bold mb-0">
                                    S/<?php
                                        $cont_pago = $conexion->prepare("SELECT SUM(monto) AS cantidad
                                    FROM citas
                                    WHERE id_paciente = ? 
                                    AND (
                                    fecha > CURDATE()
                                    OR (
                                        fecha = CURDATE()
                                        AND hora > CURTIME()
                                    ))
                                    AND estado_pago = 'Pendiente'");
                                        $cont_pago->bind_param("i", $_SESSION['id_usuario']);
                                        $cont_pago->execute();
                                        $result = $cont_pago->get_result();
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
                                style="width:55px; height:55px;">

                                <i class="bi bi-credit-card text-danger fs-4"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ESPECIALIDAD -->
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <div class="text-secondary small">
                                    Próxima especialidad
                                </div>

                                <h5 class="fw-bold mb-0 my-2">
                                    <?php
                                    $agenda = $conexion->prepare("SELECT e.nombre AS especialidad
                                    FROM citas c 
                                    INNER JOIN medicos m  ON c.id_medico = m.id 
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    WHERE (
                                            fecha = CURDATE() AND hora > CURTIME()
                                        ) OR fecha > CURDATE()
                                    AND id_paciente = ?
                                    ORDER BY fecha ASC, hora ASC");
                                    $agenda->bind_param("i", $_SESSION['id_usuario']);
                                    $agenda->execute();

                                    $resultado = $agenda->get_result();
                                    $fila = $resultado->fetch_assoc();
                                    echo htmlspecialchars($fila['especialidad'] ?? 'No hay cita proxima') ?>
                                </h5>

                            </div>

                            <div class="bg-info bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:55px; height:55px;">

                                <i class="bi bi-heart-pulse text-info fs-4"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- HISTORIAL -->
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <div class="text-secondary small">
                                    Consultas realizadas
                                </div>

                                <h2 class="fw-bold mb-0">
                                    <?php
                                    $cont_citas = $conexion->prepare("SELECT COUNT(*) AS cantidad
                                    FROM citas
                                    WHERE (
                                    fecha < CURDATE()
                                    OR (
                                        fecha = CURDATE()
                                        AND hora < CURTIME()
                                    ))
                                    AND id_paciente = ?
                                    ");
                                    $cont_citas->bind_param("i", $_SESSION['id_usuario']);
                                    $cont_citas->execute();
                                    $result = $cont_citas->get_result();
                                    $fila = $result->fetch_assoc();
                                    ?>
                                    <?php echo $fila['cantidad'] ?? 'Sin citas'; ?>
                                </h2>

                            </div>

                            <div class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                style="width:55px; height:55px;">

                                <i class="bi bi-clipboard2-pulse text-success fs-4"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================= -->
        <!-- CONTENIDO -->
        <!-- ========================= -->

        <div class="row g-4">

            <!-- PROXIMA CITA -->
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">
                        <?php
                        $cont_citas = $conexion->prepare("SELECT c.estado AS estado
                                    FROM citas c 
                                    WHERE (
                                            fecha = CURDATE() AND hora > CURTIME()
                                        ) OR fecha > CURDATE()
                                    AND id_paciente = ?
                                    ORDER BY fecha ASC, hora ASC");
                        $cont_citas->bind_param("i", $_SESSION['id_usuario']);
                        $cont_citas->execute();
                        $result = $cont_citas->get_result();
                        $fila = $result->fetch_assoc();
                        ?>
                        <!-- TITULO -->
                        <?php if (!$fila) { ?>

                            <!-- NO HAY CITA -->
                            <div class="text-center py-5">

                                <i class="bi bi-calendar-x display-4 text-secondary"></i>

                                <h5 class="fw-bold mt-3">
                                    No tienes próximas citas
                                </h5>

                                <p class="text-secondary">
                                    Agenda una cita para recibir atención médica
                                </p>

                                <a href="citas.php" class="btn btn-primary rounded-4 px-4 mt-2">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Agendar cita
                                </a>

                            </div>

                        <?php } else {
                            $estado = strtolower($fila['estado']);
                                    $color = "primary";

                                    if ($estado == "confirmado") $color = "success";
                                    if ($estado == "pendiente") $color = "warning";
                                    if ($estado == "cancelada") $color = "danger"; ?>
                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <h4 class="fw-bold mb-0">

                                    Próxima Cita

                                </h4>

                                <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> rounded-pill px-3 py-2">
                                    <?php echo htmlspecialchars($fila['estado'] ?? 'No hay cita'); ?>
                                </span>

                            </div>
                            <!-- INFO -->
                            <div class="row g-4">
                                <?php
                                $agenda = $conexion->prepare("SELECT c.fecha, c.hora, c.id,c.estado_pago, m.nombre AS nombre, e.nombre AS especialidad
                                    FROM citas c 
                                    INNER JOIN medicos m  ON c.id_medico = m.id 
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    WHERE (
                                            fecha = CURDATE() AND hora > CURTIME()
                                        ) OR fecha > CURDATE()
                                    AND id_paciente = ?
                                    ORDER BY fecha ASC, hora ASC");
                                $agenda->bind_param("i", $_SESSION['id_usuario']);
                                $agenda->execute();

                                $resultado = $agenda->get_result();
                                $fila = $resultado->fetch_assoc();
                                ?>
                                <input type="hidden" id="fechaCita" value="<?php echo htmlspecialchars($fila['fecha'] ?? ''); ?>">
                                <input type="hidden" id="horaCita" value="<?php echo htmlspecialchars($fila['hora'] ?? ''); ?>">
                                <!-- MEDICO -->
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-4 h-100">

                                        <div class="d-flex align-items-center gap-3 mb-3">

                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                                style="width:60px; height:60px;">

                                                <i class="bi bi-person-badge text-primary fs-3"></i>

                                            </div>

                                            <div>

                                                <h5 class="fw-bold mb-1">

                                                    Dr. <?php echo htmlspecialchars($fila['nombre'] ?? ''); ?>

                                                </h5>

                                                <span class="text-secondary">

                                                    <?php echo htmlspecialchars($fila['especialidad'] ?? ''); ?>

                                                </span>

                                            </div>

                                        </div>

                                        <div class="small text-secondary">

                                            Médico especialista asignado

                                        </div>

                                    </div>

                                </div>

                                <!-- DETALLES -->
                                <div class="col-md-6">

                                    <div class="border rounded-4 p-4 h-100 d-flex flex-column gap-3">

                                        <div class="d-flex justify-content-between">

                                            <span class="fw-semibold">
                                                Fecha
                                            </span>

                                            <span class="text-secondary">
                                                <?php echo htmlspecialchars($fila['fecha'] ?? ''); ?>
                                            </span>

                                        </div>

                                        <div class="d-flex justify-content-between">

                                            <span class="fw-semibold">
                                                Hora
                                            </span>

                                            <span class="text-secondary">
                                                <?php echo htmlspecialchars($fila['hora'] ?? ''); ?>
                                            </span>

                                        </div>

                                        <div class="d-flex justify-content-between">

                                            <span class="fw-semibold">
                                                Tiempo restante
                                            </span>

                                            <span class="text-primary fw-semibold" id="tiempoRestante">
                                            </span>
                                            <input type="hidden"
                                                id="horaCita"
                                                value="<?php echo $fila['hora']; ?>">

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!-- BOTONES -->
                            <div class="d-flex gap-3 mt-4 flex-wrap">

                                <!--<button class="btn btn-warning rounded-4 px-4">

                                    <i class="bi bi-tools me-2"></i>

                                    Trabajando ...

                                </button>-->
                                <?php
                                if ($fila['estado_pago'] == 'Pagado') {
                                ?>
                                    <a href="comprobante.php?id=<?php echo $fila['id']; ?>"
                                        target="_blank"
                                        class="btn btn-light border btn-sm rounded-3 px-3">

                                        <i class="bi bi-file-earmark-check me-1"></i>

                                        Ver comprobante

                                    </a>
                                <?php } else { ?>
                                    <a href="pagos.php"
                                        class="btn btn-success btn-sm rounded-3 p-3">

                                        <i class="bi bi-credit-card me-1"></i>

                                        Ir a pagar

                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- PANEL DERECHO -->
            <div class="col-lg-4">

                <div class="d-flex flex-column gap-4">

                    <!-- CONSEJOS -->
                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h5 class="fw-bold mb-4">

                                Recomendaciones

                            </h5>

                            <div class="d-flex flex-column gap-3">

                                <div class="alert alert-primary rounded-4 mb-0">

                                    Llegar 10 minutos antes de la cita

                                </div>

                                <div class="alert alert-warning rounded-4 mb-0">

                                    Llevar DNI y comprobante de pago

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- PAGOS -->
                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h5 class="fw-bold mb-4">

                                Estado de pagos

                            </h5>
                            <?php
                            $agenda = $conexion->prepare("SELECT e.nombre AS especialidad, c.fecha, c.estado_pago
                                    FROM citas c 
                                    INNER JOIN medicos m  ON c.id_medico = m.id 
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    WHERE (
                                        c.fecha > CURDATE()
                                        OR (
                                            c.fecha = CURDATE()
                                            AND c.hora >= CURTIME()
                                        )
                                    )
                                    AND c.id_paciente = ?
                                    AND c.estado_pago = 'Pendiente'
                                    ORDER BY c.fecha ASC, c.hora ASC
                                     LIMIT 1");
                            $agenda->bind_param("i", $_SESSION['id_usuario']);
                            $agenda->execute();

                            $resultado = $agenda->get_result();
                            $fila = $resultado->fetch_assoc();
                            ?>
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center">

                                <?php if ($fila) { ?>

                                    <div>

                                        <div class="fw-semibold">

                                            Consulta <?php echo htmlspecialchars($fila['especialidad']); ?>

                                        </div>

                                        <small class="text-secondary">

                                            <?php echo htmlspecialchars($fila['fecha']); ?>

                                        </small>

                                    </div>

                                    <span class="badge bg-danger rounded-pill px-3 py-2">

                                        <?php echo htmlspecialchars($fila['estado_pago']); ?>

                                    </span>
                                <?php } else { ?>

                                    <div class="text-secondary">

                                        No hay pagos pendientes

                                    </div>

                                <?php } ?>


                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <script src="../js/time.js"></script>
<?php endif; ?>