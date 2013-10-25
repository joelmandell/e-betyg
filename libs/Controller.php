<?php

//standard methods that get's inherited in all controllers.

class Controller {

    function __construct() {
        $this->view = new View();
    }

}