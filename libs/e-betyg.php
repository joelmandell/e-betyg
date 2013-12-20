<?php
//This is a library that we use in order to create hashed passwords!!
require 'libs/PasswordHash.php';

class Group
{    
    private $auth, $db, $user;
 
    function __construct(&$db, &$auth, &$user) {
        $this->db=&$db;
        $this->auth = &$auth;
        $this->user=&$user;
    }
    
    function GetEmailFromUserId($id)
    {
        if($this->auth->IsAuth())
        {
            foreach($this->db->query("SELECT email FROM `user` WHERE id = ?", [$id]) as $u)
            {
                $email=$u["email"];
            }
            
            return $email;
        }
    }
    
    //Delete group
    public function Delete($name)
    {
        //First check so there is no dangerous input from user.
        if(filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS) === false) {
            echo "false";
            exit;
        }   
        
        //Check if logged in and if user have priviligies.
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            $id="";

            if($name!="ADMIN")
            {  
                //Delete files that was in that group
                $this->db->query("DELETE FROM `docproperty` WHERE docId = (SELECT id FROM doc WHERE groupId=(SELECT id FROM `group` WHERE groupName=?) AND doc.id=docproperty.docId )",[$name]);
                $this->db->query("DELETE FROM `doc` WHERE groupId = (SELECT id FROM `group` WHERE groupName=?);",[$name]);

                //Delete all connected users to that group so they will not be left dead in the table.
                $this->db->query("DELETE FROM `userProp` WHERE groupId IN (SELECT id FROM `group` WHERE groupName =?);", [$name]);
                $this->db->query("DELETE FROM `group` WHERE groupName =?;",[$name]);
                
                //This is just a check to see so it really was deleted.
                foreach($this->db->query("SELECT * FROM `group` WHERE groupName = ?", [$name]) as $i)
                {
                    //If we get this far - it means something went wrong in the delete process.
                    $id=$i["id"];
                }
                
                //If we did not find the group everything got removed correctly and send true.
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
                //In order to delete the group the user that is logged in
                //needs to belong to that group.
                if($this->user->BelongsToGroupByName($name))
                {
                    //Delete Files that belonged to that group.
                    $this->db->query("DELETE FROM `docproperty` WHERE docId = (SELECT id FROM doc WHERE groupId=(SELECT id FROM `group` WHERE groupName=?) AND doc.id=docproperty.docId )",[$name]);
                    $this->db->query("DELETE FROM `doc` WHERE groupId = (SELECT id FROM `group` WHERE groupName=?);",[$name]);
                
                    $this->db->query("DELETE FROM `userProp` WHERE groupId IN (SELECT id FROM `group` WHERE groupName =?);", [$name]);
                    $this->db->query("DELETE FROM `group` WHERE groupName =?;",[$name]);
                    
                    foreach($this->db->query("SELECT * FROM `group` WHERE groupName = ?", [$name]) as $i)
                    {
                        $id=$i["id"];
                    }
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
                foreach($this->db->query("SELECT * FROM `group` WHERE 1 ORDER BY groupName ASC") as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }
            } else if($this->user->InvokedPriviligies) {
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userprop` WHERE userId = ?) ORDER BY groupName ASC", [$this->user->UserId]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }   
            } else {
                
                foreach($this->db->query("SELECT * FROM `group` WHERE id IN (SELECT groupId FROM `userprop` WHERE userId = ?) ORDER BY groupName ASC", [$this->user->UserId]) as $i)
                {
                    $group[$i["id"]]=$i["groupName"];
                }   
            }
            return $group;
        } else {
            exit;
        }
    }
    
    //Lista användare från databas.
    public function GetUsers($groupId)
    {
        $users=NULL;
        if($this->auth->IsAuth() && $this->user->InvokedPriviligies && ($this->user->BelongsToGroupById($groupId) || $this->user->BelongsToGroupByName("ADMIN")))
        {
            foreach($this->db->query("SELECT * FROM user WHERE id IN (SELECT userId
            FROM userprop WHERE groupId =? AND approved=1) ORDER BY email ASC;", [$groupId]) as $i)
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
    var $db, $auth, $user, $doc;
    function __construct(&$db,&$auth) {
        $this->db=$db;
        $this->auth=$auth;
        $this->doc = new Doc();

        if($auth->IsAuth()) isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
    }
       
    function CanChangeDocument($docId)
    {
        $data=false;
        foreach($this->user->GroupIds as $GroupID)
        {
            if($this->auth->IsAuth() && $this->user->InvokedPriviligies)
            {
                foreach($this->db->query("SELECT groupId FROM doc as a WHERE id=? AND groupId=?;",[$docId, $GroupID]) as $i){
                    $data=true;
                } 
                if($data) break;  
                
            } else if ($this->auth->IsAuth() && $this->auth->BelongsToGroupByName("ADMIN")) {
                $data=true;
                break;
                
            } else {
                $userId=$this->user->UserId;
                foreach($this->db->query("SELECT groupId FROM doc as a WHERE id=? AND a.userId=? AND groupId=?;",[$docId, $userId, $GroupID]) as $i){
                    $data=true;
                } 
                if($data) break;
            }
        }        
        return $data;
    }
    
    function BelongsToUser($docId)
    {
        $userId=$this->user->UserId;

        $data="";
        foreach($this->db->query("SELECT userId FROM `docproperty`,doc as a WHERE a.id=? AND a.id=docproperty.docId AND a.userId=?;",[$docId, $userId]) as $i){
            $data=true;
        }    
        
        return $data ? true : false ;   
    }
    
    function Correct($postData)
    {
        if($this->auth->IsAuth() && $this->CanChangeDocument($postData["docId"]) || $this->user->BelongsToGroupByName("ADMIN"))
        {
            $this->db->query("UPDATE `docproperty` "
                    . "SET corrected=?, dateCorrected=?, grade=?, comment=? WHERE docId=?;"
                    ,["1", date("Y-m-d H:i:s"),$postData["grade"],$postData["comment"],$postData["docId"]]);
            return "corrected";            
        } else {
            return "Otilåtten åtgärd";
        }
    }
    
    function Fetch($docId)
    {
        if($this->BelongsToUser($docId))
        {
            $data=null;
            foreach($this->StoreAsDocObject($docId) as $setting => $value)
            {
                $data[$setting]=$value;
            }
            return json_encode($data);
        } else {
            return "error";
        }
    }
    
    function FetchAll($groupId)
    {
        if($this->user->BelongsToGroupById($groupId))
        {
            $data=null;
            foreach($this->db->query("SELECT fileName, email, propId FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND groupId=? AND a.userId=? AND a.userId=u.id;",[$groupId, $this->user->UserId]) as $i){
                $data[$i["propId"]]=$i["email"]."|".$i["fileName"];
            }    
            return json_encode($data);
        }
    }
    
    function PendingCorrection($groupId)
    {
        if($this->user->BelongsToGroupById($groupId) || $this->user->BelongsToGroupByName("ADMIN"))
        {
            $data=null;
            foreach($this->db->query("SELECT fileName, email, propId FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND docproperty.corrected=0 AND groupId=? AND a.userId=u.id;",[$groupId]) as $i){
                $data[$i["propId"]]=$i["email"]."|".$i["fileName"];
            }    
            return json_encode($data);
        } else {
            return "FOOOO";
        }
    }

    function GetBlobAndMime($id)
    {
        if($this->doc->docId=="") echo "";
        
        if($this->auth->IsAuth())
        {
            //Need to do this to check if user actually has right to get this blob.
            foreach($this->user->GroupNames as $GroupName)
            {
                foreach($this->db->query("SELECT file, mime, fileName, groupName FROM doc as d, docproperty as dp,`group` as g WHERE d.id=? AND dp.docId=? AND g.groupName=?;",[$id, $id, $GroupName]) as $i)
                {
                    if($i["groupName"]==$GroupName)
                    {
                        return [$i["mime"],$i["fileName"], base64_encode($i["file"])];
                    } else {
                        continue;
                    }
                }
            }
        }
    }
    
    function StoreAsDocObject($docId)
    {
        $doc = $this->doc;
        
        if(!is_numeric($docId)) exit;
        
        foreach($this->db->query("SELECT docId, corrected, grade, usercomment, comment, fileName, mime, dateUploaded, dateCorrected, groupPublic, userId, groupId, email "
                . " FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND docId=? AND a.userId=u.id;",[$docId]) as $i){
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
            $email;
    
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
    
    //DEPRECATED NEED TO CHANGE TO PHPMail
    function SendMailTo($FromEmail, $ToEmail, $Subject, $Message)
    {
        $to = $ToEmail;
        $subject = $Subject;
        $mail_body = $Message;
        $FromEmail="joelmandell@127.0.0.1";
        $headers  = "From:".$FromEmail."\r\n";
        $headers .= "Content-type: text\r\n";
        mail($to, $subject, $mail_body, $headers);
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
            foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=1 ORDER BY email ASC;") as $i)
            {
                //Dont add yourself to the list - go to next item.
                if($i["email"]==$this->user->Email) continue;
                $users[$i["id"]]=$i["email"];                             
            }
        } else if($this->IsAuth() && $this->user->InvokedPriviligies && !$this->user->BelongsToGroupByName("ADMIN"))
        {
            foreach($this->user->GroupIds as $GroupId)
            {
                foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=1 AND userprop.groupId=? ORDER BY email ASC;",[$GroupId]) as $i)
                {
                    //Dont add yourself to the list - go to next item.
                    if($i["email"]==$this->user->Email) continue;
                    $users[$i["id"]]=$i["email"];                             
                }
            }
        } else {
            exit;
        }
        
        return $users;   
    }
    
    function ActivateUser($id,$group=0, $invokePriv)
    {
        if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupByName("ADMIN"))
        {
            $this->db->query("UPDATE userprop SET approved=1, groupId=?, invokePriviligies=? WHERE approved=0 AND userId=?",[$group,$invokePriv, $id]);
        } else if($this->IsAuth() && $this->user->InvokedPriviligies && $this->user->BelongsToGroupById($group)) {
            $this->db->query("UPDATE userprop SET approved=1, groupId=?, invokePriviligies=? WHERE approved=0 AND userId=?",[$group,$invokePriv,$id]);
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
            foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=0 ORDER BY email ASC;") as $i)
            {
                $users[$i["id"]]=$i["email"];                             
            }
        } else if($this->IsAuth() && $this->user->InvokedPriviligies) {
            
            foreach($this->user->GroupIds as $GroupId)
            {
                foreach($this->db->query("SELECT email, user.id FROM user INNER JOIN userprop ON user.id=userprop.userId WHERE userprop.approved=0 AND groupId=? ORDER BY email ASC;",[$GroupId]) as $i)
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

        return [true, "Ditt konto har skapats. Meddela lärare eller datoransvarige om att aktivera kontont."];    
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
    
    function BelongsToGroupById($id)
    {
        foreach($this->GroupIds as $GroupId)
        {
            //If we find for example ADMIN in here then jump out 
            if($GroupId==$id)
            {
                return true;
            } else {
                continue;
            }
        }
    }
} 

?>  
