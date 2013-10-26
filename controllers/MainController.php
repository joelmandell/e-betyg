<?php

class MainController extends Controller {

    function __construct() {
        parent::__construct();
        
        $view = new View();
        $view->render("index/index");
    }
 
    
}
