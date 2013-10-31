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
        if($driver=="mysql")
        {
            echo "working";
            //Create database class that user can call later.
            $this->db = new PDO("mysql:host=".$host.";dbname="
            .$dbname.";charset=".$charset,$user,$pass);
        }
    }

}