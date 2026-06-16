<?php

class PacienteDAO {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarPaciente($data) {
        // 🔐 1. INSERTAR USUARIO
        $sqlUsuario = "INSERT INTO usuarios (correo, password, rol) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sqlUsuario);

        if (!$stmt) {
            return "Error usuario prepare: " . $this->conexion->error;
        }

        $usuario = $data['usuario'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $rol = "paciente";

        $stmt->bind_param("sss", $usuario, $password, $rol);

        if (!$stmt->execute()) {
            return "Error al insertar usuario: " . $stmt->error;
        }

        $id_usuario = $stmt->insert_id;
        $stmt->close();

        // 🏥 2. INSERTAR PACIENTE
        $sqlPaciente = "INSERT INTO pacientes 
        (id_usuario, nombres, apellidos, dni, fecha_nacimiento, celular, direccion)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt2 = $this->conexion->prepare($sqlPaciente);

        if (!$stmt2) {
            return "Error paciente prepare: " . $this->conexion->error;
        }

        $stmt2->bind_param(
            "issssss",
            $id_usuario,
            $data['nombres'],
            $data['apellidos'],
            $data['dni'],
            $data['fecha_nacimiento'],
            $data['celular'],
            $data['direccion']
        );

        if (!$stmt2->execute()) {
            return "Error al insertar paciente: " . $stmt2->error;
        }

        $stmt2->close();

        return true;
    }
}