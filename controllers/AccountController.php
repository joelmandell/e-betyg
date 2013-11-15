<?php

class AccountController extends Controller {

    public $_M, $db, $r, $auth;

    function __construct($bundle) {
        parent::__construct();
        $this->r=$bundle; 
        
        ////Get router class that we 
        //Instantiate the db class from 
        //parent Controller class.
        $this->db=$this->db->db;
        
    }

    function index()
    {
        $this->view->setModel("AccountModel");
        $this->view->render("account/index");          
    }
    
    function register()
    {
        
    }
    
    function SignOut()
    {
        $this->auth->Logout();
        $this->r->doRedirect();
    }

    
    function SignIn()
    {       
        $validation = $this->auth->ValidateLogin();
        
        if($validation[0])
        {
            //User is validated.
        } else {
            //Send message from validation function to the router
            //then the router will do our redirect.
            $this->r->passModelData(["msg",$validation[1]]);
            $this->r->doRedirect(); 
        }
        
        //Without argument it will take us to root page.
        $this->r->doRedirect();  
    }    
}
