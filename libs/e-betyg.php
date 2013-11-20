<?php
require 'libs/pbkdf2.php';

//Group-klassen innehåller funktioner för att ge grupptillhörigheter åt inloggad användare.
class Group
{    
    private $auth, $db, $user;
 
    function __construct(&$db, &$auth, &$user) {
        $this->db=&$db;
        $this->auth = &$auth;
        $this->user=&$user;
    }
  
    public function Delete($name)
    {
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && $this->user->GroupName=="ADMIN")
        {
            $id="";

            if($name!="ADMIN")
            {
                $this->db->query("DELETE FROM `userProp` WHERE groupId IN (SELECT id FROM `group` WHERE groupName ='".$name."');");
                $this->db->query("DELETE FROM `group` WHERE groupName ='".$name."';");

                foreach($this->db->query("SELECT * FROM `group` WHERE groupName = '".$name."'") as $i)
                {
                    $id=$i["id"];
                }
                
                if($id=="")
                {
                    echo "true";
                } else {
                    echo "false";
                }
                
            } else {
                echo "false";
            }
        }
    }
    
    public function Create($name)
    {
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && $this->user->GroupName=="ADMIN")
        {
            $this->db->query("INSERT INTO `group` (groupName) VALUES('".$name."');");
            
            $id="";
            foreach($this->db->query("SELECT * FROM `group` WHERE groupName = '".$name."'") as $i)
            {
                $id=$i["id"];
            }
            echo $id;
        }
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
        } else {
            return false;
        }
    }
    
        //Lista grupper från databas.
    public function GetUsers($groupId)
    {
        $users=NULL;
        if($this->auth->IsAuth())
        {
            foreach($this->db->query("SELECT * FROM user WHERE id IN (SELECT userId
            FROM userprop WHERE groupId ='".$groupId."');") as $i)
            {
                $users[$i["id"]]=$i["email"];
            }
            return $users;
        } else {
            return false;
        }
    }
    
    function GetPriviligies()
    {
        if($this->auth->IsAuth())
        {
            $GroupID=$this->user->GroupId;
            foreach($this->db->query("SELECT * FROM `group` WHERE id='".$GroupID."'") as $i)
            {
                $this->user->GroupName=$i["groupName"];
            }
            return $this->user->GroupName;
        } else {
            return false;
        }
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
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
        $this->group = new Group($this->db, $this, $this->user);  
    }
    
    function IsAuth()
    {
        return isset($_SESSION["login"])=="true" ? true : false;
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
                //Find User properties and add the to our user object
                //that later will be stored in a session, so we can use it
                //across the user logged in session.
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
                    //Store the priviligies:
                    $this->group->GetPriviligies();
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
    public $UserId, $GroupId, $UserPropertiesId, $InvokedPriviligies,
            $Approved, $GroupName;
    
    function __construct() {
        
    }
}

class User extends UserProperties
{
    public $Email;
    
    function __construct() {
        parent::__construct();
        
    }
} 

?>  
