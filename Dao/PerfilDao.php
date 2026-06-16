<?php

class PerfilDAO
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // ➕ insertar cita
    public function actualizarPerfil($nombres, $apellidos, $telefono, $direccion, $correo, $idPaciente)
    {

        $sql = "UPDATE pacientes p
                INNER JOIN usuarios u ON u.id = p.id_usuario
                SET p.nombres = ?,
                    p.apellidos = ?,
                    p.celular = ?,
                    p.direccion = ?,
                    u.correo = ?
                WHERE p.id_paciente = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssi", $nombres, $apellidos, $telefono, $direccion,$correo, $idPaciente);

        return $stmt->execute();
    }
}
