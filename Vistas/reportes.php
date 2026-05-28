<?php
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';

if ($_SESSION['rol'] == 'medico'):

    // KPIs
    $total_citas = $conexion->query("
        SELECT COUNT(*) as t 
        FROM citas
    ")->fetch_assoc()['t'];

    $citas_hoy = $conexion->query("
        SELECT COUNT(*) as t 
        FROM citas
        WHERE fecha = CURDATE()
    ")->fetch_assoc()['t'];

    $total_pacientes = $conexion->query("
        SELECT COUNT(*) as t 
        FROM pacientes
    ")->fetch_assoc()['t'];

    $ingresos_mes = $conexion->query("
        SELECT SUM(monto) as t 
        FROM citas
        WHERE estado_pago='Pagado'
        AND MONTH(fecha)=MONTH(CURDATE())
    ")->fetch_assoc()['t'];

    $ingresos_mes = $ingresos_mes ?: 0;

?>

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Dashboard Administrativo
            </h3>

            <small class="text-secondary">
                Resumen general del sistema clínico
            </small>

        </div>

        <button class="btn btn-primary rounded-4 px-4 shadow-sm">

            <i class="bi bi-download me-2"></i>

            Exportar Reporte

        </button>

    </div>

    <!-- KPIs -->
    <div class="row g-4 mb-4">

        <!-- Total citas -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Total Citas
                            </small>

                            <h2 class="fw-bold text-primary mt-2 mb-0">
                                <?php echo $total_citas; ?>
                            </h2>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                            style="width:65px; height:65px;">

                            <i class="bi bi-calendar-check text-primary fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Citas hoy -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Citas Hoy
                            </small>

                            <h2 class="fw-bold text-success mt-2 mb-0">
                                <?php echo $citas_hoy; ?>
                            </h2>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                            style="width:65px; height:65px;">

                            <i class="bi bi-activity text-success fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Pacientes -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Pacientes
                            </small>

                            <h2 class="fw-bold text-info mt-2 mb-0">
                                <?php echo $total_pacientes; ?>
                            </h2>

                        </div>

                        <div class="bg-info bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                            style="width:65px; height:65px;">

                            <i class="bi bi-people text-info fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Ingresos -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Ingresos del Mes
                            </small>

                            <h2 class="fw-bold text-warning mt-2 mb-0">
                                S/ <?php echo number_format($ingresos_mes, 2); ?>
                            </h2>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                            style="width:65px; height:65px;">

                            <i class="bi bi-cash-stack text-warning fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- GRAFICOS -->
    <div class="row g-4 mb-4">

        <!-- Estadísticas -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Rendimiento Semanal
                            </h5>

                            <small class="text-secondary">
                                Flujo operativo de citas médicas
                            </small>

                        </div>

                    </div>

                    <div class="bg-light rounded-4 p-4">

                        <div class="d-flex align-items-end justify-content-between"
                            style="height:260px;">

                            <?php
                            $dias = [
                                ['Lun', 45],
                                ['Mar', 75],
                                ['Mié', 95],
                                ['Jue', 60],
                                ['Vie', 85],
                                ['Sáb', 40]
                            ];

                            foreach ($dias as $d):
                            ?>

                                <div class="text-center">

                                    <div class="bg-primary rounded-top shadow-sm"
                                        style="width:55px; height:<?php echo $d[1]; ?>%;"></div>

                                    <small class="fw-semibold mt-2 d-block">
                                        <?php echo $d[0]; ?>
                                    </small>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Estado sistema -->
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Estado General
                    </h5>

                    <div class="d-flex flex-column gap-4">

                        <div>

                            <div class="d-flex justify-content-between mb-2">

                                <span class="fw-medium">
                                    Pagos Completados
                                </span>

                                <span class="text-success fw-bold">
                                    82%
                                </span>

                            </div>

                            <div class="progress rounded-pill"
                                style="height:10px;">

                                <div class="progress-bar bg-success"
                                    style="width:82%;"></div>

                            </div>

                        </div>

                        <div>

                            <div class="d-flex justify-content-between mb-2">

                                <span class="fw-medium">
                                    Citas Confirmadas
                                </span>

                                <span class="text-primary fw-bold">
                                    91%
                                </span>

                            </div>

                            <div class="progress rounded-pill"
                                style="height:10px;">

                                <div class="progress-bar bg-primary"
                                    style="width:91%;"></div>

                            </div>

                        </div>

                        <div>

                            <div class="d-flex justify-content-between mb-2">

                                <span class="fw-medium">
                                    Cancelaciones
                                </span>

                                <span class="text-danger fw-bold">
                                    12%
                                </span>

                            </div>

                            <div class="progress rounded-pill"
                                style="height:10px;">

                                <div class="progress-bar bg-danger"
                                    style="width:12%;"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ULTIMAS CITAS -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h5 class="fw-bold mb-1">
                        Últimas Citas Registradas
                    </h5>

                    <small class="text-secondary">
                        Actividad reciente del sistema
                    </small>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Paciente</th>

                            <th>Fecha</th>

                            <th>Hora</th>

                            <th>Estado</th>

                            <th>Pago</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $ultimas = $conexion->query("
                            SELECT c.*, p.nombres AS paciente
                            FROM citas c
                            INNER JOIN pacientes p 
                            ON c.id_paciente = p.id_paciente
                            ORDER BY c.id DESC
                            LIMIT 5
                        ");

                        while ($u = $ultimas->fetch_assoc()):
                        ?>

                            <tr>

                                <td class="fw-semibold">
                                    <?php echo $u['paciente']; ?>
                                </td>

                                <td>
                                    <?php echo date('d/m/Y', strtotime($u['fecha'])); ?>
                                </td>

                                <td>
                                    <?php echo $u['hora']; ?>
                                </td>

                                <td>

                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">

                                        <?php echo $u['estado']; ?>

                                    </span>

                                </td>

                                <td>

                                    <?php if ($u['estado_pago'] == 'Pagado'): ?>

                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">

                                            Pagado

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">

                                            Pendiente

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>