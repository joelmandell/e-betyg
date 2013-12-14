<?php

class EditModel extends Model {

    public $user;
    
    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();

        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("group.js");
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";

        $this->h1="<h1>Redigeringsvy</h1>";
        $this->p="<p>Välj i menyn på sidan vad som skall redigeras.</p>";
        $this->edit_view="<div id=\"edit_view\"></div>";
        
        //START BUILDING SELECT OPTIONS FOR EDITING GROUPS
        $this->edit_options="<h1>Redigera:</h1> <fieldset><legend>Grupper:</legend><select id=\"edit_groups\">";
        $this->edit_options.="<option value=\"0\">Välj här:</option>";

        $groups=$group->GetGroups();
        foreach($groups as $id=> $g)
        {
            $this->edit_options.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->edit_options.="</select>";      
        $this->create_group="<br /><br /><input type=\"submit\" id=\"create_group\" value=\"Skapa ny grupp\" />";
        $this->delete_group="<br /><input type=\"submit\" id=\"delete_group\" value=\"Radera grupp\" /></fieldset>";
        $this->edit_options.="</select>";
        //FINISHED WITH THE EDITING GROUPS SECTION

        //START BUILDING THE SELECT OPTIONS FOR ACTIVATING USERS
        $this->edit_activate_users="<fieldset><legend>Aktivera användare:</legend><select id=\"edit_activation\">";
        $this->edit_activate_users.="<option value=\"0\">Välj här:</option>";
        
        foreach($this->auth->InactiveUsers() as $id=> $g)
        {
            $this->edit_activate_users.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->edit_activate_users.="</select>";
        //FINISHED WITH THE "ACTIVATE USER" SECTION.     
    
    }

}