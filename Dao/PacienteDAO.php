<?php

class PacienteDAO
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarPaciente($data)
    {
        $this->conexion->begin_transaction();

        try {

            // INSERT usuarios
            $sqlUsuario = "INSERT INTO usuarios (correo, password, rol) VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sqlUsuario);

            $usuario = $data['usuario'];
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $rol = "paciente";

            $stmt->bind_param("sss", $usuario, $password, $rol);
            $stmt->execute();

            $id_usuario = $stmt->insert_id;
            $stmt->close();

            // INSERT pacientes
            $sqlPaciente = "INSERT INTO pacientes
            (id_usuario, nombres, apellidos, dni, fecha_nacimiento, celular, direccion)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt2 = $this->conexion->prepare($sqlPaciente);

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

            $stmt2->execute();
            $stmt2->close();

            // Confirmar cambios
            $this->conexion->commit();

            return [
                "status" => "ok",
                "message" => "Usuario registrado correctamente."
            ];
        } catch (mysqli_sql_exception $e) {

            // Deshacer todo
            $this->conexion->rollback();

            if ($e->getCode() == 1062) {

                if (str_contains($e->getMessage(), "correo") || str_contains($e->getMessage(), "usuario")) {
                    return [
                        "status" => "error",
                        "message" => "El correo electrónico ya está registrado."
                    ];
                }

                if (str_contains($e->getMessage(), "dni")) {
                    return [
                        "status" => "error",
                        "message" => "El DNI ya está registrado."
                    ];
                }
            }

            return [
                "status" => "error",
                "message" => "Ocurrió un error interno."
            ];
        }
    }
}
