<?php

session_start();

if (!isset($_SESSION['usuario'])) {

    header("Location: ../Sesion/login.php");
    exit();

}

include 'ConexionDB/conexion.php';

$estilo_pagina = "css/estilos.css";

include 'includes/cabecera.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Bienvenido al Sistema Maison de Santé
        </h3>

        <small class="text-secondary">
            Panel principal del sistema clínico
        </small>

    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-5">

        <h4 class="fw-bold">

            Hola,
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
            👋

        </h4>

        <p class="text-secondary mb-0">

            Utiliza el menú lateral para navegar por los módulos del sistema.

        </p>

    </div>

</div>

<?php

$conexion->close();

include 'includes/pie.php';

?>