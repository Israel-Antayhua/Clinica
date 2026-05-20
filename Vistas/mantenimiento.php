<?php
session_start();
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'medico'): ?>

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

        <button class="btn btn-primary rounded-4 px-4 shadow-sm" onclick="abrirFormulario()">

            <i class="bi bi-plus-circle me-2"></i>

            Nuevo Registro

        </button>

    </div>

    <!-- MÉDICOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" id="tablaMedicos">

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
                        $meds = $conexion->query("
                                    SELECT m.*, e.nombre AS especialidad
                                    FROM medicos m
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    ");

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

                                        <?php

                                        echo $m['especialidad']; ?>

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
    <!-- Formulario -->
    <div class="justify-content-center align-items-center bg-white p-3" id="formMedicos">

        <div class="card border-0 shadow-lg rounded-4 w-100">

            <!-- BODY -->
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h3 class="fw-bold mt-2 mb-0">
                            Registro de Médico
                        </h3>

                        <small class="opacity-75">
                            Agrega un nuevo médico al sistema
                        </small>

                    </div>

                    <button type="button"
                        class="btn-close"
                        onclick="cerrarFormulario()">
                    </button>

                </div>

                <form action="../Controler/Add_Medico.php" method="POST">

                    <!-- USUARIO -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="usuario" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <!-- TELEFONO -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" name="telefono" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <!-- ESPECIALIDAD -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Especialidad</label>

                        <select name="id_especialidad" class="form-select form-select-sm" required>

                            <option value="">Seleccione especialidad</option>

                            <?php
                            include("conexion.php");

                            $sql = "SELECT * FROM especialidades";
                            $res = $conexion->query($sql);

                            while ($e = $res->fetch_assoc()) {
                                echo "<option value='{$e['id']}'>{$e['nombre']}</option>";
                            }
                            ?>

                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="d-grid gap-2 mt-4">

                        <button type="submit" class="btn btn-primary btn-sm rounded-3 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>
                            Guardar Médico
                        </button>

                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-3"
                            onclick="cerrarFormulario()">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver
                        </button>

                    </div>

                </form>

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
    <script>
        document.getElementById("formMedicos").style.display = "none";

        function abrirFormulario() {
            document.getElementById("tablaMedicos").style.display = "none";
            document.getElementById("formMedicos").style.display = "block";
        }

        function cerrarFormulario() {
            document.getElementById("formMedicos").style.display = "none";
            document.getElementById("tablaMedicos").style.display = "block";
        }
    </script>
<?php endif; ?>