<?php

class MainController extends Controller {

    function __construct() {
        parent::__construct();
        
        $this->view->render("index/index");
    }
 
    
}
