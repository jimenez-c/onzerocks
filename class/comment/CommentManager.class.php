<?php

class CommentManager extends AbstractManager {
    
    private static $instance;
    
    private function __construct($db) {
        $this->class = "Comment";
        $this->table = "comments";
        $this->db = $db;        
    }

    static public function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new CommentManager($db);
        }
        return self::$instance;
    }
    
    public function getNewsComments(News $news) {        
        $statement = $this->db->prepare("SELECT * FROM comments LEFT JOIN news_comments ON id = idComment WHERE idNews = :id ORDER BY date DESC");
        $statement->bindValue("id", $news->getId());
        $statement->execute();
        $result = array();
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {            
            $result[] = new Comment($line);
        }              
        return $result;
    }
    
    public function getEmissionComments(Emission $emission) {        
        $statement = $this->db->prepare("SELECT * FROM comments LEFT JOIN emissions_comments ON id = idComment WHERE idEmission = :id ORDER BY date DESC");
        $statement->bindValue("id", $emission->getId());
        $statement->execute();
        $result = array();
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {            
            $result[] = new Comment($line);
        }              
        return $result;
    }
    
    public function addComment(Comment $comment) {
        // insertion commentaire
        $pseudo = ($comment->getPseudo() == "") ? "un anonyme" : $comment->getPseudo();
        $date = new DateTime();
        $statement = $this->db->prepare("INSERT INTO comments(pseudo, description, date) VALUES(:pseudo, :desc, :date)");
        $statement->bindValue("pseudo", $pseudo);
        $statement->bindValue("desc", $comment->getDescription());
        $statement->bindValue("date", $date->format("Y-m-d H:i"));
        $statement->execute();
        $idComment = $this->db->lastInsertId();
        
        return $idComment;
    }
    
    public function addNewsComment(Comment $comment, News $news) {        
        // insertion lien commentaire / news
        $statement = $this->db->prepare("INSERT INTO news_comments(idNews, idComment) VALUES(:idNews, :idComment)");
        $statement->bindValue("idNews", $news->getId());
        $statement->bindValue("idComment", $comment->getId());
        return $statement->execute();
    }
    
    public function addEmissionComment(Comment $comment, Emission $emission) {
        // insertion lien commentaire / emission
        $statement = $this->db->prepare("INSERT INTO emissions_comments(idEmission, idComment) VALUES(:idEmission, :idComment)");
        $statement->bindValue("idEmission", $emission->getId());
        $statement->bindValue("idComment", $comment->getId());
        return $statement->execute();
    }
        
    public function getOne($id) {
        $query = new QuerySelect("*", "comments", array("id" => $id));
        return $query->getObject("Comment");
    }
    
    public function delete($id) {
        
        $statement = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        $statement->bindValue("id", $id);        
        $statement->execute();                
        
        $statement = $this->db->prepare("DELETE FROM news_comments WHERE idComment = :id");
        $statement->bindValue("id", $id);        
        $statement->execute();
        
        $statement = $this->db->prepare("DELETE FROM emissions_comments WHERE idComment = :id");
        $statement->bindValue("id", $id);        
        $statement->execute();
        
        return true;
    }

}

?>
