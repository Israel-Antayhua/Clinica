<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';
$estilo_pagina = "estilos.css";
include 'includes/cabecera.php';
?>

<?php if ($vista == 'inicio'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Bienvenido al Sistema de Gestión Maison de Santé</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="card-title">
                Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋
            </h3>

            <p class="card-text">
                Utiliza el menú lateral de la izquierda para navegar por los diferentes módulos asignados a tu rol de
                <strong><?php echo $_SESSION['rol']; ?></strong>.
            </p>
        </div>
    </div>

<?php endif; ?>

<?php if ($vista == 'citas' && $_SESSION['rol'] == 'paciente'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Mis Citas</h2>

        <button class="btn btn-primary"
            onclick="document.getElementById('form-registro').style.display='block'">
            ➕ Nueva Cita
        </button>
    </div>

    <div id="form-registro" class="card shadow-sm border-0 mb-4"
        style="display:none; max-width: 500px;">

        <div class="card-body">

            <h3 class="card-title mb-4">Registrar Nueva Reserva</h3>

            <form action="guardar.php" method="POST">

                <input type="hidden" name="nombre"
                    value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>">

                <div class="mb-3">
                    <label class="form-label">Fecha:</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora:</label>
                    <input type="time" name="hora" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Especialidad:</label>

                    <select name="especialidad" class="form-select">

                        <?php
                        $esps = $conexion->query("SELECT * FROM especialidades WHERE estado='Activo'");
                        while ($e = $esps->fetch_assoc())
                            echo "<option value='" . $e['nombre'] . "'>" . $e['nombre'] . "</option>";
                        ?>

                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    Confirmar Reserva
                </button>

                <button type="button"
                    class="btn btn-secondary mt-2"
                    onclick="document.getElementById('form-registro').style.display='none'">
                    Cancelar
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="border-bottom border-primary border-2 text-primary fw-bold mb-3 pb-2"
                style="width: 120px;">
                Próximas citas
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Especialidad</th>
                            <th>Médico</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $citas = $conexion->query("SELECT * FROM citas WHERE nombre_paciente = '" . $_SESSION['usuario'] . "'");

                        while ($f = $citas->fetch_assoc()):
                            $badge = ($f['estado'] == 'Confirmada') ? 'bg-success' : 'bg-warning';
                        ?>

                            <tr>

                                <td><?php echo date('d/m/Y', strtotime($f['fecha'])); ?></td>

                                <td><?php echo $f['hora']; ?></td>

                                <td><?php echo $f['especialidad']; ?></td>

                                <td><?php echo $f['medico']; ?></td>

                                <td>
                                    <span class="badge <?php echo $badge; ?>">
                                        <?php echo $f['estado']; ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="eliminar.php?id=<?php echo $f['id']; ?>"
                                        class="btn btn-danger btn-sm">
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

<?php if ($vista == 'pagos' && $_SESSION['rol'] == 'paciente'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Mis Pagos</h2>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Cita / Especialidad</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $pagos = $conexion->query("SELECT * FROM citas WHERE nombre_paciente = '" . $_SESSION['usuario'] . "'");

                        while ($f = $pagos->fetch_assoc()):
                            $badge_pago = ($f['estado_pago'] == 'Pagado') ? 'bg-success' : 'bg-warning';
                        ?>

                            <tr>

                                <td>Reserva - <?php echo $f['especialidad']; ?></td>

                                <td><?php echo date('d/m/Y', strtotime($f['fecha'])); ?></td>

                                <td>S/ <?php echo $f['monto']; ?></td>

                                <td>
                                    <span class="badge <?php echo $badge_pago; ?>">
                                        <?php echo $f['estado_pago']; ?>
                                    </span>
                                </td>

                                <td>

                                    <?php if ($f['estado_pago'] == 'Pendiente'): ?>

                                        <a href="pagar.php?id=<?php echo $f['id']; ?>"
                                            class="btn btn-primary btn-sm">
                                            Registrar Pago
                                        </a>

                                    <?php else: ?>

                                        <button class="btn btn-secondary btn-sm" disabled>
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

<?php if ($vista == 'agenda' && $_SESSION['rol'] == 'admin'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Mi Agenda Médica</h2>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="mb-3">
                <input type="text"
                    id="buscador"
                    class="form-control"
                    placeholder="Buscar paciente en tiempo real...">
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Paciente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Especialidad</th>
                            <th>Estado Cita</th>
                            <th>Pago</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $agenda = $conexion->query("SELECT * FROM citas");

                        while ($f = $agenda->fetch_assoc()):
                            $b_c = ($f['estado'] == 'Confirmada') ? 'bg-success' : 'bg-warning';
                            $b_p = ($f['estado_pago'] == 'Pagado') ? 'bg-success' : 'bg-warning';
                        ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($f['nombre_paciente']); ?>
                                    </strong>
                                </td>

                                <td><?php echo date('d/m/Y', strtotime($f['fecha'])); ?></td>

                                <td><?php echo $f['hora']; ?></td>

                                <td><?php echo $f['especialidad']; ?></td>

                                <td>
                                    <span class="badge <?php echo $b_c; ?>">
                                        <?php echo $f['estado']; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?php echo $b_p; ?>">
                                        <?php echo $f['estado_pago']; ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>

<?php if ($vista == 'mantenimiento' && $_SESSION['rol'] == 'admin'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            Mantenimiento de Catálogos de Médicos y Especialidades
        </h2>
    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <h3 class="card-title mb-3">Gestión de Médicos</h3>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $meds = $conexion->query("SELECT * FROM medicos");

                        while ($m = $meds->fetch_assoc()):
                        ?>

                            <tr>

                                <td><?php echo $m['id']; ?></td>

                                <td><?php echo $m['nombre']; ?></td>

                                <td><?php echo $m['especialidad']; ?></td>

                                <td><?php echo $m['telefono']; ?></td>

                                <td>
                                    <span class="badge bg-success">
                                        <?php echo $m['estado']; ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="card-title mb-3">Gestión de Especialidades</h3>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Especialidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $espe = $conexion->query("SELECT * FROM especialidades");

                        while ($e = $espe->fetch_assoc()):
                        ?>

                            <tr>

                                <td><?php echo $e['id']; ?></td>

                                <td><?php echo $e['nombre']; ?></td>

                                <td>
                                    <span class="badge bg-success">
                                        <?php echo $e['estado']; ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>

<?php if ($vista == 'reportes' && $_SESSION['rol'] == 'admin'): ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Reportes Estadísticos de Atenciones</h2>
    </div>

    <?php
    $total_citas = $conexion->query("SELECT COUNT(*) as t FROM citas")->fetch_assoc()['t'];

    $total_pagadas = $conexion->query("SELECT COUNT(*) as t FROM citas WHERE estado_pago='Pagado'")->fetch_assoc()['t'];

    $total_pendientes = $conexion->query("SELECT COUNT(*) as t FROM citas WHERE estado_pago='Pendiente'")->fetch_assoc()['t'];
    ?>

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0 text-center bg-light h-100">

                <div class="card-body">

                    <h4 class="text-secondary">
                        Total de Citas
                    </h4>

                    <h1 class="text-primary">
                        <?php echo $total_citas; ?>
                    </h1>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0 text-center bg-success-subtle h-100">

                <div class="card-body">

                    <h4 class="text-success">
                        Atendidas / Pagadas
                    </h4>

                    <h1 class="text-success">
                        <?php echo $total_pagadas; ?>
                    </h1>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0 text-center bg-warning-subtle h-100">

                <div class="card-body">

                    <h4 class="text-warning">
                        Pendientes de Pago
                    </h4>

                    <h1 class="text-warning">
                        <?php echo $total_pendientes; ?>
                    </h1>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="card-title">
                Consolidado de Estado de Citas por Día
            </h3>

            <p class="text-secondary small">
                Distribución analítica de cargas operativas en la clínica:
            </p>

            <div class="d-flex align-items-end gap-3 p-4 bg-light border-start border-2 border-bottom"
                style="height: 180px;">

                <div class="bg-primary text-white text-center"
                    style="width:40px; height:40%;">
                    Lu
                </div>

                <div class="bg-primary text-white text-center"
                    style="width:40px; height:75%;">
                    Ma
                </div>

                <div class="bg-primary text-white text-center"
                    style="width:40px; height:95%;">
                    Mi
                </div>

                <div class="bg-primary text-white text-center"
                    style="width:40px; height:50%;">
                    Ju
                </div>

                <div class="bg-primary text-white text-center"
                    style="width:40px; height:85%;">
                    Vi
                </div>

            </div>

        </div>

    </div>

<?php endif; ?>

<?php
$conexion->close();
include 'includes/pie.php';
?>