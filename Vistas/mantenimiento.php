<?php
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'administrador'): ?>
    <div class="d-flex gap-2 mb-4">

        <button class="btn btn-primary rounded-4"
            id="btnVistaMedicos"
            onclick="cambiarVista('medicos')">

            <i class="bi bi-person-badge me-2"></i>
            Médicos

        </button>

        <button class="btn btn-outline-primary rounded-4"
            id="btnVistaEspecialidades"
            onclick="cambiarVista('especialidades')">

            <i class="bi bi-heart-pulse me-2"></i>
            Especialidades

        </button>

    </div>
    <div id="vistaMedicos">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Gestion de Medicos
                </h3>

                <small class="text-secondary">
                    Administración de médicos
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
                                    SELECT m.*, e.nombre AS especialidad, u.correo AS usuario
                                    FROM medicos m
                                    INNER JOIN especialidades e ON m.id_especialidad = e.id
                                    INNER JOIN usuarios u ON m.id_usuario = u.id
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

                                            <button class="btn btn-sm btn-light border rounded-3" disabled>

                                                <i class="bi bi-eye"></i>

                                            </button>

                                            <button class="btnEditar2 btn btn-sm btn-light border rounded-3"
                                                data-id="<?php echo $m['id']; ?>"
                                                data-nombre="<?php echo $m['nombre']; ?>"
                                                data-telefono="<?php echo $m['telefono']; ?>"
                                                data-usuario="<?php echo $m['usuario']; ?>"
                                                data-id_especialidad="<?php echo $m['id_especialidad']; ?>">

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
                            <label class="form-label fw-semibold small text-secondary">Correo</label>
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
        <!-- Modal Medico -->
        <div class="modal fade" id="modalMedico" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <form id="formMedico" action="../Controler/Add_Medico.php" method="POST" class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-success text-white">

                        <h5 class="modal-title">
                            Registro de Médico
                        </h5>

                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <!-- ID (para editar) -->
                        <input type="hidden" name="id" id="med_id">

                        <!-- ACCION -->
                        <input type="hidden" name="accion" value="guardar">

                        <!-- NOMBRE -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">Nombre</label>

                            <input type="text"
                                name="nombre"
                                id="med_nombre"
                                class="form-control rounded-3"
                                required>

                        </div>

                        <!-- TELEFONO -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">Teléfono</label>

                            <input type="text"
                                name="telefono"
                                id="med_telefono"
                                class="form-control rounded-3"
                                required>

                        </div>

                        <!-- USUARIO -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">Correo</label>

                            <input type="text"
                                name="usuario"
                                id="med_usuario"
                                class="form-control rounded-3"
                                required>

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">Contraseña</label>

                            <input type="password"
                                name="password"
                                id="med_password"
                                class="form-control rounded-3">

                            <small class="text-muted">
                                (Solo completar si es nuevo o cambiar contraseña)
                            </small>

                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">Especialidad</label>

                            <select name="id_especialidad"
                                id="med_especialidad"
                                class="form-select rounded-3"
                                required>

                                <option value="">Seleccione...</option>

                                <!-- dinámico desde BD -->
                                <?php
                                $esp = $conexion->query("SELECT * FROM especialidades");
                                while ($e = $esp->fetch_assoc()) {
                                    echo "<option value='{$e['id']}'
                            data-precio='{$e['precio_consulta']}'>
                            {$e['nombre']}</option>";
                                }
                                ?>

                            </select>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="btn btn-success">
                            Guardar Médico
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
    <div id="vistaEspecialidades" style="display:none;">
        <!--Header Especialidades-->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Gestion de Especialidades
                </h3>

                <small class="text-secondary">
                    Administración de Especialidades
                </small>

            </div>

            <button class="btn btn-primary rounded-4 px-4 shadow-sm" onclick="abrirFormulario2()">

                <i class="bi bi-plus-circle me-2"></i>

                Nuevo Registro

            </button>

        </div>
        <!-- ESPECIALIDADES -->
        <div class="card border-0 shadow-sm rounded-4" id="tablaEspecialidades">

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

                                <th class="py-3">Precio Consulta</th>

                                <th class="py-3">Estado</th>

                                <th class="py-3 text-center">Acciones</th>

                            </tr>

                        </thead>

                        <tbody id="bodyEspecie">
                            <?php include("../Controler/Get_Especia.php"); ?>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>
        <!-- Formulario -->
        <div class="justify-content-center align-items-center bg-white p-3" id="formEspecialidades">

            <div class="card border-0 shadow-lg rounded-4 w-100">

                <!-- BODY -->
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h3 class="fw-bold mt-2 mb-0">
                                Registro de Especialidad
                            </h3>

                            <small class="opacity-75">
                                Agrega una nueva Especialidad al sistema
                            </small>

                        </div>

                        <button type="button"
                            class="btn-close"
                            onclick="cerrarFormulario2()">
                        </button>

                    </div>

                    <form id="formEspecialidad">

                        <!-- USUARIO -->
                        <!-- NOMBRE ESPECIALIDAD -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold small text-secondary">
                                Nombre de Especialidad
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-heart-pulse"></i>
                                </span>

                                <input type="text"
                                    name="nombre"
                                    class="form-control form-control-sm"
                                    placeholder="Ej: Cardiología"
                                    required>

                            </div>

                        </div>

                        <!-- PRECIO CONSULTA -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold small text-secondary">
                                Precio de Consulta
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    S/
                                </span>

                                <input type="number"
                                    name="precio_consulta"
                                    class="form-control form-control-sm"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    required>

                            </div>

                        </div>

                        <!-- BOTONES -->
                        <div class="d-grid gap-2 mt-4">

                            <button type="submit" class="btn btn-primary btn-sm rounded-3 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>
                                Guardar Especialidad
                            </button>

                            <button type="button"
                                class="btn btn-outline-secondary btn-sm rounded-3"
                                onclick="cerrarFormulario2()">
                                <i class="bi bi-arrow-left me-2"></i>
                                Volver
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
        <!-- Modal Especia -->
        <div class="modal fade" id="modalEditar">
            <div class="modal-dialog">

                <form class="modal-content" id="formEspecialidadEditar">

                    <div class="modal-header">
                        <h5>Editar Especialidad</h5>
                    </div>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        style="position:absolute; right:15px; top:15px;">
                    </button>
                    <div class="modal-body">

                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-2">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Precio</label>
                            <input type="number" name="precio" id="edit_precio" class="form-control">
                        </div>

                        <input type="hidden" name="accion" value="editar">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/mantenimiento.js"></script>
    <script>
        Swal.fire({

            icon: <?php echo json_encode($_SESSION['swal']['icon']); ?>,
            title: <?php echo json_encode($_SESSION['swal']['title']); ?>,
            text: <?php echo json_encode($_SESSION['swal']['text']); ?>

        });
    </script>
<?php endif; ?>