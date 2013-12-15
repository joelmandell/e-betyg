<?php

class RegisterModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        
        if($auth->IsAuth()) 
        {
            isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
            $user=$this->user;
            $this->user="Du är ".$user->Email;
        }
           
        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("group.js");
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";
        $this->doc="<li>".$this->createLink("Account/Docs/","Dokument")."</li>";
        $this->h1="<h1>Registrera dig</h1>";
        $this->p="<p>Det går snabbt att registrera dig, men en administratör "
                . "måste godkänna ditt konto - varav du senare får ett e-mail "
                . "om att ditt konto registrerats.</p>";
        
        $this->selected_group="<br /><label>Vilken grupp söker du medlemsskap i:</label><select name=\"selected_group\">
            
            <option value=\"0\">Välj grupp</option>
        ";
        $groups=$group->GetGroupsSafeMode();
        foreach($groups as $id => $g)
        {
        $this->selected_group.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->selected_group.="</select><br /><br />";
        
        $this->register_form="<form method=\"post\" action=\"\">"
                . "<label>E-mail</label>"
                . "<input type=\"text\" name=\"user\" />"
                . "<label>Lösenord:</label>"
                . "<input type=\"password\" name=\"pass\" /><br />"
                . $this->selected_group
                . "<input type=\"submit\" value=\"Registrera\" />"
                . "</form>";
    
    }

}