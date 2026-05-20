<?php
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'paciente'): ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Mis Pagos
            </h3>

            <small class="text-secondary">
                Historial y gestión de pagos médicos
            </small>

        </div>

    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- Tabla -->
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th class="py-3">Cita / Especialidad</th>

                            <th class="py-3">Fecha</th>

                            <th class="py-3">Monto</th>

                            <th class="py-3">Estado</th>

                            <th class="py-3 text-center">Acción</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $pagos = $conexion->query(
                            "SELECT c.*,e.nombre AS especialidad FROM citas c
                            INNER JOIN medicos m  ON c.id_medico = m.id JOIN especialidades e ON m.id_especialidad = e.id
                             WHERE id_paciente = '" . $_SESSION['id_usuario'] . "'"
                        );

                        while ($f = $pagos->fetch_assoc()):

                            $badge_pago = ($f['estado_pago'] == 'Pagado')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-warning-subtle text-warning border border-warning-subtle';
                        ?>

                            <tr>

                                <!-- Especialidad -->
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                             style="width:45px; height:45px;">

                                            <i class="bi bi-receipt text-primary"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                Reserva - <?php echo $f['especialidad']; ?>

                                            </div>

                                            <small class="text-secondary">

                                                Atención médica

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <!-- Fecha -->
                                <td>

                                    <span class="fw-medium">

                                        <?php echo date('d/m/Y', strtotime($f['fecha'])); ?>

                                    </span>

                                </td>

                                <!-- Monto -->
                                <td>

                                    <span class="fw-bold text-success">

                                        S/ <?php echo number_format($f['monto'], 2); ?>

                                    </span>

                                </td>

                                <!-- Estado -->
                                <td>

                                    <span class="badge rounded-pill px-3 py-2 <?php echo $badge_pago; ?>">

                                        <?php echo $f['estado_pago']; ?>

                                    </span>

                                </td>

                                <!-- Acción -->
                                <td class="text-center">

                                    <?php if ($f['estado_pago'] == 'Pendiente'): ?>

                                        <a href="pagar.php?id=<?php echo $f['id']; ?>"
                                           class="btn btn-primary btn-sm rounded-3 px-3">

                                            <i class="bi bi-credit-card me-1"></i>

                                            Registrar Pago

                                        </a>

                                    <?php else: ?>

                                        <button class="btn btn-light border btn-sm rounded-3 px-3">

                                            <i class="bi bi-file-earmark-check me-1"></i>

                                            Ver Comprobante

                                        </button>

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