<?php

class QueryDelete extends Query {
    
    function __construct($table, $whereParams) {   
        $this->db = Database::getInstance()->getConnexion();
        $this->setTable($table);
        $this->setWhereParams($whereParams);
    }
    
    public function getQuery() {
        $query = "DELETE FROM ".$this->getTable();
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
        return $statement->execute();
    }
        

}

?>
