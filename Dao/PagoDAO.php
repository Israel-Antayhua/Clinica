<?php

class Pago
{

    private $conexion;

    public function __construct($conexion)
    {

        $this->conexion = $conexion;
    }

    // REGISTRAR PAGO
    public function agregarPago($id_cita, $monto, $estado, $charge_id, $metodo)
    {
        try {
            $sql = "INSERT INTO pagos (
                    id_cita,
                    monto,
                    estado,
                    culqi_charge_id,
                    metodo_pago
                )
                VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "idsss",
                $id_cita,
                $monto,
                $estado,
                $charge_id,
                $metodo
            );

            // 2. Actualizar cita a Confirmada
            $sql2 = "UPDATE citas 
                 SET estado = 'Confirmado'
                 WHERE id = ?";

            $stmt2 = $this->conexion->prepare($sql2);
            $stmt2->bind_param("i", $id_cita);
            $stmt2->execute();

            // Confirmar todo
            $this->conexion->commit();

            return true;
        } catch (Exception $e) {

            // Revertir si algo falla
            $this->conexion->rollback();
            return false;
        }
    }
}
