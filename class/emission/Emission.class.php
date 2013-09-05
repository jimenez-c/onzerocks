<?php

class Emission {
    private $id;
    private $title;
    private $description;    
    private $filename;
    private $date;
    private $type;
    private $plays;
    private $comments;
    private $playlist;
    
    public function __construct($data) {
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
    public function hasId() {
        return (isset($this->id)) ? true : false;
    }

    public function setId($id) {
        $this->id = htmlspecialchars($id);
    }

    public function getTitle() {
        return stripcslashes($this->title);
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return stripslashes($this->description);
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {        
        if(preg_match("#[0-9]{4}.[0-9]{2}.[0-9]{2}#", $date)) {
            $this->date = new DateTime($date);            
        }
        elseif(preg_match("#[0-9]{2}.[0-9]{2}.[0-9]{4}#", $date)) {
            $day = substr($date, 0, 2);
            $month = substr($date, 3, 2);
            $year = substr($date, 6, 4);
            $this->date = new DateTime($year."-".$month."-".$day);            
        }               
        else throw new Exception("Le format de date est incorrect.");
    }
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = htmlspecialchars($type);
    }
    
    public function getPlays() {
        return $this->plays;
    }

    public function setPlays($plays) {
        $this->plays = $plays;
    }
    
    public function hasComment() {
        if(!isset($this->comments) || empty($this->comments)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }
    
    public function getPlaylist() {
        return stripslashes($this->playlist);
    }

    public function setPlaylist($playlist) {
        $this->playlist = $playlist;
    }




}

?>
