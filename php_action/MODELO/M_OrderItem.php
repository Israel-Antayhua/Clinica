<?php

class OrderItem {
    private $id;
    private $orderId;
    private $productId;
    private $cantidad;
    private $precio;
    private $total;
    private $estado;

    public function __construct($orderId = null, $productId = null, $cantidad = null, $precio = null, $total = null, $estado = 1) {
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->total = $total;
        $this->estado = $estado;
    }

    // --- Getters y setters ---
    public function getOrderId() { return $this->orderId; }
    public function getProductId() { return $this->productId; }
    public function getCantidad() { return $this->cantidad; }
    public function getPrecio() { return $this->precio; }
    public function getTotal() { return $this->total; }
}
