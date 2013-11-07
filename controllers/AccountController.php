<?php

require 'libs/pbkdf2.php';

class AccountController extends Controller {

    public $_M;
    
    function __construct() {
       
        parent::__construct();
        //$this->index();
        $this->view->setModel("AccountModel");

    }

    function index()
    {
        $this->view->render("account/index");  
    }
    
    function SaltTest($password="kalle")
    {
        $salt=create_hash($password);
        $hashed_password=create_hash($password.$salt);

        $this->_M->hash=$password;
        
        $this->view->render("account/index");  

    }
    
}
