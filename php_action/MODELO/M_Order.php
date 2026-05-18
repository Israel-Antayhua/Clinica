<?php

class Order {
    private $id;
    private $fecha;
    private $clienteNombre;
    private $clienteContacto;
    private $subtotal;
    private $iva;
    private $total;
    private $descuento;
    private $totalGeneral;
    private $pagado;
    private $pendiente;
    private $tipoPago;
    private $estadoPago;
    private $lugarPago;
    private $gstn;
    private $estado;
    private $usuarioId;

    public function __construct($fecha = null, $clienteNombre = null, $clienteContacto = null, $subtotal = null,
                                $iva = null, $total = null, $descuento = null, $totalGeneral = null,
                                $pagado = null, $pendiente = null, $tipoPago = null, $estadoPago = null,
                                $lugarPago = null, $gstn = null, $estado = 1, $usuarioId = null) {
        $this->fecha = $fecha;
        $this->clienteNombre = $clienteNombre;
        $this->clienteContacto = $clienteContacto;
        $this->subtotal = $subtotal;
        $this->iva = $iva;
        $this->total = $total;
        $this->descuento = $descuento;
        $this->totalGeneral = $totalGeneral;
        $this->pagado = $pagado;
        $this->pendiente = $pendiente;
        $this->tipoPago = $tipoPago;
        $this->estadoPago = $estadoPago;
        $this->lugarPago = $lugarPago;
        $this->gstn = $gstn;
        $this->estado = $estado;
        $this->usuarioId = $usuarioId;
    }

    // --- Getters y setters ---
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }

    public function getClienteNombre() { return $this->clienteNombre; }
    public function setClienteNombre($clienteNombre) { $this->clienteNombre = $clienteNombre; }

    public function getClienteContacto() { return $this->clienteContacto; }
    public function setClienteContacto($clienteContacto) { $this->clienteContacto = $clienteContacto; }

    public function getSubtotal() { return $this->subtotal; }
    public function setSubtotal($subtotal) { $this->subtotal = $subtotal; }

    public function getIva() { return $this->iva; }
    public function setIva($iva) { $this->iva = $iva; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getDescuento() { return $this->descuento; }
    public function setDescuento($descuento) { $this->descuento = $descuento; }

    public function getTotalGeneral() { return $this->totalGeneral; }
    public function setTotalGeneral($totalGeneral) { $this->totalGeneral = $totalGeneral; }

    public function getPagado() { return $this->pagado; }
    public function setPagado($pagado) { $this->pagado = $pagado; }

    public function getPendiente() { return $this->pendiente; }
    public function setPendiente($pendiente) { $this->pendiente = $pendiente; }

    public function getTipoPago() { return $this->tipoPago; }
    public function setTipoPago($tipoPago) { $this->tipoPago = $tipoPago; }

    public function getEstadoPago() { return $this->estadoPago; }
    public function setEstadoPago($estadoPago) { $this->estadoPago = $estadoPago; }

    public function getLugarPago() { return $this->lugarPago; }
    public function setLugarPago($lugarPago) { $this->lugarPago = $lugarPago; }

    public function getGstn() { return $this->gstn; }
    public function setGstn($gstn) { $this->gstn = $gstn; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getUsuarioId() { return $this->usuarioId; }
    public function setUsuarioId($usuarioId) { $this->usuarioId = $usuarioId; }
}
