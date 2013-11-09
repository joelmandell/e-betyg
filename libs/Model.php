<?php

class Model {

    private $data = array();
    
    function __construct() {
        
    }
    
    public function __get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : "Error in value";
    }
    
    function __set($key,$value)
    {
        $this->data[$key] = $value;
    }
}