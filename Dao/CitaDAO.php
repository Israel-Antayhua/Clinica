<?php

class CitaDAO {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // 🔍 verificar cruce de citas por médico
    public function existeCruce($fecha, $hora, $id_medico) {

        $sql = "SELECT * FROM citas 
                WHERE fecha = ? AND hora = ? AND id_medico = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $fecha, $hora, $id_medico);
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows > 0;
    }

    // ➕ insertar cita
    public function insertarCita($id_usuario, $id_medico, $monto, $fecha, $hora) {

        $sql = "INSERT INTO citas (id_paciente, id_medico, fecha, hora,monto)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iissi", $id_usuario, $id_medico, $fecha, $hora,$monto);

        return $stmt->execute();
    }
}