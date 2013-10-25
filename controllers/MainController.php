<?php

class MainController extends Controller {

    function __construct() {
        parent::__construct();
        print "MainController";
    }
    
    function login($test="")
    {
        echo "login ".$test;
    }
}

?>