<?php
session_start();
if (isset($_SESSION['swal']));
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
    <!-- Modal -->
    <div class="modal fade" id="modalEditarHora" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <form action="../Controler/Add_Cita.php" method="POST" class="modal-content" id="formCambiarHora">

                <div class="modal-header bg-warning">

                    <h5 class="modal-title">Cambiar Hora de Cita</h5>

                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">
                    <input type="hidden" name="accion" value="cambiar_hora">

                    <input type="hidden" id="edit_id_medico" name="id_medico">

                    <input type="hidden" id="edit_id" name="id">

                    <input type="hidden" id="edit_fecha" name="fecha">

                    <input type="hidden" id="edit_monto" name="monto">

                    <label class="form-label fw-semibold">Hora</label>

                    <select name="hora" id="edit_hora" class="form-select" required>

                        <?php
                        for ($h = 8; $h <= 18; $h++) {
                            $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
                            echo "<option value='$hora'>$hora</option>";
                        }
                        ?>

                    </select>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-warning">
                        Actualizar Hora
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Formulario -->
    <div id="form-registro" class="card border-0 shadow-sm rounded-4 mb-4"
        style="display:none;">

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

            <form action="../Controler/Add_Cita.php" method="POST" id="formCita">
                <input type="hidden" name="accion" value="crear">
                <!-- Especialidad -->
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Especialidad
                    </label>

                    <select id="especialidad" name="especialidad" class="form-select" required>
                        <option value="">Seleccione especialidad</option>

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

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Medico
                    </label>
                    <select id="id_medico" name="id_medico" class="form-select" required>
                        <option value="">Seleccione médico</option>
                    </select>
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

                <!-- Hora -->
                <div class="mb-3">

                    <div class="row g-2">

                        <!-- HORA -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Hora
                            </label>

                            <select
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

                        </div>

                        <!-- MONTO -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Monto
                            </label>

                            <!-- visible -->
                            <input
                                type="text"
                                id="montoVisible"
                                class="form-control rounded-3 bg-light"
                                value="S/ 120"
                                readonly>
                            <input
                                type="hidden"
                                id="monto"
                                name="monto"
                                value="120">
                        </div>

                    </div>

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
    <!-- FILTROS -->
    <div class="d-flex gap-2 mb-4">

        <button class="btn btn-primary rounded-4 px-4 filtro-cita active"
            data-filtro="proxima">

            <i class="bi bi-calendar-event me-2"></i>

            Próximas Citas

        </button>

        <button class="btn btn-light border rounded-4 px-4 filtro-cita"
            data-filtro="historial">

            <i class="bi bi-clock-history me-2"></i>

            Historial

        </button>

    </div>
    <!-- TABLA -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Mis Citas
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
                            "SELECT 
                            c.*,
                            m.nombre AS medico,
                            e.nombre AS especialidad
                        FROM citas c
                        INNER JOIN medicos m ON c.id_medico = m.id
                        INNER JOIN especialidades e ON m.id_especialidad = e.id
                        WHERE c.id_paciente = " . $_SESSION['id_usuario'] . "
                        ORDER BY c.fecha ASC, c.hora ASC"
                        );

                        while ($f = $citas->fetch_assoc()):

                            $badge = ($f['estado'] == 'Confirmada')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-warning-subtle text-warning border border-warning-subtle';

                            $tipo = (strtotime($f['fecha']) >= strtotime(date('Y-m-d')))
                                ? 'proxima'
                                : 'historial';
                        ?>

                            <tr class="fila-cita <?php echo $tipo; ?>">

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

                                    <?php if ($tipo == 'proxima'): ?>

                                        <button class="btn btn-sm btn-light border btnEditarCita"
                                            data-id="<?php echo $f['id']; ?>"
                                            data-monto="<?php echo $f['monto']; ?>"
                                            data-fecha="<?php echo $f['fecha']; ?>"
                                            data-hora="<?php echo $f['hora']; ?>"
                                            data-medico="<?php echo $f['id_medico']; ?>">

                                            <i class="bi bi-clock text-primary"></i>

                                        </button>

                                        <a href="eliminar.php?id=<?php echo $f['id']; ?>"
                                            class="btn btn-sm btn-outline-danger rounded-3">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Cancelar

                                        </a>

                                    <?php else: ?>

                                        <span class="text-secondary small">
                                            Finalizada
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

    <script>
        const botonesCita = document.querySelectorAll(".filtro-cita");
        const filasCita = document.querySelectorAll(".fila-cita");

        botonesCita.forEach(btn => {

            btn.addEventListener("click", () => {

                botonesCita.forEach(b => {
                    b.classList.remove("btn-primary");
                    b.classList.add("btn-light", "border");
                });

                btn.classList.remove("btn-light", "border");
                btn.classList.add("btn-primary");

                let filtro = btn.dataset.filtro;

                filasCita.forEach(fila => {

                    if (fila.classList.contains(filtro)) {
                        fila.style.display = "";
                    } else {
                        fila.style.display = "none";
                    }

                });

            });

        });

        // mostrar próximas por defecto
        document.querySelector('[data-filtro="proxima"]').click();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/cita.js"></script>
    <script>
        Swal.fire({

            icon: '<?php echo $_SESSION['swal']['icon']; ?>',

            title: '<?php echo $_SESSION['swal']['title']; ?>',

            text: '<?php echo $_SESSION['swal']['text']; ?>'

        });
    </script>
    <?php unset($_SESSION['swal']); ?>
<?php endif; ?>