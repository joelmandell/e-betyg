<?php

class EditModel extends Model {

    public $user;
    
    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();

        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("user.js");
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
        //FINISHED WITH THE EDITING GROUPS SECTION

        //START BUILDING THE SELECT OPTIONS FOR ACTIVATING USERS
        $this->edit_activate_users="<fieldset>\n\r<legend>Aktivera användare:</legend>\n\r<select id=\"edit_activation\">\n\r";
        $this->edit_activate_users.="<option value=\"0\">Välj här:</option>\n\r";
        
        foreach($this->auth->InactiveUsers() as $id=> $g)
        {
            $this->edit_activate_users.="<option value=\"".$id."\">".$g."</option>\n\r";
        }
        $this->edit_activate_users.="</select>";
        
        $this->add_to_group="<div id=\"activate_to_group\" style=\"display:none;\">\n\r<select id=\"add_to_group\">\n\r<option value=\"0\">Aktivera i grupp:</option>\n\r";
        foreach($groups as $id=> $g)
        {
            $this->add_to_group.="<option value=\"".$id."\">".$g."</option>\n\r";
        }
        $this->add_to_group.="</select>"; 
        $this->invokedPriv="<label for=\"invokePriv\">Ge redigeringsrättigheter?</label><input type=\"checkbox\" id=\"invokePriv\" name=\"invokePriv\" value=\"public\" /> <br />";
        $this->add_to_group.=$this->invokedPriv;
        $this->add_to_group.="<input type=\"submit\" id=\"confirm_user_activation\" value=\"Verkställ\"/></div></fieldset>";
        //FINISHED WITH THE "ACTIVATE USER" SECTION.     
    
    }

}