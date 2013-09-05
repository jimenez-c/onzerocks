<?php

class LinkManager extends AbstractManager {
    
    private static $instance;

    private function __construct($db) {
        $this->class = "Link";
        $this->table = "links";
        $this->db = $db;
    }
    
    static public function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new self($db);
        }
        return self::$instance;
    }
    
    public function getAll() {
        $query = new QuerySelect("*", "links");
        return $query->getObjects("Link");
    }    
    public function getOne($id) {
        $query = new QuerySelect("*", "links", array("id" => $id));        
        return $query->getObject("Link");
    }
    

    public function getLinkQuery() {
        return new QuerySelect("*", "links", array());                
    }
    public function getLink() {
        return $this->getLinkQuery()->getObjects("Link");
    }
   
    public function edit(Link $link) {        
        $setParams = array(
            "url" => $link->getUrl(),
            "description" => $link->getDescription()            
        );        
        if($link->hasId()) {                        
            $whereParams = array("id" => $link->getId());
            $query = new QueryUpdate("links", $setParams, $whereParams);
            
            return $query->execute();            
        }
        else {                  
            $query = new QueryInsert("links", $setParams);
            return $query->execute();            
        }                
    }
    
    public function delete($id) {
        $query = new QueryDelete("links", array("id" => $id));        
        return $query->execute();
    }
}

?>
