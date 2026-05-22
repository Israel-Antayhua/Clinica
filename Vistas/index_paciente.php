<?php
session_start();
include '../includes/cabecera.php';
date_default_timezone_set('America/Lima');
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
include '../ConexionDB/conexion.php';
if (isset($_SESSION['swal']));
if ($_SESSION['rol'] == 'paciente'): ?>
    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    Hola, <?php echo $_SESSION['usuario']; ?>👋
                </h2>

                <p class="text-secondary mb-0">
                    Bienvenido nuevamente a Maison de Santé
                </p>

            </div>

            <!-- FECHA -->
            <div class="bg-white shadow-sm rounded-4 px-5 py-4"
                style="min-width:240px;">

                <div class="small text-secondary mb-1">
                    Fecha actual
                </div>

                <div class="fw-bold fs-4">
                    <?php echo date('d/m/Y'); ?>
                </div>

            </div>

        </div>

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
                                    WHERE fecha = CURDATE()
                                    AND hora > CURTIME() 
                                    AND id_paciente = ?");
                                    $agenda->bind_param("i", $_SESSION['id_usuario']);
                                    $agenda->execute();

                                    $resultado = $agenda->get_result();
                                    $fila = $resultado->fetch_assoc();
                                    echo htmlspecialchars($fila['especialidad']);
                                    ?>
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

                        <!-- TITULO -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="fw-bold mb-0">

                                Próxima Cita

                            </h4>

                            <span class="badge bg-success rounded-pill px-3 py-2">

                                <?php
                                $cont_citas = $conexion->prepare("SELECT c.estado AS estado
                                    FROM citas c 
                                    WHERE fecha = CURDATE()
                                    AND hora > CURTIME() 
                                    AND id_paciente = ?");
                                $cont_citas->bind_param("i", $_SESSION['id_usuario']);
                                $cont_citas->execute();
                                $result = $cont_citas->get_result();
                                $fila = $result->fetch_assoc();
                                ?>
                                <?php echo htmlspecialchars($fila['estado']); ?>
                            </span>

                        </div>

                        <!-- INFO -->
                        <div class="row g-4">

                            <!-- MEDICO -->
                            <div class="col-md-6">
                                <?php
                                $agenda = $conexion->prepare("SELECT c.fecha, c.hora, c.id, m.nombre AS nombre, e.nombre AS especialidad
                                    FROM citas c 
                                    INNER JOIN medicos m  ON c.id_medico = m.id 
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    WHERE fecha = CURDATE()
                                    AND hora > CURTIME() 
                                    AND id_paciente = ?");
                                $agenda->bind_param("i", $_SESSION['id_usuario']);
                                $agenda->execute();

                                $resultado = $agenda->get_result();
                                $fila = $resultado->fetch_assoc();
                                ?>
                                <div class="border rounded-4 p-4 h-100">

                                    <div class="d-flex align-items-center gap-3 mb-3">

                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                            style="width:60px; height:60px;">

                                            <i class="bi bi-person-badge text-primary fs-3"></i>

                                        </div>

                                        <div>

                                            <h5 class="fw-bold mb-1">

                                                Dr. <?php echo htmlspecialchars($fila['nombre']); ?>

                                            </h5>

                                            <span class="text-secondary">

                                                <?php echo htmlspecialchars($fila['especialidad']); ?>

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
                                            <?php echo htmlspecialchars($fila['fecha']); ?>
                                        </span>

                                    </div>

                                    <div class="d-flex justify-content-between">

                                        <span class="fw-semibold">
                                            Hora
                                        </span>

                                        <span class="text-secondary">
                                            <?php echo htmlspecialchars($fila['hora']); ?>
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

                            <button class="btn btn-warning rounded-4 px-4">

                                <i class="bi bi-tools me-2"></i>

                                Trabajando ...

                            </button>

                            <a href="comprobante.php?id=<?php echo $fila['id']; ?>"
                                target="_blank"
                                class="btn btn-light border btn-sm rounded-3 px-3">

                                <i class="bi bi-file-earmark-check me-1"></i>

                                Ver comprobante

                            </a>

                        </div>

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

        <!-- ========================= -->
        <!-- TABLA CITAS -->
        <!--

        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="fw-bold mb-0">

                        Mis Citas

                    </h4>

                    <button class="btn btn-primary rounded-4 px-4">

                        <i class="bi bi-plus-circle me-2"></i>

                        Nueva cita

                    </button>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Fecha</th>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    22/05/2026
                                </td>

                                <td>
                                    Dr. Carlos Ruiz
                                </td>

                                <td>
                                    Cardiología
                                </td>

                                <td>

                                    <span class="badge bg-success rounded-pill px-3 py-2">

                                        Confirmada

                                    </span>

                                </td>

                                <td class="text-center">

                                    <button class="btn btn-light border rounded-3 btn-sm">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        -->

    </div>
    <script src="../js/time.js"></script>
<?php endif; ?>