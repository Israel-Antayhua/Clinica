<?php
class Conexion {
    private static $instancia = null;
    private $conexion;

    private $localhost = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "proyecto_gestion2";

    private function __construct() {
        $this->conexion = new mysqli($this->localhost, $this->username, $this->password, $this->dbname);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->conexion;
    }
}
?>