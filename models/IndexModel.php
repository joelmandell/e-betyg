<?php

class IndexModel extends Model {

    function __construct() {
        parent::__construct();
                
        $this->h1="Välkommen!";
        $this->p="<p>E-Betyg är en tjänst för <strong>lärare</strong> och <strong>elever</strong>.</p>
        <p>Här kan elever med sitt konto skicka upp uppdrag för
        att få dem betygsatta och även med kommentarer.
        Är du elev så kan du välja i listan vilken skola du går på,
        skicka en förfrågan om användarkonto till aktuell lärare och därefter
        vänta på bekräftelse av konto från lärare.
        </p>";
    }

}