<?php
require 'libs/pbkdf2.php';

//Group-klassen innehåller funktioner för att ge grupptillhörigheter åt inloggad användare.
class Group
{
 
	function __construct() {
	}
  
	//Lista grupper från databas.
	public function GetGroups()
	{
	}
  
	public function GetPriviligies()
	{
	}
  
	public function CheckUser($user)
	{
	}
 
}
 
class Auth
{
    public $db;
    
    function __construct($db) {
        $this->db = $db->db;
    }
    
    function IsAuth()
    {
        if(isset($_SESSION["login"]))
        {
            if($_SESSION["login"]=="true")
            {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    function Logout()
    {
        foreach($_SESSION as $s)
        {
            $s=NULL;
            unset($s);
        }
        
        session_destroy();
    }
    
    function ValidateLogin()
    {
        if(isset($_POST["user"]) & !isset($_SESSION["login"]))
        {
            $o_pass=""; //Outputed password from db
            $o_salt=""; //Outputed salt from db
            $email=$_POST["user"];
            $pass=$_POST["pass"];
            
            foreach($this->db->query("SELECT * FROM user WHERE email='".$email."'") as $i)
            {
                $o_pass=$i["password"];
                $o_salt=$i["salt"];
            }

            if(validate_password($pass.$o_salt,$o_pass))
            {
                $_SESSION["login"]="true";
                return [true, "Lösenordet är rätt!"];
            } else {
                return [false, "Lösenordet är fel!"];
            }
        } else {
            if(!isset($_SESSION["login"]))
            {
                return [false, "Saknar argument"];
            }
        }
    }
}
 
class Teacher extends User 
{
 
    function __construct() {

    }
  
    //Ge $value antingen värdet TRUE om konto ska aktiveras eller FALSE om det ska avaktiveras, andra argumentet är kontot som berörs.
    function Activation(bool $value, $accountId)
    {
  
    }
  
}
 
class User
{
 
    function __construct() {

    }

    //$values är en vektor med användarinmatad information
    //för att registrera sig - som skickas med en post request via ajax.
    function Register($values)
    {

    }

    function Login($values)
    {

    }
 
}

  
?>  
