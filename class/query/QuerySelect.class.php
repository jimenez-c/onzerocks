<?php

class QuerySelect extends Query {
    
    function __construct($selector, $table, $whereParams = array(), $orderBy = "", $dir = "", $limit = array()) {
        $this->db = Database::getInstance()->getConnexion();
        $this->setSelector($selector);
        $this->setTable($table);
        $this->setWhereParams($whereParams);
        $this->setOrderBy($orderBy);
        $this->setDir($dir);        
        $this->setLimit($limit);
    }
    
    public function getQuery() {
        $query = "SELECT ".$this->getSelector()." FROM ".$this->getTable();
        $params = $this->getWhereParams();                
        if(!empty($params)) {
            $i = 0;
            foreach($params as $key => $value) {
                if($i == 0) {
                    $query .= " WHERE ";
                }
                else {
                    $query .= " AND ";
                }
                if(is_array($value)) {
                    $query .= $value[0]." ".$value[1]." :".$value[0];
                }                
                else {
                    $query .= $key." = '".$value."'";
                }
                $i++;
            }
        }
        if($this->getOrderBy()) {
            $query .= " ORDER BY ".$this->getOrderBy();
        }
        if($this->getDir()) {
            $query .= " ".$this->getDir();
        }
        $limit = $this->getLimit();        
        if(!empty($limit)) {            
            if(!is_array($limit)) {              
                $query .= " LIMIT ".$limit;
            }
            else {
                $query .= " LIMIT ".$limit[0].", ".$limit[1];
            }
        }
        return $query;
    }
    
    protected function execute() {
        $query = $this->getQuery();
        $statement = $this->db->prepare($query);
        $params = $this->getWhereParams();
        if(!empty($params)) {
            foreach($params as $key => $value) {
                if(is_array($value)) {                    
                    $statement->bindValue($value[0], $value[2]);
                }                
                else {                    
                    $statement->bindValue($key, $value);
                }
            }
        }
        $statement->execute();
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {                                
            $this->results[] = $line;
        }            
        $statement->closeCursor();        
    }
    public function getResults() {
        $this->execute();
        return $this->results;
    }
    
    public function getObjects($object) {        
        $this->execute();        
        foreach($this->results as $result) {
            $this->objects[] = new $object($result);
        }        
        return $this->objects;    
    }
    public function getObject($object) {
        $this->execute();
        return new $object($this->results[0]);
    }
   

}

?>
