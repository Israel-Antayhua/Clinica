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

            <form action="../Controler/Add_Cita.php" method="POST">
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
                            echo "<option value='{$e['id']}'>{$e['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
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

                    <label class="form-label fw-semibold">
                        Hora
                    </label>

                    <input type="time"
                        name="hora"
                        class="form-control rounded-3"
                        required>

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
                            "SELECT 
                                c.*,
                                m.nombre AS medico,
                                e.nombre AS especialidad
                            FROM citas c
                            INNER JOIN medicos m ON c.id_medico = m.id
                            INNER JOIN especialidades e ON m.id_especialidad = e.id
                            WHERE c.id_paciente = " . $_SESSION['id_usuario']
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
    <script>
        document.getElementById("especialidad").addEventListener("change", function() {

            let idEspecialidad = this.value;

            fetch("../Controler/Get_Medicos.php?id=" + idEspecialidad)
                .then(res => res.text()) // 👈 cambia a text temporalmente
                .then(data => {
                    return JSON.parse(data);
                })
                .then(data => {
                    console.log(data);

                    let selectMedico = document.getElementById("id_medico");

                    selectMedico.innerHTML = "<option value=''>Seleccione médico</option>";

                    data.forEach(medico => {
                        selectMedico.innerHTML += `
                    <option value="${medico.id}">
                        Dr. ${medico.nombre}
                    </option>
                `;
                    });

                });

        });
    </script>
<?php endif; ?>