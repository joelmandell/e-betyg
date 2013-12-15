<?php
require 'libs/PasswordHash.php';

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
        if(filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS) === false) {
            echo "false";
            exit;
        }   
        
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            $id="";

            if($name!="ADMIN")
            {
                //Get groups that current user belongs to.
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userProp` WHERE userId = ? AND invokedPriviligies=1) AND name=?", [$this->user->UserId,$name]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                } 
                     
                $this->db->query("DELETE FROM `userProp` WHERE groupId IN (SELECT id FROM `group` WHERE groupName =?);", [$name]);
                $this->db->query("DELETE FROM `group` WHERE groupName =?;",[$name]);

                foreach($this->db->query("SELECT * FROM `group` WHERE groupName = ?", [$name]) as $i)
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
                echo "false here";
            }
        } else if($this->auth->IsAuth() && $this->user->InvokedPriviligies)
        {
            $id="";

            if($name!="ADMIN")
            {
                //Get groups that current user belongs to.
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userProp` WHERE userId = ? AND invokedPriviligies=1) AND name=?", [$this->user->UserId,$name]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                } 
                     
                $this->db->query("DELETE FROM `userProp` WHERE groupId IN (SELECT id FROM `group` WHERE groupName =?);", [$name]);
                $this->db->query("DELETE FROM `group` WHERE groupName =?;",[$name]);

                foreach($this->db->query("SELECT * FROM `group` WHERE groupName = ?", [$name]) as $i)
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
                echo "false here";
            } 
        }
    }
    
    public function Create($name)
    {    
        //TODO: UPDATE USER SO HE BELONGS TO THE CREATED GROUP.

        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            $this->db->query("INSERT INTO `group` (groupName) VALUES(?);",[$name]);
            
            $id="";
            
            if(!$this->user->BelongsToGroupByName("ADMIN"))
            {    
                foreach($this->db->query("SELECT * FROM `group` WHERE groupName = ?", [$name]) as $i)
                {
                    $id=$i["id"];
                    $this->db->query("INSERT INTO userprop (userId, groupId, approved, invokePriviligies) VALUES(?,?,?,?);",[$this->user->UserId,$id,"1","1"]);
                }
            } else {
                $id="0";
            }
            echo $id;
        } else {
            echo "false";
        }
    }
    
    public function GetGroupsSafeMode()
    {
        //Add function here so a non registered user can 
        //see groups when he is registering.
        foreach($this->db->query("SELECT * FROM `group` WHERE 1") as $i)
        {
            if($i["groupName"]=="ADMIN") continue;
            $group[$i["id"]]=$i["groupName"];
        }
        return $group;
    }
    
    //Lista grupper från databas.
    public function GetGroups()
    {
        $group=NULL;
        if($this->auth->IsAuth())
        {
            if($this->user->BelongsToGroupByName("ADMIN") && $this->user->InvokedPriviligies)
            {
                foreach($this->db->query("SELECT * FROM `group` WHERE 1") as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }
            } else if($this->user->InvokedPriviligies) {
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userProp` WHERE userId = ?)", [$this->user->UserId]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }   
            } else {
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userProp` WHERE userId = ?)", [$this->user->UserId]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }   
            }
            return $group;
        } else {
            exit;
        }
    }
    
        //Lista grupper från databas.
    public function GetUsers($groupId)
    {
        $users=NULL;
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies)
        {
            foreach($this->db->query("SELECT * FROM user WHERE id IN (SELECT userId
            FROM userprop WHERE groupId =? AND approved=1);", [$groupId]) as $i)
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
            $GroupIDS=$this->user->GroupIds;
            
            foreach($GroupIDS as $GroupID)
            {
                foreach($this->db->query("SELECT * FROM `group` WHERE id=?",[$GroupID]) as $i)
                {
                    $this->user->GroupNames[]=$i["groupName"];
                }
            }
            return $this->user->GroupNames;
        } else {
            return false;
        }
    }

    function __destruct() {
       unset($this->db);
       unset($this->auth);
    }
}
 

class Upload {

    private $content, $mime, $db, $doc, $user, $auth;
    
    function __construct(&$db, &$auth, &$group, &$doc) {
        $this->db=$db;
        $this->doc=$doc;
        $this->auth=$auth;
        if($auth->IsAuth()) isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
    }
        
    function updateDoc(&$doc)
    {
        $this->doc=$doc;
    }
    
    function store($file)
    {
        $doc=$this->doc;
        $blob=file_get_contents($file);
        $time=time();
        $corrected=0;
        
        //If the Admin or the teacher does the upload - they do not need
        //to wait for correction of document or need to correct their own
        //document in order to be showed.
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies)
        {
          $corrected=1;  
        }
        
