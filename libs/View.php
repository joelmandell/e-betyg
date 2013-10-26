<?php

class View {

    function __construct() {
    }
    
    function render($name)
    {
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