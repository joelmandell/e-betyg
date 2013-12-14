<?php

class UploadModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();

        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("jquery.base64.js");
        $this->addJsLibrary("upload.js");
        
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";

        $this->h1="<h1>Ladda upp dokument</h1>";
        $this->p="<p>Välj i menyn på sidan vilken grupp du vill skicka dokumentet till.</p>";
        $this->upload_view="<div id=\"upload_view\"></div>";
        $this->upload_options="<h1>Ladda upp till:</h1> <fieldset><legend>Grupper:</legend><select id=\"select_group\">";
        $this->upload_options.="<option value=\"0\">Välj här:</option>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";

        $this->file_input="<a href=\"#\" id=\"filepick\" />Välj fil</a><input style=\"display:none;\" type=\"file\" id=\"selfile\" />";
        $this->file_choice="<div style=\"display:none;\" id=\"file_choice_layer\">Du har valt fil: <strong id=\"file_choice\"></strong></div>";
        $this->usermessage="<label>Meddelande till ansvarig:</label><textarea name=\"usercomment\" id=\"usercomment\"></textarea><br />";
        $this->groupPublic="<label for=\"groupPublic\">Synlig för hela gruppen?</label><input type=\"checkbox\" id=\"groupPublic\" name=\"groupPublic\" value=\"public\" /> <br />";
        $this->send="<a style=\"display:none;\" href=\"#\" id=\"send\">Ladda upp fil</a>";
        $this->progress="<br \><progress id=\"prog\" style=\"display:none;\" min=\"0\" max=\"100\" value=\"0\">0% färdigt</progress>";
        $groups=$group->GetGroups();
        
        foreach($groups as $id=> $g)
        {
            $this->upload_options.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->upload_options.="</select></fieldset>";
    
    }

}