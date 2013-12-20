<?php

class ReviewDocModel extends Model {
    
    public $user;
    
    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
                
        if($auth->IsAuth()) 
        {
            isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
            $user=$this->user;
        }
           
        $doc=$_SESSION["doc"];
        
        $this->download="<a style=\"display:none;\" id=\"download_blob\" href=\"#\">Öppna bifogad fil</a>";
        $this->h2="<h2>Granskar filen <i id=\"title\">".$doc->fileName."</i></h2>";
        $this->dateuploaded="<p><strong>Uppladdad av <u>".$doc->email."</u> ".$doc->dateUploaded."</strong></p>";
        $this->usercomment="<p>Kommentar från eleven:</p><blockquote>".$doc->usercomment."</blockquote>";
        $this->set_grade="<p>Sätt betyg</p><select id=\"grade\" name=\"grade\">";
        $this->set_grade.="<option value=\"0\">Välj betyg</option>";
        $this->set_grade.="<option value=\"1\">A</option>";
        $this->set_grade.="<option value=\"2\">B</option>";
        $this->set_grade.="<option value=\"3\">C</option>";
        $this->set_grade.="<option value=\"4\">D</option>";
        $this->set_grade.="<option value=\"5\">E</option>";
        $this->set_grade.="<option value=\"6\">F</option>";
        $this->set_grade.="</select>";
        $this->comment="<p>Ge kommentar till elev:</p><textarea id=\"comment\" name=\"comment\"></textarea>";
        $this->review="<br /><input type=\"submit\" class=\"correct".$doc->docId."\"  id=\"correct".$doc->docId."\" value=\"Ange dokument som rättad!\" />";

    }

}
?>