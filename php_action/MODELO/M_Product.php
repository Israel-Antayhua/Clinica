<?php
class Product {
    private $id;
    private $name;
    private $image;
    private $brandId;
    private $categoryId;
    private $quantity;
    private $rate;
    private $active;
    private $status;

    public function __construct($name = null, $image = null, $brandId = null, $categoryId = null, $quantity = null, $rate = null, $active = null, $status = null) {
        $this->name = $name;
        $this->image = $image;
        $this->brandId = $brandId;
        $this->categoryId = $categoryId;
        $this->quantity = $quantity;
        $this->rate = $rate;
        $this->active = $active;
        $this->status = $status;
    }

    // --- Getters y Setters ---
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getImage() { return $this->image; }
    public function setImage($image) { $this->image = $image; }

    public function getBrandId() { return $this->brandId; }
    public function setBrandId($brandId) { $this->brandId = $brandId; }

    public function getCategoryId() { return $this->categoryId; }
    public function setCategoryId($categoryId) { $this->categoryId = $categoryId; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getRate() { return $this->rate; }
    public function setRate($rate) { $this->rate = $rate; }

    public function getActive() { return $this->active; }
    public function setActive($active) { $this->active = $active; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }
}
?>
