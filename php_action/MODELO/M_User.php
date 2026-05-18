<?php
class User {
    private $id;
    private $username;
    private $password;
    private $email;

    public function __construct($username = null, $password = null, $email = null) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    // --- Getters y Setters ---
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
}
?>
