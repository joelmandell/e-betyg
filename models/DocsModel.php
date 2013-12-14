<?php


class DocsModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        $this->h1="<h1>Dokumentöversikt</h1>";
        $this->not_logged_in="Du är ej inloggad!";
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";
        $this->doc="<li>".$this->createLink("Account/Docs/","Dokument")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->doc_view="<div id=\"doc_view\"></div>";
        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("docs.js");

        //START BUILDING SELECT OPTIONS FOR EDITING GROUPS
        $this->edit_pending_options="<h1>Redigera:</h1> <fieldset><legend>Dokument som väntar på rättning:</legend><select id=\"edit_pending_options\">";
        $this->edit_pending_options.="<option value=\"0\">Välj grupp:</option>";

        $groups=$group->GetGroups();
        foreach($groups as $id=> $g)
        {
            $this->edit_pending_options.="<option value=\"".$id."\">".$g."</option>";
        }
        $this->edit_pending_options.="</select></fieldset>";      

        //FINISHED WITH THE EDITING GROUPS SECTION
    }

}

