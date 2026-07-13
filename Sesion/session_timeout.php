<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tiempo_inactividad = 60; // 15 minutos

if (isset($_SESSION['ultima_actividad'])) {

    if ((time() - $_SESSION['ultima_actividad']) > $tiempo_inactividad) {

        // Guardamos el aviso antes de destruir
        $_SESSION['timeout'] = true;

        // Destruir sesión
        session_unset();
        session_destroy();

        // Nueva sesión para guardar el mensaje
        session_start();
        $_SESSION['timeout'] = true;

        header("Location: ../Sesion/login.php");
        exit;
    }
}

$_SESSION['ultima_actividad'] = time();