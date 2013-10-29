<?php

class AccountController extends Controller {

    function __construct() {
        parent::__construct();
        
        $view = new View();
        $view->render("account/index");
    }
 
    
}
