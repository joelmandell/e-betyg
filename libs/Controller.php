<?php

//standard methods that get's inherited in all controllers.

class Controller {
    
    public $view, $db, $_M;
    
    function __construct() {
        
        $this->db = new DatabaseConnection("mysql", "e-betyg");
        
        if($_POST)
        {
            //If POST request, do not load view.
        }
        if($_GET)
        {
            //There is always a View class available
            //when controller is called as $_GET request
            $this->view = new View();
            
            //In the View class there is a Model class 
            //we want to make this Model available.
            $this->_M=&$this->view->_M;
        }
    }

}