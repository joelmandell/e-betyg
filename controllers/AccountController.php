<?php

require 'libs/pbkdf2.php';

class AccountController extends Controller {

    public $_M, $db;
    
    function __construct() {
       
        parent::__construct();
        
        //Instantiate the db class from 
        //parent Controller class.
        $this->db=$this->db->db;

    }

    function index()
    {
        $this->view->setModel("AccountModel");

        $this->_M->hash="MAMMA";
        $this->view->render("account/index");  
        
    }
    
    function register()
    {
        
    }
    
    function SignIn()
    {
        $o_pass=""; //Outputed password from db
        $o_salt=""; //Outputed salt from db
        $email=$_POST["user"];
        $pass=$_POST["pass"];
        
        foreach($this->db->query("SELECT * FROM user WHERE email='".$email."'") as $i)
        {
            $o_pass=$i["password"];
            $o_salt=$i["salt"];
        }
        
        if(validate_password($pass.$o_salt,$o_pass))
        {
            $this->_M->hash="Lösenordet är rätt!";
        } else {
            $this->_M->hash="Lösenordet är fel!";
        }
        
        $this->view->render("account/index");  

    }
    
    function SaltTest($password="kalle")
    {
        //ABSOLUTE.
        /* Algorithm to create hash and salt
        $salt=create_hash($password);
        $hashed_password=create_hash($password.$salt);
        */
        
        $this->view->render("account/index");  

    }
    
}
