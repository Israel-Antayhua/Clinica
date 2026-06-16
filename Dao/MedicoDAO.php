<?php

class MedicoDAO
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarMedico($data)
    {

        try {

            // 🔐 1. CREAR USUARIO
            $sqlUser = "INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)";
            $stmtUser = $this->conexion->prepare($sqlUser);

            if (!$stmtUser) {
                return "Error usuario: " . $this->conexion->error;
            }

            $usuario = $data['usuario'];
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $rol = "medico";

            $stmtUser->bind_param("sss", $usuario, $password, $rol);

            if (!$stmtUser->execute()) {
                return "Error al crear usuario: " . $stmtUser->error;
            }

            $id_usuario = $stmtUser->insert_id;
            $stmtUser->close();

            // 🧑‍⚕️ 2. CREAR MÉDICO
            $sqlMedico = "INSERT INTO medicos (id_usuario, nombre, telefono, id_especialidad)
                          VALUES (?, ?, ?, ?)";

            $stmtMedico = $this->conexion->prepare($sqlMedico);

            if (!$stmtMedico) {
                return "Error medico: " . $this->conexion->error;
            }

            $stmtMedico->bind_param(
                "issi",
                $id_usuario,
                $data['usuario'],
                $data['telefono'],
                $data['id_especialidad']
            );

            if (!$stmtMedico->execute()) {
                return "Error al crear médico: " . $stmtMedico->error;
            }

            $stmtMedico->close();

            return true;
        } catch (Exception $e) {
            return "Exception: " . $e->getMessage();
        }
    }

    public function editarMedico($data)
    {
        // actualizar datos del médico
        if (!empty($data["password"])) {
            $sql = "UPDATE medicos 
            SET nombre = ?, telefono = ?, id_especialidad = ?
            WHERE id = ?";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                "ssii",
                $data["nombre"],
                $data["telefono"],
                $data["id_especialidad"],
                $data["id"]
            );

            $stmt->execute();

            // si quiere cambiar usuario/password
            $sql2 = "UPDATE usuarios 
                SET usuario = ?, password = ?
                WHERE id = (
                    SELECT id_usuario FROM medicos WHERE id = ?
                )";

            $stmt2 = $this->conexion->prepare($sql2);
            $stmt2->bind_param(
                "ssi",
                $data["usuario"],
                password_hash($data["password"], PASSWORD_DEFAULT),
                $data["id"]
            );

            return $stmt2->execute();
        }

        // SI NO VIENE PASSWORD → NO TOCAR PASSWORD
        else {

            $sql = "UPDATE medicos 
                SET nombre = ?, telefono = ?, id_especialidad = ?
                WHERE id = ?";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                "ssii",
                $data["nombre"],
                $data["telefono"],
                $data["id_especialidad"],
                $data["id"]
            );

            $stmt->execute();

            // solo actualizar usuario
            $sql2 = "UPDATE usuarios 
                SET correo = ?
                WHERE id = (
                    SELECT id_usuario FROM medicos WHERE id = ?
                )";

            $stmt2 = $this->conexion->prepare($sql2);
            $stmt2->bind_param(
                "si",
                $data["usuario"],
                $data["id"]
            );

            return $stmt2->execute();
        }
    }
}
