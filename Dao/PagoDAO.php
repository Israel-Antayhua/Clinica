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

        // 🔥 INICIAR TRANSACCIÓN
        $this->conexion->begin_transaction();

        // 1. INSERTAR PAGO
        $sql = "INSERT INTO pagos (
                    id_cita,
                    monto,
                    estado,
                    culqi_charge_id,
                    metodo_pago
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error prepare pagos");
        }

        $stmt->bind_param(
            "idsss",
            $id_cita,
            $monto,
            $estado,
            $charge_id,
            $metodo
        );

        if (!$stmt->execute()) {
            throw new Exception("Error insert pago: " . $stmt->error);
        }

        // 2. ACTUALIZAR CITA
        $sql2 = "UPDATE citas 
                 SET estado = 'Confirmado'
                 WHERE id = ?";

        $stmt2 = $this->conexion->prepare($sql2);

        if (!$stmt2) {
            throw new Exception("Error prepare citas");
        }

        $stmt2->bind_param("i", $id_cita);

        if (!$stmt2->execute()) {
            throw new Exception("Error update cita: " . $stmt2->error);
        }

        // 🔥 CONFIRMAR TODO
        $this->conexion->commit();

        return true;

    } catch (Exception $e) {

        $this->conexion->rollback();
        return false;
    }
}
}
