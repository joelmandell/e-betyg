<?php

class AccountModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        $this->h1="<h1>Ditt konto</h1>";
        $this->not_logged_in="Du är ej inloggad!";
        $this->register="<li>".$this->createLink("Account/Register/", "Registrera")."</li>";
        $this->account="<li>".$this->createLink("Account/","Mitt konto")."</li>";
        $this->upload="<li>".$this->createLink("Account/Upload/","Ladda upp dokument")."</li>";
        $this->logout="<li>".$this->createLink("Account/SignOut/", "Logga ut")."</li>";

    }

}