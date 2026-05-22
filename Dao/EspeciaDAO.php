<?php

class EspeciaDAO
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarEspecia($data)
    {

        try {

            // 🔐 1. CREAR USUARIO
            $sqlUser = "INSERT INTO especialidades (nombre, precio_consulta) VALUES (?, ?)";
            $stmtUser = $this->conexion->prepare($sqlUser);

            if (!$stmtUser) {
                return "Error usuario: " . $this->conexion->error;
            }

            $nombre = $data['nombre'];
            $precio = $data['precio'];

            $stmtUser->bind_param("si", $nombre, $precio);

            if (!$stmtUser->execute()) {
                return "Error al crear Especialidad: " . $stmtUser->error;
            }


            $stmtUser->close();

            return true;
        } catch (Exception $e) {
            return "Exception: " . $e->getMessage();
        }
    }

    public function cambiarEstado($id)
    {
        $sql = "SELECT estado FROM especialidades WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        if (!$row) {
            return false;
        }

        $nuevoEstado = ($row['estado'] == 'Activo') ? 'Inactivo' : 'Activo';

        $update = "UPDATE especialidades SET estado = ? WHERE id = ?";
        $stmt2 = $this->conexion->prepare($update);
        $stmt2->bind_param("si", $nuevoEstado, $id);
        $stmt2->execute();

        return $nuevoEstado;
    }
}
