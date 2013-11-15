<?php

class IndexModel extends Model {

    function __construct() {
        parent::__construct();
                
        $this->h1="<h1>Välkommen!</h1>";
        $this->p="<p>E-Betyg är en tjänst för <strong>lärare</strong> och <strong>elever</strong>.</p>
        <p>Här kan elever med sitt konto skicka upp uppdrag för
        att få dem betygsatta och även med kommentarer.
        Är du elev så kan du välja i listan vilken skola du går på,
        skicka en förfrågan om användarkonto till aktuell lärare och därefter
        vänta på bekräftelse av konto från lärare.
        </p>";
        
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";

        $this->login_form="            
            <h2>Logga in:</h2>

            <form method=\"post\" action=\"Account/SignIn/\" >
                <label>E-post:</label>
                <input type=\"text\" name=\"user\" />
                <label>Lösenord:</label>
                <input type=\"password\" name=\"pass\" />
                <input type=\"submit\" name=\"submit\" value=\"Logga in\" />
            </form>";
    }

}