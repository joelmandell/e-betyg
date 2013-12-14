<?php

class AccountController extends Controller {

    public $_M, $db, $r, $auth, $user, $group, $upload, $doc;

    function __construct($bundle) {
        parent::__construct();
        $this->r=$bundle; 
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user = new User();
        $this->group=new Group($this->db, $this->auth, $this->user);
        $this->doc=new Doc($this->db);
        $this->upload=new Upload($this->db, $this->auth, $this->group, $this->doc);
    }

    function index()
    {
        $this->view->setModel("AccountModel");
        $this->view->render("account/index");          
    }
    
    function Delete($what=NULL)
    {
        $group=$this->group;
        if($this->auth->IsAuth())
        {
            //If we have possibility to do things that requires certain privs
            //and if we belong to the admin group we should be allowed to do
            //EVERYTHING!
            if($this->user->InvokedPriviligies)
            {
                if($what=="Group")
                {
                    if(isset($_POST["groupName"])){                   
                        $group->Delete($_POST["groupName"]);              
                    }   
                } else if($what=="Document") {
                    //TODO:AJAX
                } else if($what=="User") {
                    //TODO:AJAX
                } else {
 
                }
            }
        }
    }
    
    function Create($what=NULL)
    {
        $group=$this->group;
        if($this->auth->IsAuth())
        {
            //If we have possibility to do things that requires certain privs
            //and if we belong to the admin group we should be allowed to do
            //EVERYTHING!
            if($this->user->InvokedPriviligies && $this->user->GroupName=="ADMIN")
            {
                if($what=="Group")
                {
                    if(isset($_POST["groupName"])){               
                        $group->Create($_POST["groupName"]);  
                    }   
                } else if($what=="Document") {
                    //TODO:AJAX
                } else if($what=="User") {
                    //TODO:AJAX
                } else {
 
                }
            }
        }
    }
    
    function Edit($what=NULL)
    {
        $group=$this->group;
        if($this->auth->IsAuth())
        {
            //If we have possibility to do things that requires certain privs
            //and if we belong to the admin group we should be allowed to do
            //EVERYTHING!
            if($this->user->InvokedPriviligies)
            {
                $users=NULL;
                if($what=="Groups")
                {
                    if(isset($_POST["id"])){
                        
                        if($group->GetUsers($_POST["id"]))
                        {
                            foreach($group->GetUsers($_POST["id"]) as $gUser)
                            {
                                $users.=$gUser."&nbsp;<a href=\"#remove\" id=\"remove_user\">Radera</a>&nbsp;&nbsp;"
                                        . "<a href=\"#inactivate\" id=\"inactivate_user\">Inaktivera</a><br />";
                            }
                            $this->_M->users=$users;

                        } else {
                            $this->_M->users="Inga användare i gruppen ännu";
                        }
                    }
                    
                    $this->view->render("ajax/groups");                     
                } else if($what=="Documents") {
                    //TODO:AJAX
                } else if($what=="Users") {
                    //TODO:AJAX
                } else {
                    $this->view->setModel("EditModel");
                    $this->view->render("account/edit");  
                }
            }
        }
    }
    
    function Upload()
    {
        if($_FILES)
        {
            if($this->auth->IsAuth())
            {
                $this->doc->groupId=$_POST["group"];
                $this->doc->mime=$_POST["mime"];
                $this->doc->userId=$this->user->UserId;
                $this->doc->dateUploaded=date("Y-m-d H:i:s");
                $this->doc->fileName=$_POST["filename"];
                $this->doc->groupPublic=$_POST["groupPublic"];
                $this->doc->usercomment=$_POST["usercomment"];
                $this->upload->updateDoc($this->doc);
                $this->upload->store($_FILES["file"]["tmp_name"]);

            }
        } else {
            $this->view->setModel("UploadModel");
            $this->view->render("account/upload");  
        }
    }
   
    function Register()
    {
        if($_POST)
        {
            $action=$this->auth->Register();
            if($action[0])
            {
                $this->r->passModelData(["register_success",$action[1]]);
                $this->r->doRedirect("/Account/");
            } else {
                $this->r->passModelData(["register_fail",$action[1]]);
                $this->r->doRedirect("/Account/Register/");
            }
        } else {
            $this->view->setModel("RegisterModel");
            $this->view->render("account/register");   
        }
    }
    
    function SignOut()
    {
        $this->auth->Logout();
        $this->r->doRedirect();
    }

    
    function SignIn()
    {       
        $validation = $this->auth->ValidateLogin();
        
        if($validation[0])
        {
            //User is validated.
        } else {
            //Send message from validation function to the router
            //then the router will do our redirect.
            $this->r->passModelData(["msg",$validation[1]]);
        }
        
        $this->r->doRedirect();  
    }    
}
