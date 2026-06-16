<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maison de Santé - Sistema Integral</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="bg-light">
    <?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit();
    }
    ?>

    <?php if (isset($_SESSION['usuario'])):

        $vista_actual = basename($_SERVER['PHP_SELF']);
    ?>
        <div class="d-flex min-vh-100">

            <!-- Sidebar -->
            <aside
                id="sidebar"
                class="text-white d-flex flex-column shadow-lg position-fixed top-0 start-0 vh-100"
                style="width: 290px; background-color: #102542; z-index:1030;">

                <!-- Logo -->
                <div class="p-4 border-bottom border-secondary border-opacity-25">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-primary rounded-4 d-flex justify-content-center align-items-center shadow"
                            style="width: 60px; height: 60px;">

                            <i class="bi bi-hospital fs-2 text-white"></i>

                        </div>

                        <div>

                            <h5 class="fw-bold mb-0">
                                Maison de Santé
                            </h5>

                            <small class="text-light opacity-75">
                                Sistema Integral Clínico
                            </small>

                        </div>

                    </div>

                </div>

                <!-- Usuario -->
                <div class="p-4 border-bottom border-secondary border-opacity-25">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center shadow"
                            style="width: 55px; height: 55px;">

                            <i class="bi bi-person-fill fs-4 text-white"></i>

                        </div>

                        <div>

                            <?php
                            $nombre = $_SESSION['usuario'];
                            $prefijo = ($_SESSION['rol'] === 'medico') ? "Dr. " : "";
                            ?>

                            <div class="fw-semibold small">
                                <?php echo $prefijo . htmlspecialchars($nombre); ?>
                            </div>

                            <small class="text-light opacity-75">
                                <?php echo ucfirst($_SESSION['rol']); ?>
                            </small>

                        </div>

                    </div>

                </div>

                <!-- Menú -->
                <div class="p-3 flex-grow-1">

                    <ul class="nav nav-pills flex-column gap-2">

                        <!-- Inicio -->
                        <li class="nav-item">

                            <a href="../index.php"
                                class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small transition
                       <?php echo $vista_actual == ('index_' . $_SESSION['rol'] . '.php')
                            ? 'active bg-primary shadow text-white'
                            : 'text-white opacity-75'; ?>">

                                <i class=" bi bi-house-door-fill fs-6"></i>

                                <span>Inicio</span>

                            </a>

                        </li>

                        <?php if ($_SESSION['rol'] == 'paciente'): ?>

                            <!-- Citas -->
                            <li class="nav-item">

                                <a href="../Vistas/citas.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'citas.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-calendar-check-fill fs-6"></i>

                                    <span>Mis Citas</span>

                                </a>

                            </li>

                            <!-- Pagos -->
                            <li class="nav-item">

                                <a href="../Vistas/pagos.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'pagos.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-credit-card-2-front-fill fs-6"></i>

                                    <span>Mis Pagos</span>

                                </a>

                            </li>

                            <!-- Perfil -->
                            <li class="nav-item">

                                <a href="perfil.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'perfil.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-person-vcard-fill fs-6"></i>

                                    <span>Mi Perfil</span>

                                </a>

                            </li>

                        <?php endif; ?>

                        <?php if ($_SESSION['rol'] == 'medico'): ?>

                            <!-- Agenda -->
                            <li class="nav-item">

                                <a href="../Vistas/agenda.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'agenda.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-calendar-week-fill fs-6"></i>

                                    <span>Mi Agenda</span>

                                </a>

                            </li>

                            <!-- Reportes -->
                            <li class="nav-item">

                                <a href="../Vistas/reportes.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'reportes.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-bar-chart-line-fill fs-6"></i>

                                    <span>Reportes</span>

                                </a>

                            </li>

                        <?php endif; ?>
                        <?php if ($_SESSION['rol'] == 'administrador'): ?>
                            <!-- Mantenimiento -->
                            <li class="nav-item">

                                <a href="../Vistas/mantenimiento.php"
                                    class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-4 fw-medium small
                           <?php echo $vista_actual == 'mantenimiento.php'
                                ? 'active bg-primary shadow text-white'
                                : 'text-white opacity-75'; ?>">

                                    <i class="bi bi-gear-wide-connected fs-6"></i>

                                    <span>Mantenimiento</span>

                                </a>

                            </li>
                        <?php endif; ?>
                    </ul>

                </div>

                <!-- Logout -->
                <div class="p-3 border-top border-secondary border-opacity-25">

                    <a href="../Sesion/logout.php"
                        class="btn btn-outline-light w-100 rounded-4 py-2 small fw-semibold">

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Cerrar Sesión

                    </a>

                </div>

            </aside>

            <!-- Contenido Principal -->
            <main class="flex-grow-1 p-3 p-md-4" id="mainContent">

                <!-- Navbar Superior -->
                <div class="bg-white rounded-4 shadow-sm p-3 mb-4 d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-0">
                            Panel Principal
                        </h5>

                        <small class="text-secondary">
                            Bienvenido al sistema clínico
                        </small>

                    </div>

                    <div class="d-flex align-items-center gap-3">

                        <button class="btn btn-light rounded-circle shadow-sm">
                            <i class="bi bi-bell"></i>
                        </button>

                        <button class="btn btn-light rounded-circle shadow-sm">
                            <i class="bi bi-gear"></i>
                        </button>

                    </div>

                </div>

                <!-- Aquí va tu contenido -->

            <?php endif; ?>