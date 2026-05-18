<?php

class Categoria {
    private $id;
    private $nombre;
    private $activo;
    private $estado;

    // Constructor opcional
    public function __construct($nombre = null, $activo = null, $estado = null) {
        $this->nombre = $nombre;
        $this->activo = $activo;
        $this->estado = $estado;
    }

    // --- Getters y Setters ---
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getActivo() {
        return $this->activo;
    }
    public function setActivo($activo) {
        $this->activo = $activo;
    }

    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }
}
