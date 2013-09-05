<?php

class Comment {
    private $id;
    private $pseudo;
    private $description;
    private $date;
    
    function __construct($data) {
        foreach($data as $key => $value) {            
            $methodName = "set" . ucfirst($key);
            if(method_exists($this, $methodName)) {
                $this->$methodName($value);
            }            
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescription() {
        return stripslashes($this->description);
    }

    public function setDescription($description) {
        if($description == "") {
            throw new Exception("Le commentaire ne peut être vide.");
        }
        $this->description = $description;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = new DateTime($date);
    }    
    
    public function getPseudo() {
        return stripcslashes($this->pseudo);
    }

    public function setPseudo($pseudo) {
        $this->pseudo = $pseudo;
    }



}

?>
