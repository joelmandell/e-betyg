<?php

class View {

    var $model, $_M;
    
    function __construct() {
    }
    
    function setModel($model=NULL)
    {
        if($model!=NULL)
        {
            $this->model=$model;
        }
    }
    
    function render($name)
    {
        if($this->model!=NULL)
        {
            require "models/".$this->model.".php";
            $_M=new $this->model;
        }
        
        $file="views/".$name.".php";
                
        if(file_exists($file)===TRUE)
        {
            require $file;
        } else {
            $file="views/error/wrong_view.php";
            require $file;
        }        
    }

}