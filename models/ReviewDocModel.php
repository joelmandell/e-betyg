<?php

class ReviewDocModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
                
        if($auth->IsAuth()) 
        {
            isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
            $user=$this->user;
        }
           
        $doc=$_SESSION["doc"];
        
        $this->h2="<h2>Granskar filen <i>".$doc->fileName."</i></h2>";
        $this->dateuploaded="<p><strong>Uppladdad ".$doc->dateUploaded."</strong></p>";
        $this->usercomment="<p>Kommentar från eleven:</p><textarea>".$doc->usercomment."</textarea>";
        $this->set_grade="<p>Sätt betyg</p><select id=\"grade\" name=\"grade\">";
        $this->set_grade.="<option value=\"0\">Välj betyg</option>";
        $this->set_grade.="<option value=\"1\">A</option>";
        $this->set_grade.="<option value=\"2\">B</option>";
        $this->set_grade.="<option value=\"3\">C</option>";
        $this->set_grade.="<option value=\"4\">D</option>";
        $this->set_grade.="<option value=\"5\">E</option>";
        $this->set_grade.="<option value=\"6\">F</option>";
        $this->set_grade.="</select>";

    }

}
?>