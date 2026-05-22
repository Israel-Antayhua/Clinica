<?php

session_start();

if (!isset($_SESSION['usuario'])) {

    header("Location: ../Sesion/login.php");
    exit();
}
switch ($_SESSION['rol']) {

    case 'medico':
        header("Location: Vistas/index_medico.php");
        break;

    case 'paciente':
        header("Location: Vistas/index_paciente.php");
        break;
}
exit;
?>
