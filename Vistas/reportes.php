<?php
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'admin'): ?>

    <?php
    $total_citas = $conexion->query(
        "SELECT COUNT(*) as t FROM citas"
    )->fetch_assoc()['t'];

    $total_pagadas = $conexion->query(
        "SELECT COUNT(*) as t FROM citas 
         WHERE estado_pago='Pagado'"
    )->fetch_assoc()['t'];

    $total_pendientes = $conexion->query(
        "SELECT COUNT(*) as t FROM citas 
         WHERE estado_pago='Pendiente'"
    )->fetch_assoc()['t'];
    ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Reportes Estadísticos
            </h3>

            <small class="text-secondary">
                Análisis general de atenciones y pagos clínicos
            </small>

        </div>

        <button class="btn btn-primary rounded-4 px-4 shadow-sm">

            <i class="bi bi-download me-2"></i>

            Exportar

        </button>

    </div>

    <!-- Cards estadísticas -->
    <div class="row g-4 mb-4">

        <!-- Total citas -->
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Total de Citas
                            </small>

                            <h1 class="fw-bold text-primary mb-0 mt-2">

                                <?php echo $total_citas; ?>

                            </h1>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                             style="width:65px; height:65px;">

                            <i class="bi bi-calendar-check text-primary fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Pagadas -->
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Atendidas / Pagadas
                            </small>

                            <h1 class="fw-bold text-success mb-0 mt-2">

                                <?php echo $total_pagadas; ?>

                            </h1>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                             style="width:65px; height:65px;">

                            <i class="bi bi-cash-coin text-success fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Pendientes -->
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-secondary">
                                Pendientes de Pago
                            </small>

                            <h1 class="fw-bold text-warning mb-0 mt-2">

                                <?php echo $total_pendientes; ?>

                            </h1>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                             style="width:65px; height:65px;">

                            <i class="bi bi-exclamation-circle text-warning fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Gráfico -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Consolidado de Citas por Día
                    </h4>

                    <small class="text-secondary">
                        Distribución operativa semanal
                    </small>

                </div>

            </div>

            <!-- Gráfico visual -->
            <div class="bg-light rounded-4 p-4">

                <div class="d-flex align-items-end justify-content-between"
                     style="height:250px;">

                    <!-- Lunes -->
                    <div class="text-center">

                        <div class="bg-primary rounded-top"
                             style="width:55px; height:40%;"></div>

                        <small class="mt-2 d-block fw-medium">
                            Lun
                        </small>

                    </div>

                    <!-- Martes -->
                    <div class="text-center">

                        <div class="bg-primary rounded-top"
                             style="width:55px; height:75%;"></div>

                        <small class="mt-2 d-block fw-medium">
                            Mar
                        </small>

                    </div>

                    <!-- Miércoles -->
                    <div class="text-center">

                        <div class="bg-primary rounded-top"
                             style="width:55px; height:95%;"></div>

                        <small class="mt-2 d-block fw-medium">
                            Mié
                        </small>

                    </div>

                    <!-- Jueves -->
                    <div class="text-center">

                        <div class="bg-primary rounded-top"
                             style="width:55px; height:55%;"></div>

                        <small class="mt-2 d-block fw-medium">
                            Jue
                        </small>

                    </div>

                    <!-- Viernes -->
                    <div class="text-center">

                        <div class="bg-primary rounded-top"
                             style="width:55px; height:85%;"></div>

                        <small class="mt-2 d-block fw-medium">
                            Vie
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

<?php endif; ?>