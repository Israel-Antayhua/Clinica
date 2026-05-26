<?php

session_start();
if (isset($_SESSION['swal']));
date_default_timezone_set('America/Lima');
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';
if ($_SESSION['rol'] == 'medico'): ?>

    <!-- Encabezado -->
    <!-- Encabezado -->
    <div class="d-flex flex-column gap-3 mb-4">

        <!-- TÍTULO -->
        <div>
            <h3 class="fw-bold mb-1">Mi Agenda Médica</h3>
            <small class="text-secondary">
                Gestión de citas y seguimiento de pacientes
            </small>
        </div>

        <!-- FILTRO DE FECHA -->
        <div class="d-flex justify-content-between align-items-center">

            <!-- flechas + fecha -->
            <div class="d-flex align-items-center gap-3">

                <button class="btn btn-light border rounded-3" id="btnPrevDia">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <input type="date" class="form-control w-auto" id="filtroFecha" value="<?php echo $fecha; ?>">

                <button class="btn btn-light border rounded-3" id="btnNextDia">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

            <!-- NUEVA CITA -->
            <button class="btn btn-primary rounded-4 px-4 shadow-sm"
                id="btnNuevaCita">

                <i class="bi bi-plus-circle me-2"></i>
                Nueva Cita

            </button>

        </div>

    </div>
    <!-- Layout -->
    <!-- LISTADO + FORM -->
    <div class="row g-4">
        <!-- LISTADO -->
        <div class="col-12" id="contenedorListado">

            <div class="d-flex flex-column gap-3">

                <?php
                $agenda = $conexion->prepare("SELECT c.*,p.nombres AS nombre_paciente,e.nombre AS especialidad
                FROM citas c 
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                INNER JOIN medicos m  ON c.id_medico = m.id 
                INNER JOIN especialidades e ON m.id_especialidad = e.id
                WHERE fecha = ? AND id_medico = ?
                Order by hora Asc");
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

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden card-cita" data-fecha="<?php echo $f['fecha']; ?>">

                            <div class="row g-0 align-items-center">

                                <!-- INFO -->
                                <div class="col-lg-9 col-info p-4 border-end">

                                    <div class="fw-bold fs-4 text-primary">
                                        <?php echo $f['hora']; ?>
                                    </div>

                                    <div class="fw-bold fs-5">
                                        <?php echo htmlspecialchars($f['nombre_paciente']); ?>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div class="text-secondary">
                                            <?php echo $f['especialidad']; ?>
                                        </div>

                                        <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> rounded-pill px-3 py-2">
                                            <?php echo $f['estado']; ?>
                                        </span>

                                    </div>

                                </div>

                                <!-- ACCIONES -->
                                <div class="col-lg-3 col-acciones d-flex justify-content-center align-items-center p-3">

                                    <div class="d-flex gap-2">

                                        <button class="btn btn-light border rounded-3">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-light border rounded-3">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-light border rounded-3">
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="alert alert-warning text-center">
                        No hay citas para esta fecha
                    </div>

                <?php endif; ?>

            </div>

        </div>

        <!-- FORMULARIO -->
        <div class="col-6 d-none" id="panelFormulario">

            <div class="card border-0 shadow-sm rounded-4 sticky-top"
                style="top:20px;">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Registrar atención
                            </h5>

                            <small class="text-secondary">
                                Nueva cita médica
                            </small>

                        </div>

                        <button class="btn btn-light rounded-circle"
                            id="cerrarFormulario">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>

                    <form action="../Controler/Add_Cita.php" id="formCita">

                        <div class="mb-3">
                            <input type="hidden" name="accion" value="crear">
                            <label class="form-label fw-semibold">
                                DNI del Paciente
                            </label>

                            <input type="text"
                                id="dni"
                                class="form-control rounded-4 py-2"
                                placeholder="Ingrese DNI y presione Enter">

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Datos del Paciente
                            </label>
                            <input type="hidden"
                                name="id_paciente"
                                id="id_paciente">

                            <input type="text"
                                style="background:#e9ecef; cursor:not-allowed;"
                                id="nombre_paciente"
                                class="form-control rounded-4 py-2"
                                readonly>
                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Especialidad
                            </label>

                            <input type="text" class="form-control rounded-4 py-2"
                                style="background:#e9ecef; cursor:not-allowed;"
                                value="<?php
                                        $sql = "SELECT e.nombre,e.precio_consulta
                                FROM especialidades e
                                INNER JOIN medicos m On m.id_especialidad = e.id
                                where m.id=?";
                                        $stmt = $conexion->prepare($sql);
                                        $stmt->bind_param("i", $_SESSION['id_medico']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $especialidad = $result->fetch_assoc();
                                        echo $especialidad['nombre'];
                                        ?>" readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Fecha
                            </label>

                            <input type="date"
                                name="fecha"
                                class="form-control rounded-3"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Hora
                            </label>

                            <select type="time"
                                name="hora"
                                class="form-control rounded-3"
                                required>
                                <?php
                                for ($h = 8; $h <= 18; $h++) {

                                    $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";

                                    echo "<option value='$hora'>$hora</option>";
                                }
                                ?>
                            </select>
                            <input type="hidden"
                                id="monto"
                                name="monto"
                                value="<?php echo $especialidad['precio_consulta'] ?? 0; ?>">
                        </div>

                        <button class="btn btn-success w-100 rounded-4 py-3 fw-semibold">

                            Guardar atención

                        </button>

                        <input type="hidden" name="accion" value="crear">

                    </form>

                </div>

            </div>

        </div>

    </div>
    <style>
        .card-cita {
            transition: .2s ease;
        }

        .card-cita:hover {
            transform: translateY(-3px);
        }
    </style>
    <script src="../js/agenda.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({

            icon: '<?php echo $_SESSION['swal']['icon']; ?>',

            title: '<?php echo $_SESSION['swal']['title']; ?>',

            text: '<?php echo $_SESSION['swal']['text']; ?>'

        });
    </script>
    <?php unset($_SESSION['swal']); ?>
<?php endif; ?>