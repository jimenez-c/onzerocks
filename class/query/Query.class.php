<?php

abstract class Query {
    protected $db;
    
    protected $selector;
    protected $table;
    protected $setParams;
    protected $whereParams;
    protected $orderBy;
    protected $dir;
    protected $limit;
    
    protected $results = array();
    protected $objects = array();         
      
    public function getSelector() {
        return $this->selector;
    }

    public function setSelector($selector) {
        $this->selector = $selector;
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }
    
    public function getOrderBy() {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    public function getDir() {
        return $this->dir;
    }

    public function setDir($dir) {
        $this->dir = $dir;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getSetParams() {
        return $this->setParams;
    }

    public function setSetParams($setParams) {
        $this->setParams = $setParams;
    }

    public function getWhereParams() {
        return $this->whereParams;
    }

    public function setWhereParams($whereParams) {
        $this->whereParams = $whereParams;
    }




}

?>
