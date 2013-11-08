<?php

/*
This is a MVC Framework coded and copyrighted by Joel Mandell 2013.
For questions, please e-mail me at joelmandell@gmail.com
*/
if(!isset($_GET['c']))
{
    exit;
}

session_start();

class load {

    //TODO:
    //Really in need to chunk the code into functions 
    //and not have all code in the constructor.
    
    function __construct()
    {
        //$_DB=new DatabaseConnection("mysql", "e-betyg");

        if(isset($_GET["c"]))
        {
            $req=$_GET["c"];
            $params = explode("/", $req);
            $standard_controller=constant("index_controller");
            
            if(count($params)>0)
            {
                if($params[0]=="index.php" || $params[0]=="" || $params[0] == "/") 
                {
                    $controller_file = "controllers/".$standard_controller."Controller.php";
                } else {
                    $controller_file = "controllers/".$params[0]."Controller.php";     
                }   
            }

            //Does the file exist that we are trying to call.
            if(file_exists($controller_file))
            {
                require $controller_file;

                //If params[0] is not null aka url is www.example.com/example/
                //then load the controller //of that variables value.
                if($params[0]!="")
                {
                    //Build the class name for the Controller to load.
                    $clsName=$params[0]."Controller";
                    
                    //Do we have a class with that name?
                    if(class_exists($clsName))
                    {
                        //We have a class like that and we instantiate
                        //that class as an controller object.
                        $controller = new $clsName;
                    } else {
                        //If not load the standard controller class
                        //defined in the config/routes.php file.
                        $clsName=$standard_controller."Controller";
                        $controller = new $clsName;
                    }
                } else {
                    //If $params[0] is null aka url is www.example.com
                    //then load the standard controller.
                    $clsName=$standard_controller."Controller";
                    $controller = new $clsName;    
                }
                
                //If params is not null then we can work with potential
                //arguments. And if it is more than two items
                //that means an method in controller was called aswell.
                if(count($params)>2)
                {
                    //Is it so that there is only 2 parameters=controller and 
                    //method was called.
                    if(count($params)<2)
                    {
                        //Call this method in the controller
                        $controller->$params[1]();
                    } else {

                        //If first param is the standard controller
                        if($params[0]==$standard_controller)
                        {
                            if(method_exists($controller->$params[1]))
                            {
                                $controller->$params[1]($params[2]);
                            }
                        } else {
                            
                            $controller->$params[1]($params[2]);

                        }
                    }

                } else {
                        $controller->index();
                }

            } 
        }
    }
}
?>
