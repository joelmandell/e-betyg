<?php

class RegisterModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();

        $this->addJsLibrary("jquery-2.0.3.min.js");
        $this->addJsLibrary("group.js");
        
        $this->h1="<h1>Registrera dig</h1>";
        $this->p="<p>Det går snabbt att registrera dig, men en administratör "
                . "måste godkänna ditt konto - varav du senare får ett e-mail "
                . "om att ditt konto registrerats.</p>";
        
        $this->register_form="<form method=\"post\" action=\"\">"
                . "<label>E-mail</label>"
                . "<input type=\"text\" name=\"user\" />"
                . "<label>Lösenord:</label>"
                . "<input type=\"password\" name=\"pass\" /><br />"
                . "<input type=\"submit\" value=\"Registrera\" />"
                . "</form>";
    
    }

}