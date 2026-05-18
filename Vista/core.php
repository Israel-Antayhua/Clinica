<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../php_action/db_connect.php';

$connect = Conexion::getInstancia();
// echo $_SESSION['userId'];

if(!$_SESSION['userId']) {
	header('location:'.$store_url);	
} 



?>