        $this->db->query("INSERT INTO `doc` (file,userId,timestamp, groupId) VALUES(?,?,?,?);"
                . "INSERT INTO `docproperty` (docId, timestamp) SELECT id, timestamp FROM doc WHERE timestamp=? AND userId=?;"
                . "UPDATE `doc` SET propId = (SELECT id FROM `docproperty` WHERE timestamp = ?) WHERE timestamp=? AND userId=?;"
                . "UPDATE `docproperty` SET mime=?,fileName=?,dateUploaded=?,usercomment=?, groupPublic=?, corrected=? WHERE timestamp = ? AND docId=(select id FROM doc WHERE userId=? AND timestamp=?);",
                [$blob,$doc->userId,$time, $doc->groupId,$time,$doc->userId, $time, $time, $doc->userId,$doc->mime, $doc->fileName, $doc->dateUploaded, $doc->usercomment,$doc->groupPublic, $corrected,$time, $doc->userId, $time]);

        if($this->db->error==null)
        {
            echo "true";
        } else {
            echo "false";
        }
    }
    
}

class UploadedDoc
{
    var $db, $auth, $user;
    function __construct(&$db,&$auth) {
        $this->db=$db;
        $this->auth=$auth;
        if($auth->IsAuth()) isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
    }
        
    function PendingCorrection($groupId)
    {
        $data=null;
        foreach($this->db->query("SELECT fileName, email, propId FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND docproperty.corrected=0 AND groupId=? AND a.userId=u.id;",[$groupId]) as $i){
            $data[$i["propId"]]=$i["email"]."|".$i["fileName"];
        }    
        return json_encode($data);
    }
    
    function StoreAsDocObject($docId)
    {
        $doc = new Doc();
        
        if(!is_numeric($docId)) exit;
        
        foreach($this->db->query("SELECT docId, corrected, grade, usercomment, comment, fileName, mime, dateUploaded, dateCorrected, groupPublic, userId, groupId, email "
                . " FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND docproperty.corrected=0 AND docId=? AND a.userId=u.id;",[$docId]) as $i){
            $doc->comment=$i["comment"];
            $doc->corrected=$i["corrected"];
            $doc->dateCorrected=$i["dateCorrected"];
            $doc->dateUploaded=$i["dateUploaded"];
            $doc->docId=$i["docId"];
            $doc->email=$i["email"];
            $doc->fileName=$i["fileName"];
            $doc->grade=$i["grade"];
            $doc->groupId=$i["groupId"];
            $doc->groupPublic=$i["groupPublic"];
            $doc->mime=$i["mime"];
            $doc->propId=$i["docId"];
            $doc->userId=$i["userId"];
            $doc->usercomment=$i["usercomment"];
        } 
        
        return $doc;
    }
    
}

//Almost every variable here has its equvilant in the database,
//some of them are set at runtime from the logic - for example the $downloadPath.
class DocProperty
{
    public $docId, $corrected, $grade, $comment, $fileName,
            $mime, $dateUploaded, $dateCorrected, $groupPublic, $usercomment,
            $email, $downloadPath;
    
    function __construct() 
    {

    }  
    
}

class Doc extends DocProperty
{
    public $userId, $groupId, $propId;
    
    function __construct()
    {
        parent::__construct();
    }  
}

class Auth
{
    public $db, $group, $user;
    private $hasher;
    
    function __construct($db) {
        $this->db = $db;
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
        $this->group = new Group($this->db, $this, $this->user);  
        $this->hasher = new PasswordHash(8, FALSE);     
    }
    
    function IsAuth()
    {
        return isset($_SESSION["login"])=="true" ? true : false;
    }
    
