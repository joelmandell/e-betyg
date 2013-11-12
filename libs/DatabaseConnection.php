<?php

class DatabaseConnection {

    var $driver, $db, $dbname;
    
    function __construct($driver="mysql", $dbname="mysql") {
        $this->driver=$driver;
        $this->dbname=$dbname;
        $this->db=null;
        
        //Load some constants stored in config/routes.php
        $pass=constant("db_pass");
        $user=constant("db_user");
        $host=constant("db_host");
        $charset=constant("db_charset");
        $port=constant("db_port");
        
        if($driver=="mysql")
        {
            //Create database class that user can call later.
            $conn="mysql:host=".$host.";port=".$port.";dbname="
            .$dbname.";charset=".$charset;
            
            $this->db = new PDO($conn,$user,$pass);            

        } else {
            throw new Exception("Driver ".$driver." does not exist", "DatabaseConnection", "");
        }
    }
}