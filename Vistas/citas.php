<?php 
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'paciente'): ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Mis Citas
            </h3>

            <small class="text-secondary">
                Gestiona tus reservas médicas
            </small>

        </div>

        <button class="btn btn-primary rounded-4 shadow-sm px-4"
                onclick="document.getElementById('form-registro').style.display='block'">

            <i class="bi bi-plus-circle me-2"></i>

            Nueva Cita

        </button>

    </div>

    <!-- Formulario -->
    <div id="form-registro"
         class="card border-0 shadow-sm rounded-4 mb-4"
         style="display:none; max-width: 550px;">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Registrar Reserva
                    </h4>

                    <small class="text-secondary">
                        Complete los datos de la cita
                    </small>

                </div>

                <button type="button"
                        class="btn-close"
                        onclick="document.getElementById('form-registro').style.display='none'">
                </button>

            </div>

            <form action="guardar.php" method="POST">

                <input type="hidden"
                       name="nombre"
                       value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>">

                <!-- Fecha -->
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Fecha
                    </label>

                    <input type="date"
                           name="fecha"
                           class="form-control rounded-3"
                           required>

                </div>

                <!-- Hora -->
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Hora
                    </label>

                    <input type="time"
                           name="hora"
                           class="form-control rounded-3"
                           required>

                </div>

                <!-- Especialidad -->
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Especialidad
                    </label>

                    <select name="especialidad"
                            class="form-select rounded-3">

                        <?php
                        $esps = $conexion->query("SELECT * FROM especialidades WHERE estado='Activo'");

                        while ($e = $esps->fetch_assoc()):
                        ?>

                            <option value="<?php echo $e['nombre']; ?>">

                                <?php echo $e['nombre']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- Botones -->
                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary rounded-3 px-4">

                        <i class="bi bi-check-circle me-2"></i>

                        Confirmar

                    </button>

                    <button type="button"
                            class="btn btn-light border rounded-3 px-4"
                            onclick="document.getElementById('form-registro').style.display='none'">

                        Cancelar

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Próximas Citas
                    </h4>

                    <small class="text-secondary">
                        Historial y seguimiento de reservas
                    </small>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th class="py-3">Fecha</th>

                            <th class="py-3">Hora</th>

                            <th class="py-3">Especialidad</th>

                            <th class="py-3">Médico</th>

                            <th class="py-3">Estado</th>

                            <th class="py-3 text-center">Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $citas = $conexion->query(
                            "SELECT * FROM citas 
                             WHERE nombre_paciente = '" . $_SESSION['usuario'] . "'"
                        );

                        while ($f = $citas->fetch_assoc()):

                            $badge = ($f['estado'] == 'Confirmada')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-warning-subtle text-warning border border-warning-subtle';
                        ?>

                            <tr>

                                <!-- Fecha -->
                                <td>

                                    <span class="fw-medium">

                                        <?php echo date('d/m/Y', strtotime($f['fecha'])); ?>

                                    </span>

                                </td>

                                <!-- Hora -->
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        <?php echo $f['hora']; ?>

                                    </span>

                                </td>

                                <!-- Especialidad -->
                                <td>

                                    <?php echo $f['especialidad']; ?>

                                </td>

                                <!-- Médico -->
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                             style="width:40px; height:40px;">

                                            <i class="bi bi-person-fill text-primary"></i>

                                        </div>

                                        <span>

                                            <?php echo $f['medico']; ?>

                                        </span>

                                    </div>

                                </td>

                                <!-- Estado -->
                                <td>

                                    <span class="badge rounded-pill px-3 py-2 <?php echo $badge; ?>">

                                        <?php echo $f['estado']; ?>

                                    </span>

                                </td>

                                <!-- Acciones -->
                                <td class="text-center">

                                    <a href="eliminar.php?id=<?php echo $f['id']; ?>"
                                       class="btn btn-sm btn-outline-danger rounded-3">

                                        <i class="bi bi-x-circle me-1"></i>

                                        Cancelar

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>