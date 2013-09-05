<?php

class News {

    private $id;
    private $title;
    private $description;
    private $date; 
    private $comments;
    
    public function __construct($data = array()) {
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

    public function getTitle() {
        return stripslashes($this->title);
    }

    public function getDescription() {
        return stripslashes($this->description);
    }

    public function getDate() {
        return $this->date;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        if($title == "") {
            throw new Exception("Une actu doit avoir un titre.");
        }
        $this->title = htmlspecialchars($title);
    }

    public function setDescription($description) {
        if($description == "") {
            throw new Exception("Une actu doit avoir une description. C'est OB-LI-GÉ.");
        }
        $this->description = $description;
    }

    public function setDate($date) {
        $this->date = new DateTime($date);
    }
    
    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function hasComment() {
        if(!isset($this->comments) || empty($this->comments)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function hasId() {
        return (isset($this->id)) ? true : false;
    }
}

?>