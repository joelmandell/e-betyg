<?php
require 'libs/pbkdf2.php';

//Group-klassen innehåller funktioner för att ge grupptillhörigheter åt inloggad användare.
class Group
{
    
    private $auth, $db, $user;
 
    function __construct($db, $auth) {
        $this->db=$db;
        $this->auth = &$auth;
    }
  
    //Lista grupper från databas.
    public function GetGroups()
    {
        $group=NULL;
        if($this->auth->IsAuth())
        {
            foreach($this->db->query("SELECT * FROM `group` WHERE 1") as $i)
            {
                $group[$i["id"]]=$i["groupName"];
            }
            return $group;
        }    
    }
    
    public function GetPriviligies()
    {
    }

    public function CheckUser($user)
    {
    }
    
    function __destruct() {
       unset($this->db);
       unset($this->auth);
    }
}
 
class Auth
{
    public $db, $group, $user;
    
    function __construct($db) {
        $this->db = $db->db;
        $this->group = new Group($this->db, $this);
        $this->user=new User();
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
            $o_pass=NULL; //Outputed password from db
            $o_salt=NULL; //Outputed salt from db
            $email=$_POST["user"];
            $pass=$_POST["pass"];
            $UserId=NULL;
            
            foreach($this->db->query("SELECT * FROM user WHERE email='".$email."'") as $i)
            {
                $o_pass=$i["password"];
                $o_salt=$i["salt"];
                $UserId=$i["id"];
            }

            if(validate_password($pass.$o_salt,$o_pass))
            {
                foreach($this->db->query("SELECT * FROM userprop WHERE userId='".$UserId."'") as $i)
                {
                    $this->user->Approved=$i["approved"];
                    $this->user->GroupId=$i["groupId"];
                    $this->user->InvokedPriviligies=$i["invokePriviligies"];
                    $this->user->UserId=$i["userId"];
                }                
                                
                if($this->user->Approved)
                {
                    $_SESSION["login"]="true";
                    $this->user->Email=$email;
                    $_SESSION["user"]=$this->user;
                    return [true, "Lösenordet är rätt!"];
                } else {
                    return [true, "Konto ej aktiverat!"];
                }
                
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
 
class UserProperties {
    public $UserId, $GroupId, $UserPropertiesId;
    
    function __construct() {
        
    }
}

class User extends UserProperties
{
    public $Email, $InvokedPriviligies, $Approved;
    
    function __construct() {
        parent::__construct();
    }
} 

?>  
