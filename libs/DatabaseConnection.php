<?php

class DatabaseConnection {

    public $driver, $db, $dbname;
    public $error;
           
    function __construct($driver="mysql", $dbname="mysql") {
        $this->driver=$driver;
        $this->dbname=$dbname;
        $this->db=null;
        $this->error=null;
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
    
    function query($sql, $params=null)
    {
            $stmt = $this->db->prepare($sql);
            
            $counter=0;
            
            if($params!=null)
            {
                foreach($params as $param)
                {
                    $counter++;
                    if(is_float($param))
                    {
                        $stmt->bindValue($counter, $param, PDO::PARAM_STR);
                    }
                    if(is_int($param))
                    {
                        $stmt->bindValue($counter, $param, PDO::PARAM_STR);
                    }
                    if(is_bool($param))
                    {
                        $stmt->bindValue($counter, $param, PDO::PARAM_BOOL);
                    }
                    if(is_string($param))
                    {
                        $stmt->bindValue($counter, $param, PDO::PARAM_STR);
                    }
                }
            }
            
            if (!$stmt->execute()) {

                $this->error = $stmt->errorCode();
                return false;
            } else {
                $this->error=null;
            }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}