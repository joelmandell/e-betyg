<?php

class Model {

    private $data = array();
    
    function __construct() {
        
    }
    
    public function __get($key) {
        // for clarity you could throw an exception if isset($this->data[$key]) 
        // returns false as it is entirely possible for null to be a valid return value
        return isset($this->data[$key]) ? $this->data[$key] : "NOOO";
    }
    
    function __set($key,$value)
    {
        $this->data[$key] = $value;
    }
}