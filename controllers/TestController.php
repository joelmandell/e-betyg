<?php

class TestController extends Controller {

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
        echo "This is pages with test-cases";    
    }
    
    function PendingCorrections()
    {
        if($this->auth->IsAuth() && $this->user->GroupName=="ADMIN" && $this->user->InvokedPriviligies)
        {
            foreach($this->db->query("SELECT fileName, email, propId FROM `docproperty`,doc as a,`user` as u WHERE docproperty.id=a.propId AND docproperty.corrected=0 AND groupId=? AND a.userId=u.id;",["4"]) as $i){
                //$data[$i["email"]]=$i["fileName"]."|".$i["propId"];
                //echo $i["email"]."  ";
                $data[$i["propId"]]=$i["email"]."|".$i["fileName"];
            }    
            print_r($data);
        }
    }
}
