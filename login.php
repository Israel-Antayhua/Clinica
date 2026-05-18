<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$estilo_pagina = "login.css";
include 'includes/cabecera.php'; 
?>

<div class="login-contenedor">
    <h2>Control de Accesos</h2>
    <form action="validar.php" method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required placeholder="Ej. doctor1">

        <label>Contraseña:</label>
        <input type="password" name="password" required placeholder="••••••••">

        <button type="submit">Ingresar</button>
    </form>
</div>

<style>
    footer {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 0 !important;
        width: 100%;
    }
</style>

<?php 
include 'includes/pie.php'; 
?>