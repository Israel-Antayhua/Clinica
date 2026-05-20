<?php

class Pago {

    private $conexion;

    public function __construct($conexion) {

        $this->conexion = $conexion;

    }

    // REGISTRAR PAGO
    public function agregarPago($id_cita, $monto, $estado, $charge_id, $metodo) {

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

        return $stmt->execute();

    }

}