<?php

class QueryUpdate extends Query {
    
    function __construct($table, $setParams, $whereParams = array()) { 
        $this->db = Database::getInstance()->getConnexion();
        $this->setTable($table);
        $this->setSetParams($setParams);
        $this->setWhereParams($whereParams);
    }
    
    public function getQuery() {
        $query = "UPDATE ".$this->getTable()." SET ";
        
        $setParams = $this->getSetParams();
        $i = 0;        
        foreach($setParams as $key => $value) {
            if($i == count($setParams) - 1 ) {
                $query .= $key." = :".$key;
            }
            else {
                $query .= $key." = :".$key.", ";
            }
            $i++;
        }
        
        $whereParams = $this->getWhereParams();
        if(!empty($whereParams)) {
            $j = 0;
            foreach($whereParams as $key => $value) {
                if($j == 0) {
                    $query .= " WHERE ";
                }
                else {
                    $query .= " AND ";
                }
                if(is_array($value)) {
                    $query .= $param[0]." ".$param[1]." :".$param[0];
                }
                else {
                    $query .= $key." = :".$key;
                }
                
                $j++;
            }   
        }
        return $query;
    }
                
    public function execute() {
        $query = $this->getQuery();
        $statement = $this->db->prepare($query);
        $whereParams = $this->getWhereParams();
        if(!empty($whereParams)) {            
            foreach($whereParams as $key => $value) {
                if(is_array($value)) {
                    $statement->bindValue($param[0], $param[2]);
                }
                else {
                    $statement->bindValue($key, $value);
                }
                
            }
        }
        $setParams = $this->getSetParams();
        if(!empty($setParams)) {
            foreach($setParams as $key => $value) {
                $statement->bindValue($key, $value);
            }
        }
        return $statement->execute();
    }          

}

?>
