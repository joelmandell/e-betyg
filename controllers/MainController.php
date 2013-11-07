<?php

class MainController extends Controller {

    function __construct() {
        parent::__construct();
        
        $this->view->setModel("IndexModel");
        $this->view->render("index/index");
        
    }
 
    
}