    function PreferredGroup($userId)
    {
        if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->db->query("SELECT groupId FROM userprop WHERE userId=? AND userprop.approved=0;",[$userId]) as $i)
            {
                return $i["groupId"];                         
            }
            return 0;
        } else if($this->IsAuth() && $this->user->InvokedPriviligies && !$this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->db->query("SELECT groupId FROM userprop WHERE userId=? AND userprop.approved=0;",[$userId]) as $i)
            {
                if($i["groupId"]==1 || $i["groupId"]==0) return 0;
                return $i["groupId"];                             
            }
            return 0;
        } else {
            exit;
        }
    }
    
    function ActiveUsers()
    {
        $users = new ArrayObject();
        if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=1;") as $i)
            {
                $users[$i["id"]]=$i["email"];                             
            }
        } else if($this->IsAuth() && $this->user->InvokedPriviligies && !$this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->user->GroupIds as $GroupId)
            {
                foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=1 AND userprop.groupId=?;",[$GroupId]) as $i)
                {
                    $users[$i["id"]]=$i["email"];                             
                }
            }
        } else {
            exit;
        }
        return $users;   
    }
    
    function ActivateUser($id,$group=0)
    {
        if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            $this->db->query("UPDATE userprop SET approved=1, groupId=? WHERE approved=0 AND userId=?",[$group,$id]);
        } else if($this->IsAuth() && $this->user->InvokedPriviligies) {
            $this->db->query("UPDATE userprop SET approved=1, groupId=? WHERE approved=0 AND userId=?",[$group,$id]);
        }
        
        $data="";
        foreach($this->db->query("SELECT groupId FROM userprop WHERE approved=1 AND userId=? AND groupId=?;",[$id,$group]) as $i)
        {
            $data=$i["groupId"];                            
        }
        return $data;
    }
    
    function InactiveUsers()
    {
        $users = new ArrayObject();
        if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=0;") as $i)
            {
                $users[$i["id"]]=$i["email"];                             
            }
        } else if($this->IsAuth() && $this->user->InvokedPriviligies) {
            
            foreach($this->user->GroupIds as $GroupId)
            {
                foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=0 AND groupId=?;",[$GroupId]) as $i)
                {
                    $users[$i["id"]]=$i["email"];                             
                }
            }
        }
        return $users;
    }
    
    function Register()
    {
        $email=$_POST["user"];
        $pass=$_POST["pass"];
        $group=$_POST["selected_group"];
        
        if($group=="1")
        {
            return [false, "Otillåten åtgärd"];
        }
        
        if($group=="0")
        {
            return [false, "Du glömde ange önskad grupp att bli medlem i"];
        }
        
        if(filter_var($pass,FILTER_SANITIZE_SPECIAL_CHARS) === false) {
            return [false, "Ogiltiga lösenords-tecken."];    
        }
        
        if($email=="" && $pass=="")
        {
            return [false, "Användarnamn och lösen saknas."];
        }

        if($email=="")
        {
            return [false, "Användarnamn saknas."];
        }

        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return [false, "Ogiltig e-mail adress."];
        }    
        
        if(strlen($pass) < 8)
        {
            return [false, "Lösenordet är för kort, det behövs minst 8 tecken."];
        }
        
        $salt=$this->hasher->HashPassword($pass);
        $pass_hash=$this->hasher->HashPassword($pass.$salt);
        $this->db->query("INSERT INTO `user` (email,password, salt) VALUES(?,?,?);",[$email,$pass_hash,$salt]); 
       
        //MySQL will return error if there is a user already, and that errno is 23000 (DUPLICATE ENTRY).
        if($this->db->error==23000)
        {
            return [false, "En användare med den emailadressen är redan registrerad."];    
        }
        
        $this->db->query("INSERT INTO `userprop` (userId, groupId) SELECT id,? FROM user WHERE email = ?;",[$group,$email]); 

        return [true, "Ditt konto har skapats. Meddela en datoransvarig om att aktivera kontont."];    
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

            if(filter_var($pass,FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                return [false, "Ogiltiga lösenords-tecken."];    
            }
            
            if($email=="" && $pass=="")
            {
                return [false, "Användarnamn och lösen saknas."];
            }
            
            if($email=="")
            {
                return [false, "Användarnamn saknas."];
            }
            
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return [false, "Ogiltig email."];
            } 	
                
            $UserId=NULL;
          
            foreach($this->db->query("SELECT * FROM user WHERE email=?",[$email]) as $i)
            {
                $o_pass=$i["password"];
                $o_salt=$i["salt"];
                $UserId=$i["id"];                                
            }

            if($this->hasher->CheckPassword($pass.$o_salt,$o_pass))
            {
                //Find User properties and add the to our user object
                //that later will be stored in a session, so we can use it
                //across the user logged in session.
                foreach($this->db->query("SELECT * FROM userprop WHERE userId=?",[$UserId]) as $i)
                {
                    $this->user->Approved=$i["approved"];
                    $this->user->GroupIds[]=$i["groupId"];
                    $this->user->InvokedPriviligies=$i["invokePriviligies"];
                    $this->user->UserId=$i["userId"];
                }                
               
                if($this->user->Approved=="1")
                {
                    $_SESSION["login"]="true";
                    $this->user->Email=$email;
                    $_SESSION["user"]=$this->user;
                    //Store the priviligies:
                    $this->group->GetPriviligies();
                    return [true, "Lösenordet är rätt!"];
                } else if(count($this->user->GroupIds)<1) {
                    return [false, "Ingen grupp tilldelad - kontakta admin!"];
                } else {
                    return [false, "Konto ej aktiverat!"];
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
 
class UserProperties {
    public $UserId, $GroupIds, $UserPropertiesId, $InvokedPriviligies,
            $Approved, $GroupNames;
    
    function __construct() {
        $this->GroupIds=Array();
        $this->GroupNames=Array();
    }
}

class User extends UserProperties
{
    public $Email;
    
    function __construct() {
        parent::__construct();
        
    }
    
    function BelongsToGroupByName($str)
    {
        foreach($this->GroupNames as $GroupName)
        {
            //If we find for example ADMIN in here then jump out 
            if($GroupName==$str)
            {
                return true;
            } else {
                continue;
            }
        }
    }
} 

?>  
