<?php
// 1. Inicializamos la sesión para poder manipularla
session_start();

// 2. Destruimos todas las variables de la sesión activa
session_destroy();

// 3. Redirigimos al usuario inmediatamente de vuelta al login
header("Location: login.php");
exit();
?>