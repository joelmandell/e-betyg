<?php

/*
This is a MVC Framework coded and copyrighted by Joel Mandell 2013.
For questions, please e-mail me at joelmandell@gmail.com
*/
if(!isset($_GET['c']))
{
    exit;
}

class load {

    //Really in need to chunk the code into functions and not have all code in the constructor.
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

                //If params[0] is not null, then load the controller 
                //of that variables value.
                if($params[0]!="")
                {
                    //Build the class name for the Controller to load.
                    $clsName=$params[0]."Controller";
                    
                    //Do we have a class with that name?
                    if(class_exists($clsName))
                    {
                        $controller = new $clsName;
                    } else {
                        //If not load the standard controller class
                        //defined in the config/routes.php file.
                        $clsName=$standard_controller."Controller";
                        $controller = new $clsName;
                    }
                } else {
                    //If $params[0] is null then load the standard controller.
                    $clsName=$standard_controller."Controller";
                    $controller = new $clsName;     
                }
                
                //If params is not null then we can work with potential
                //arguments.
                //And if it is more than two items
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

                }

            } else {    
                
                //Start building up filename.
                //This is so regular files, like css files and 
                //images, and javascript files will be loaded.
                //Because my framework is built on RewriteRules in
                //htaccess that hinders those to be loaded, we will
                //do it codewise.
                $file="";
                
                foreach($params as $dir)
                {
                    $file.=$dir."/";
                }
                
                $file=rtrim($file,"/");

                if(is_file($file))
                {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    
                    $mime=finfo_file($finfo, $file);

                    
                    //We are going to send mimetype of file in headers...
                    //But first check if they are allowed..
                    echo $mime;die();
                    if($mime!=="text/css" || $mime!=="text/html" || $mime!=="text/php")
                    {
                        die("Not correct mime");
                        exit;
                    } else {
                        header('Content-type:'.$mime.'');
                        require $file;
                    }
                    
                } else {
                    exit;
                }
            }
        }
    }
}
?>
