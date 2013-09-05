<?php

class User {

    private $id;
    private $pseudo;
    private $password;
    private $email;

    public function __construct($data) {
        foreach($data as $key => $value) {            
            $methodName = "set" . ucfirst($key);
            if(method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
            else throw new Exception("Impossible de construire l'objet");
        }    
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setPseudo($pseudo) {
        $this->pseudo = $pseudo;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }



}

?>