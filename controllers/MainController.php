<?php

class MainController {

    function __construct() {
		print "MainController";
    }
    
    function login($test="")
    {
        echo "login ".$test;
    }
}

?>