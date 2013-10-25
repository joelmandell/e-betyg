<?php

require 'config/routes.php';

class load {

    function __construct()
    {

        if(isset($_GET["c"]))
        {
            $req=$_GET["c"];
            $params = explode("/", $req);
            $standard_controller=  constant("index_controller");
            
            if(count($params)>0)
            {

                if($params[0]=="index.php" || $params[0]=="" || $params[0] = "/") 
                {
                    $controller_file = "controllers/".$standard_controller.".php";
                } else {
                    $controller_file = "controllers/".$params[0].".php";     
                }
            }

            if(file_exists($controller_file))
            {
                require $controller_file;

                if(class_exists($params[0]))
                {
                    $controller = new $params[0];
                } else {
                    $controller = new $standard_controller;
                }
                //If params is not null then we can work with potential arguments.
                if(count($params)>2)
                {
                //Get the controller class...
                //If it is more than two items
                //that mean an argument was passed
                    if(count($params)<2)
                    {
                        //Call this method in the controller
                        $controller->$params[1]();
                    } else {
                        //Call this method in the controller with argument 'foo'
                        
                        //If first param is not the standard controller
                        if($params[0]==$standard_controller)
                        {
                            $controller->$params[1]($params[2]);
                        } else {
                            $controller->$params[1]($params[2]);                            
                        }
                    }

                }

            } else {
                echo "File error";
            }
        }
    }
}
?>