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

        $sqlUser = "INSERT INTO usuarios (correo, password, rol) VALUES (?, ?, ?)";
        $stmtUser = $this->conexion->prepare($sqlUser);

        if (!$stmtUser) {
            die("ERROR PREP USER: " . $this->conexion->error);
        }

        $usuario = $data['usuario'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $rol = "medico";

        if (!$stmtUser->bind_param("sss", $usuario, $password, $rol)) {
            die("ERROR BIND USER");
        }

        if (!$stmtUser->execute()) {
            die("ERROR EXEC USER: " . $stmtUser->error);
        }

        $id_usuario = $this->conexion->insert_id;
        $stmtUser->close();

        $sqlMedico = "INSERT INTO medicos (id_usuario, nombre, telefono, id_especialidad)
                      VALUES (?, ?, ?, ?)";

        $stmtMedico = $this->conexion->prepare($sqlMedico);

        if (!$stmtMedico) {
            die("ERROR PREP MEDICO: " . $this->conexion->error);
        }

        if (!$stmtMedico->bind_param(
            "issi",
            $id_usuario,
            $data['nombre'],
            $data['telefono'],
            $data['id_especialidad']
        )) {
            die("ERROR BIND MEDICO");
        }

        if (!$stmtMedico->execute()) {
            die("ERROR EXEC MEDICO: " . $stmtMedico->error);
        }

        $stmtMedico->close();

        return true;

    } catch (Exception $e) {
        die("EXCEPTION: " . $e->getMessage());
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
