<?php

class IndexModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
            
                
        if($auth->IsAuth()) 
        {
            isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
            $user=$this->user;
            $this->userstatus="Du är ".$user->Email;
            if($this->user->InvokedPriviligies) $this->userstatus.=" och är <u>grupp-admin</u>.";
        }
           
        $this->h1="<h1>Välkommen!</h1>";
        $this->p="<p>E-Betyg är en tjänst för <strong>lärare</strong> och <strong>elever</strong>.</p>
        <p>Här kan elever med sitt konto skicka upp uppdrag för
        att få dem betygsatta och även med kommentarer.
        Är du elev så kan du välja i listan vilken skola du går på,
        skicka en förfrågan om användarkonto till aktuell lärare och därefter
        vänta på bekräftelse av konto från lärare.
        </p>
        <p>Detta systemet har begränsad funktionalitet och skall
        betraktas som en beta verision. Det innebär att många funktioner
        som ses som essentiella för en sådan här platform saknas.
        Då detta är en praktisk uppgift som sköts för att visa kunskapen inom
        php så hålls detta projekt inom vissa ramar.
        </p>
        <p>De är följande:</p>
        <ul>
            <li>Skicka filer</li>
            <li>Databas-lagring</li>
            <li>Användande av OOP</li>
            <li>\"Template\"-system</li>
            <li>Gruppbaserade rättigheter</li>
            <li>HTML och CSS</li>
        </ul>
        ";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";
     
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->edit="<li>".$this->createLink("Account/Edit/","Redigera")."</li>";
        $this->doc="<li>".$this->createLink("Account/Docs/","Dokument")."</li>";

        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";

        $this->login_form="            
            <h2>Logga in:</h2>

            <form method=\"post\" action=\"Account/SignIn/\" >
                <input placeholder=\"E-post:\" autocomplete=\"off\" autocapitalize=\"\" autocorrect=\"off\" spellcheck=\"false\" type=\"text\" name=\"user\" /><br />
                <input placeholder=\"Lösenord:\" autocomplete=\"off\" autocapitalize=\"\" autocorrect=\"off\" spellcheck=\"false\" type=\"password\" name=\"pass\" /><br />
                <input type=\"submit\" name=\"submit\" value=\"Logga in\" />
            </form>";
    }

}