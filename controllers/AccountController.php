<?php

require 'libs/pbkdf2.php';

class AccountController extends Controller {

    function __construct() {
        parent::__construct();
        
        $this->view->render("account/index");
    }
 
    function SignIn()
    {
        if($_POST)
        {
            //$_POST["password"]
        }
    }
    
    function SaltTest($password)
    {
        echo $password;
        $salt=create_hash($password);
        echo "salt: ".$salt."<br />";
        $hashed_password=create_hash($password.$salt);
        echo "<br />".$hashed_password."<br />";
        echo "<br />Res:".validate_password($password.$salt,$hashed_password);
        $this->view->render("account/index");
    }
    
}
