<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maison de Santé - Sistema Integral</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu CSS -->
    <link rel="stylesheet" href="css/<?php echo $estilo_pagina; ?>">
</head>
<body>

<?php if (isset($_SESSION['usuario'])): 
    // Detectamos en qué vista estamos para marcarla como activa en el menú
    $vista_actual = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';
?>

<div class="d-flex w-100 min-vh-100">

    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 vh-100" style="width: 280px;">

        <!-- Logo -->
        <div class="fs-4 fw-bold mb-4 border-bottom pb-3">
            ➕ Maison de Santé
        </div>

        <!-- Usuario -->
        <div class="mb-4">
            <div>
                👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?>
            </div>
            <small class="text-secondary">
                Rol: <?php echo ucfirst($_SESSION['rol']); ?>
            </small>
        </div>

        <!-- Menú -->
        <ul class="nav nav-pills flex-column mb-auto">

            <li class="nav-item mb-2">
                <a href="index.php"
                   class="nav-link <?php echo $vista_actual == 'inicio' ? 'active' : 'text-white'; ?>">
                    🏠 Inicio
                </a>
            </li>

            <?php if ($_SESSION['rol'] == 'paciente'): ?>

                <li class="nav-item mb-2">
                    <a href="index.php?vista=citas"
                       class="nav-link <?php echo $vista_actual == 'citas' ? 'active' : 'text-white'; ?>">
                        📅 Mis Citas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?vista=pagos"
                       class="nav-link <?php echo $vista_actual == 'pagos' ? 'active' : 'text-white'; ?>">
                        💳 Mis Pagos
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#"
                       class="nav-link text-white">
                        👤 Mi Perfil
                    </a>
                </li>

            <?php endif; ?>

            <?php if ($_SESSION['rol'] == 'admin'): ?>

                <li class="nav-item mb-2">
                    <a href="index.php?vista=agenda"
                       class="nav-link <?php echo $vista_actual == 'agenda' ? 'active' : 'text-white'; ?>">
                        📅 Mi Agenda
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?vista=mantenimiento"
                       class="nav-link <?php echo $vista_actual == 'mantenimiento' ? 'active' : 'text-white'; ?>">
                        👨‍⚕️ Catálogos / Mantenimiento
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?vista=reportes"
                       class="nav-link <?php echo $vista_actual == 'reportes' ? 'active' : 'text-white'; ?>">
                        📊 Reportes Estadísticos
                    </a>
                </li>

            <?php endif; ?>

            <!-- Logout -->
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link text-danger">
                    🚪 Cerrar Sesión
                </a>
            </li>

        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4 main-content">
<?php endif; ?>