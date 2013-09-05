<?php

class NewsManager extends AbstractManager {
    
    private static $instance;

    private function __construct($db) {
        $this->class = "News";
        $this->table = "news";
        $this->db = $db;
    }
    
    static public function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new NewsManager($db);
        }
        return self::$instance;
    }
    
    public function getAll() {
        $query = new QuerySelect("*", "news");
        return $query->getObjects("News");
    }    
    public function getOne($id) {
        $query = new QuerySelect("*", "news", array("id" => $id));        
        return $query->getObject("News");
    }
    

    public function getNewsQuery() {
        return new QuerySelect("*", "news", array(), "date", "DESC");                
    }
    public function getNews() {
        return $this->getNewsQuery()->getObjects("News");
    }
   
    public function edit(News $news) {
        $date = new DateTime();
        $setParams = array(
            "title" => $news->getTitle(),
            "description" => $news->getDescription(),
            "date" => $date->format("Y-m-d")
        );
        $hasId = $news->hasId();
        if($hasId) {                        
            $whereParams = array("id" => $news->getId());
            $query = new QueryUpdate("news", $setParams, $whereParams);
            
            return $query->execute();
            //return $this->query("UPDATE news SET title = :title, description = :description, date = :date WHERE id = :id", $data, false);
        }
        else {                  
            $query = new QueryInsert("news", $setParams);
            return $query->execute();
            //return $this->query("INSERT INTO news(title, description, date) VALUES(:title, :description, :date)", $data, false);    
        }                
    }
    
    public function delete($id) {
        $query = new QueryDelete("news", array("id" => $id));        
        return $query->execute();
//        return $this->query("DELETE FROM news WHERE id = :id", array(
//            "id" => $id
//        ), false);        
    }
}

?>
