<?php

//standard methods that get's inherited in all controllers.
require 'libs/e-betyg.php';

class Controller {
    
    public $view, $db, $_M, $r, $auth;
    
    function __construct($bundle=NULL) {
                        
        
        $this->db = new DatabaseConnection("mysql", "e-betyg");
        $this->auth=new Auth($this->db);

        if($_POST)
        {
            //If POST request, do not load view.
        }
        if($_GET)
        {
            //There is always a View class available
            //when controller is called as $_GET request
            $this->view = new View($bundle);
            $this->view->auth=&$this->auth;
            
            //In the View class there is a Model class 
            //we want to make this Model available.
            $this->_M=&$this->view->_M;
        }
    }

}