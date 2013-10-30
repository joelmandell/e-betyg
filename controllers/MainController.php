<?php

class MainController extends Controller {

    function __construct() {
        parent::__construct();
        
        $this->view->setModel("index_model");
        $this->view->render("index/index");
        
    }
 
    
}
