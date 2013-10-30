<?php

class AccountController extends Controller {

    function __construct() {
        parent::__construct();
        
        $this->view->render("account/index");
    }
 
    function SignIn($user="", $password="")
    {
        echo "sign in";
        echo $user;
        echo $password;
    }
    
}
