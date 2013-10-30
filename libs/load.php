<?php

/*
This is a MVC Framework coded and copyrighted by Joel Mandell 2013.
For questions, please e-mail me at joelmandell@gmail.com


*/

class load {

    //Really in need to chunk the code into functions and not have all code in the constructor.
    function __construct()
    {

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

                if($params[0]!="")
                {
                    $clsName=$params[0]."Controller";
                    
                    if(class_exists($clsName))
                    {
                        $controller = new $clsName;
                    } else {
                        $clsName=$standard_controller."Controller";
                        $controller = new $clsName;
                    }
                } else {
                    $clsName=$standard_controller."Controller";
                    $controller = new $clsName;     

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
                
                $file="";
                
                foreach($params as $dir)
                {
                    $file.=$dir."/";
                }
                $file=rtrim($file,"/");

                if(is_file($file))
                {
                    require $file;
                }
            }
        }
    }
}
?>
