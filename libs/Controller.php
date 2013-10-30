<?php

//standard methods that get's inherited in all controllers.

class Controller {
    var $view;
    
    function __construct() {
        
        //There is always a View class available.
        $this->view = new View();
    }

}