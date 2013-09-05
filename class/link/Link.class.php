<?php

class Link {

    private $id;
    private $url;
    private $description;    
    
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

    public function getUrl() {
        return $this->url;
    }

    public function getDescription() {
        return stripslashes($this->description);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUrl($url) {
        if($url == "") {
            throw new Exception("Un lien doit avoir une adresse URL.");
        }
        $this->url = $url;
    }

    public function setDescription($description) {
        if($description == "") {
            throw new Exception("Un lien doit avoir une description. C'est OB-LI-GÉ.");
        }
        $this->description = $description;
    }    
    
    public function hasId() {
        return (isset($this->id)) ? true : false;
    }
}

?>