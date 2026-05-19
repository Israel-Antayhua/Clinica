<?php
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'admin'): ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Catálogos y Mantenimiento
            </h3>

            <small class="text-secondary">
                Administración de médicos y especialidades
            </small>

        </div>

        <button class="btn btn-primary rounded-4 px-4 shadow-sm">

            <i class="bi bi-plus-circle me-2"></i>

            Nuevo Registro

        </button>

    </div>

    <!-- MÉDICOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Gestión de Médicos
                    </h4>

                    <small class="text-secondary">
                        Registro y administración del personal médico
                    </small>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th class="py-3">ID</th>

                            <th class="py-3">Médico</th>

                            <th class="py-3">Especialidad</th>

                            <th class="py-3">Teléfono</th>

                            <th class="py-3">Estado</th>

                            <th class="py-3 text-center">Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $meds = $conexion->query("SELECT * FROM medicos");

                        while ($m = $meds->fetch_assoc()):

                            $badge = ($m['estado'] == 'Activo')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-danger-subtle text-danger border border-danger-subtle';
                        ?>

                            <tr>

                                <!-- ID -->
                                <td>

                                    <span class="fw-semibold">

                                        #<?php echo $m['id']; ?>

                                    </span>

                                </td>

                                <!-- Médico -->
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                             style="width:45px; height:45px;">

                                            <i class="bi bi-person-badge text-primary"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                <?php echo $m['nombre']; ?>

                                            </div>

                                            <small class="text-secondary">
                                                Médico registrado
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <!-- Especialidad -->
                                <td>

                                    <span class="fw-medium">

                                        <?php echo $m['especialidad']; ?>

                                    </span>

                                </td>

                                <!-- Teléfono -->
                                <td>

                                    <?php echo $m['telefono']; ?>

                                </td>

                                <!-- Estado -->
                                <td>

                                    <span class="badge rounded-pill px-3 py-2 <?php echo $badge; ?>">

                                        <?php echo $m['estado']; ?>

                                    </span>

                                </td>

                                <!-- Acciones -->
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-2">

                                        <button class="btn btn-sm btn-light border rounded-3">

                                            <i class="bi bi-eye"></i>

                                        </button>

                                        <button class="btn btn-sm btn-light border rounded-3">

                                            <i class="bi bi-pencil"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- ESPECIALIDADES -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Gestión de Especialidades
                    </h4>

                    <small class="text-secondary">
                        Administración de áreas médicas disponibles
                    </small>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th class="py-3">ID</th>

                            <th class="py-3">Especialidad</th>

                            <th class="py-3">Estado</th>

                            <th class="py-3 text-center">Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $espe = $conexion->query("SELECT * FROM especialidades");

                        while ($e = $espe->fetch_assoc()):

                            $badge = ($e['estado'] == 'Activo')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-danger-subtle text-danger border border-danger-subtle';
                        ?>

                            <tr>

                                <!-- ID -->
                                <td>

                                    <span class="fw-semibold">

                                        #<?php echo $e['id']; ?>

                                    </span>

                                </td>

                                <!-- Especialidad -->
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="bg-info bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                                             style="width:45px; height:45px;">

                                            <i class="bi bi-heart-pulse text-info"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                <?php echo $e['nombre']; ?>

                                            </div>

                                            <small class="text-secondary">
                                                Especialidad médica
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <!-- Estado -->
                                <td>

                                    <span class="badge rounded-pill px-3 py-2 <?php echo $badge; ?>">

                                        <?php echo $e['estado']; ?>

                                    </span>

                                </td>

                                <!-- Acciones -->
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-2">

                                        <button class="btn btn-sm btn-light border rounded-3">

                                            <i class="bi bi-pencil"></i>

                                        </button>

                                        <button class="btn btn-sm btn-light border rounded-3">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>