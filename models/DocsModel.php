<?php


class DocsModel extends Model {

    public $user;
    
    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        //TODO: Fix so windows.save will be used on other browsers
        //if possible use it in mozilla aswell!!!!!!!
        
        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("FileSaver.js");
        if($auth->IsAuth()) 
        {
            isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
            $user=$this->user;
            $this->userstatus="Du är ".$user->Email;
            if($this->user->InvokedPriviligies) $this->userstatus.=" och är <u>grupp-admin</u>.";
        }
        $this->fileloader="<div id=\"fileloader\" style=\"display:none;\"><strong>Laddar </strong> <img src=\"".constant("webapp_path")."/resources/img/ajax-loader.gif\" /></div>";
  
        $this->h1="<h1>Dokumentöversikt</h1>";
        $this->not_logged_in="Du är ej inloggad!";
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";
        $this->doc="<li>".$this->createLink("Account/Docs/","Dokument")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->doc_view="<div id=\"doc_view\"></div>";

        //START BUILDING SELECT OPTIONS FOR EDITING GROUPS
        if($auth->IsAuth() && $user->InvokedPriviligies)
        {
            $this->addJsLibrary("docs.js");

            $this->edit_pending_options="<h1>Redigera:</h1> <fieldset><legend>Dokument som väntar på rättning:</legend><select id=\"edit_pending_options\">";
            $this->edit_pending_options.="<option value=\"0\">Välj grupp:</option>";

            $groups=$group->GetGroups();
            if(count($groups)>0)
            {
                foreach($groups as $id=> $g)
                {
                    $this->edit_pending_options.="<option value=\"".$id."\">".$g."</option>";
                }
            }
            $this->edit_pending_options.="</select></fieldset>";      
        } else if($auth->IsAuth() && !$user->InvokedPriviligies)
        {
            $this->addJsLibrary("docs.js");

            $this->downloaded_files_options="<h1>Redigera:</h1> <fieldset><legend>Dina uppladdade dokument:</legend><select id=\"downloaded_files_options\">";
            $this->downloaded_files_options.="<option value=\"0\">Välj grupp:</option>";

            $groups=$group->GetGroups();
            if(count($groups)>0)
            {
                foreach($groups as $id=> $g)
                {
                    $this->downloaded_files_options.="<option value=\"".$id."\">".$g."</option>";
                }
            }
            $this->downloaded_files_options.="</select></fieldset>"; 
                        
        }
        //FINISHED WITH THE EDITING GROUPS SECTION
    }

}

