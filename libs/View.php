<?php

class View {

    public $model=NULL, $_M, $auth, $user, $group, $db;
    
    function __construct(&$db) {
        $this->auth=new Auth($db);
        $this->db=$db;
        
        //Initiate the $_M with a empty Model always.
        $this->_M=new Model($db, $this->auth, $this->group);

        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
        $this->group=new Group($db, $this->auth, $this->user);     
    }
    
    function setModel($model=NULL)
    {
        if($model!=NULL)
        {
            $this->model=$model;
            //$this->model->auth=&$this->auth;
            require "models/".$this->model.".php";
            
            $class=$this->model;
            
            //Make sure that View class variable $_M
            //get's insantiated as Model Class Object.
            $this->_M=new $class($this->db, $this->auth, $this->group);
        }
    }
    
    function render($name)
    {              
        $file="views/".$name.".php";
        
        //magic lines: discard the need of using $this-> in view files.
        $_M=&$this->_M;     
        $user=&$this->user;
        $group=&$this->group;
        $auth=&$this->auth;
        $js="";
        foreach($_M->jslibs as $jslib)
        {
            $js.="<script src=\"".$jslib."\"></script>\n";
        }
                
        if(file_exists($file)===TRUE)
        {
            require $file;
        } else {
            $file="views/error/wrong_view.php";
            require $file;
        }        
    }
}