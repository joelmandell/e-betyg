<?php

require 'libs/e-betyg.php';

class AccountController extends Controller {

    public $_M, $db, $auth, $r;

    function __construct($bundle) {
        parent::__construct();
        
        $this->r=$bundle; 
        
        ////Get router class that we 
        //Instantiate the db class from 
        //parent Controller class.
        $this->db=$this->db->db;
        $this->auth=new Auth($this->db);
    }

    function index()
    {
        $this->view->setModel("AccountModel");
        $this->view->render("account/index");          
    }
    
    function register()
    {
        
    }
    
    function SignIn()
    {       
        $validation = $this->auth->ValidateLogin();
        
        if($validation[0])
        {
            $this->_M->msg=$validation[1];
            $this->view->render("account/index");  
        } else {
            //Send message from validation function to the router
            //then the router will do our redirect.
            $this->r->passModelData(["msg",$validation[1]]);
            
            //Withour argument it will take us to root.
            $this->r->doRedirect(); 
        }
    }    
}
