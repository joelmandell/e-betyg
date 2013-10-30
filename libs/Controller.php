<?php

//standard methods that get's inherited in all controllers.

class Controller {
    var $view;
    
    function __construct() {
        
        if($_POST)
        {
            //If POST request, do not load view.
        }
        if($_GET)
        {
            //There is always a View class available
            //when controller is called as $_GET request
            $this->view = new View();
        }
    }

}