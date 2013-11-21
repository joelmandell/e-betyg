<?php

class EditModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();

        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("group.js");
        
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->h1="<h1>Redigeringsvy</h1>";
        $this->p="<p>Välj i menyn på sidan vad som skall redigeras.</p>";
        $this->edit_view="<div id=\"edit_view\"></div>";
        $this->edit_options="<h1>Redigera:</h1> <label>Grupper:</label><select id=\"edit_groups\">";
        $this->edit_options.="<option value=\"0\">Välj här:</option>";

        $groups=$group->GetGroups();
        
        foreach($groups as $id=> $g)
        {
            $this->edit_options.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->edit_options.="</select>";
        $this->create_group="<br /><br /><input type=\"submit\" id=\"create_group\" value=\"Skapa ny grupp\" />";
        $this->delete_group="<br /><br /><input type=\"submit\" id=\"delete_group\" value=\"Radera grupp\" />";
    
    }

}