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

    <!-- BOTONES FILTRO -->
    <div class="d-flex gap-2 mb-4">

        <button class="btn btn-primary rounded-4 px-4 filtro-btn active"
            data-filtro="pendiente">

            <i class="bi bi-clock-history me-2"></i>

            Pendientes

        </button>

        <button class="btn btn-light border rounded-4 px-4 filtro-btn"
            data-filtro="pagado">

            <i class="bi bi-check-circle me-2"></i>

            Historial de Pagos

        </button>

    </div>

    <!-- CARD -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- TABLA -->
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
                            "SELECT c.*,e.nombre AS especialidad 
                        FROM citas c
                        INNER JOIN medicos m ON c.id_medico = m.id 
                        INNER JOIN especialidades e ON m.id_especialidad = e.id
                        WHERE id_paciente = '" . $_SESSION['id_usuario'] . "'
                        ORDER BY fecha ASC, hora ASC"
                        );

                        while ($f = $pagos->fetch_assoc()):

                            $badge_pago = ($f['estado_pago'] == 'Pagado')
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-warning-subtle text-warning border border-warning-subtle';

                            $estadoClase = strtolower($f['estado_pago']);
                        ?>

                            <tr class="fila-pago <?php echo $estadoClase; ?>">

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

                                        <button class="btn btn-primary btn-sm rounded-3 px-3 btn-pagar"
                                            data-id="<?php echo $f['id']; ?>"
                                            data-monto="<?php echo $f['monto']; ?>">

                                            <i class="bi bi-credit-card me-1"></i>

                                            Registrar Pago

                                        </button>

                                    <?php else: ?>

                                        <a href="comprobante.php?id=<?php echo $f['id']; ?>"
                                            target="_blank"
                                            class="btn btn-light border rounded-4 px-4 shadow-sm">

                                            <i class="bi bi-file-earmark-check text-success me-2"></i>

                                            Ver Comprobante

                                        </a>

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
        const botones = document.querySelectorAll(".filtro-btn");
        const filas = document.querySelectorAll(".fila-pago");

        botones.forEach(btn => {

            btn.addEventListener("click", () => {

                // estilos botones
                botones.forEach(b => {
                    b.classList.remove("btn-primary");
                    b.classList.add("btn-light", "border");
                });

                btn.classList.remove("btn-light", "border");
                btn.classList.add("btn-primary");

                let filtro = btn.dataset.filtro;

                filas.forEach(fila => {

                    if (fila.classList.contains(filtro)) {
                        fila.style.display = "";
                    } else {
                        fila.style.display = "none";
                    }

                });

            });

        });

        // mostrar pendientes por defecto
        document.querySelector('[data-filtro="pendiente"]').click();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.culqi.com/js/v4"></script>
    <script>
        Culqi.publicKey = 'pk_test_k3Q7XjOI4T773JjS';

        document.querySelectorAll('.btn-pagar').forEach(btn => {

            btn.addEventListener("click", function() {

                const monto = btn.dataset.monto;
                const id_cita = btn.dataset.id;

                // Configurar checkout dinámico
                Culqi.settings({

                    title: 'Maison de Santé',

                    currency: 'PEN',

                    amount: monto * 100

                });

                // Guardamos datos globalmente
                window.id_cita_actual = id_cita;
                window.monto_actual = monto;

                Culqi.open();

            });

        });

        // RESPUESTA CULQI
        function culqi() {

            if (Culqi.token) {

                let token = Culqi.token.id;

                fetch("../Controler/Get_Pagar.php", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json"
                        },

                        body: JSON.stringify({

                            token: token,

                            id_cita: window.id_cita_actual,

                            monto: window.monto_actual

                        })

                    })
                    .then(r => r.json())
                    .then(data => {

                        console.log(data);

                        if (data.success) {
                            Culqi.close();

                            Swal.fire({

                                icon: 'success',

                                title: 'Pago exitoso',

                                text: 'La cita fue pagada correctamente'

                            }).then(() => {

                                location.reload();

                            });

                        } else {
                            Swal.fire({

                                icon: 'error',

                                title: $respuesta,

                                text: 'La tarjeta fue rechazada'

                            });
                            alert(data.mensaje);

                        }

                    });

            }

        }
    </script>
<?php endif; ?>