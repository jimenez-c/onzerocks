<?php

class QueryInsert extends Query {
    
    function __construct($table, $setParams) { 
        $this->db = Database::getInstance()->getConnexion();
        $this->setTable($table);
        $this->setSetParams($setParams);        
    }
    
    public function getQuery() {
        $query = "INSERT INTO ".$this->getTable()."(";         
        $params = $this->getSetParams();
        $length = count($params);
        $i = 0;
        foreach($params as $key => $value) {
            if($i == $length - 1) {
                $query .= $key.") ";
            }            
            else {
                $query .= $key.", ";
            }
            $i++;
        }
        $query .= "VALUES(";
        $j = 0;
        foreach($params as $key => $value) {
            if($j == $length - 1) {
                $query .= ":".$key.") ";
            }            
            else {
                $query .= ":".$key.", ";
            }
            $j++;
        }
        return $query;
    }
    
    public function execute() {
        $query = $this->getQuery();
        $statement = $this->db->prepare($query);
        $params = $this->getSetParams();
        if(!empty($params)) {
            foreach($params as $key => $value) {
                $statement->bindValue($key, $value);
            }
        }
        return $statement->execute();
    }

}

?>
