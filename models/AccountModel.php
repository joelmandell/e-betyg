<?php

class AccountModel extends Model {

    function __construct(&$db, &$auth, &$group) {
        parent::__construct($db, $auth, $group);
        $this->h1="<h1>Ditt konto</h1>";
        $this->not_logged_in="Du är ej inloggad!";
    }

}