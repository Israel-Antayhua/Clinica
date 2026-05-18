<?php
class Brand {
    private $id;
    private $name;
    private $active;
    private $status;

    public function __construct($name = null, $active = null, $status = null) {
        $this->name = $name;
        $this->active = $active;
        $this->status = $status;
    }

    // --- Getters y Setters ---
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    public function getActive() {
        return $this->active;
    }
    public function setActive($active) {
        $this->active = $active;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
}
?>
