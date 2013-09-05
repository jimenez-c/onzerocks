<?php

class EmissionManager extends AbstractManager {
    
    private static $instance;
    
    private function __construct($db) {
        $this->class = "Emission";
        $this->table = "emissions";
        $this->db = $db;        
    }

    static public function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new EmissionManager($db);
        }
        return self::$instance;
    }
    
    public function getAll() {
        $query = new QuerySelect("*", "emissions");
        return $query->getObjects("Emission");
    }    
    public function getOne($id) {
        $query = new QuerySelect("*", "emissions", array("id" => $id));
        return $query->getObject("Emission");
    }
    
    public function getSpecialesQuery() {
        $query = new QuerySelect("*", "emissions", array("type" => "speciales"), "date", "DESC");
        return $query;
    }
    public function getSpeciales() {
        return $this->getSpecialesQuery()->getObjects("Emission");        
    }
    
    public function getRecentesQuery() {
        $query = new QuerySelect("*", "emissions", array("type" => "recentes"), "date", "DESC");
        return $query;
    }
    
    public function getLivesQuery() {
        $query = new QuerySelect("*", "emissions", array("type" => "live"), "date", "DESC");
        return $query;
    }
    public function getLives() {        
        return $this->getLivesQuery()->getObjects("Emission");        
    }

    public function edit(Emission $emission) {        
       $setParams = array(
           "title" => $emission->getTitle(),
           "description" => $emission->getDescription(),
           "date" => $emission->getDate()->format("Y-m-d"),
           "type" => $emission->getType(),
           "playlist" => $emission->getPlaylist()
       );
       $hasId = $emission->hasId();
       if($hasId) {
           $whereParams = array("id" => $emission->getId());
           $query = new QueryUpdate("emissions", $setParams, $whereParams);
           return $query->execute();
//           return $this->query("UPDATE emissions SET 
//               title = :title, 
//               description = :description, 
//               date = :date, 
//               type = :type 
//            WHERE id = :id", $data, false);
       }
       else {
           $setParams["filename"] = $emission->getFilename();
           $query = new QueryInsert("emissions", $setParams);
           return $query->execute();
//           return $this->query("INSERT INTO emissions(title, description, date, type, filename) 
//               VALUES(:title, :description, :date, :type, :filename)", $data, false); 
       }
    }
    
    public function delete($id) {
        $emission = $this->getOne($id);
        if(isset($_POST["delFile"]) && $_POST["delFile"] == "on") {
            unlink("store/audio/".$emission->getType(). "/" . $emission->getFilename());  
        }        
        $query = new QueryDelete("emissions", array("id" => $id));
        return $query->execute();
//       return $this->query("DELETE FROM emissions WHERE id = :id", array(
//            "id" => $id
//        ), false);
    }
    
    public function addPlay($id) {        
        $statement = $this->db->prepare("UPDATE emissions SET plays = plays + 1 WHERE id = :id");
        return $statement->execute(array("id" => $id));     
    }

}

?>
