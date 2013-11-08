<?php

class View {

    var $model=NULL;
    public $_M;
    
    function __construct() {
        
        //Initiate the $_M with a empty Model always.
        //Because this one will be replaced anyway
        //if user sets his model in setModel($model)
        //This will make it possible to send variables
        //to the View without an own model.
        $this->_M=new Model();
    }
    
    function setModel($model=NULL)
    {
        if($model!=NULL)
        {
            $this->model=$model;
            require "models/".$this->model.".php";
            
            //Dynamically create a class
            //in this case we want to make the class from
            //user choosen model in their Controller.
            $cls=$this->model;
            
            //Make sure that View class variable $_M
            //get's insantiated as Model Class Object.
            $this->_M=new $cls;
        }
    }
    
    function render($name)
    {              
        $file="views/".$name.".php";
        
        //This is the magic line that make's
        //it possible to discard the $this->_M in 
        //view files and instead send variables to 
        //the model by calling $_M->MyVariable.
        $_M=$this->_M;     
        
        //We want to be sure that the file 
        //that the user calls is existing.
        if(file_exists($file)===TRUE)
        {
            require $file;
        } else {
            $file="views/error/wrong_view.php";
            require $file;
        }        
    }
}