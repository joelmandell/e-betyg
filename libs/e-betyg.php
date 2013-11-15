<?php

/**
 * Documentation, License etc.
 *
 * License: GNU GPL 3.0
 *
 *
 * Copyright Joel Mandell 2013.
 * @package e-betyg
 */

require 'libs/pbkdf2.php';


//Group-klassen innehåller funktioner för att ge grupptillhörigheter åt inloggad användare.
class Group
{
 
	function __construct() {
		print "In BaseClass constructor\n";
	}
  
	//Lista grupper från databas.
	public function GetGroups()
	{
  
	}
  
	public function GetPriviligies()
	{
	}
  
	public function CheckUser($user)
	{
  
	}
 
}
 
 /*class Auth.
 /*
 /*Detta är en klass som innehåller funktioner för:
 * datakontroll, inloggningskontroll, omdirigering.
 * 
 * 1) Ta emot HTTP POST värden.
 * 2) Datakontroll - Är fälten rätt ifyllda? Hindra XSS. Ta bort html tags och icke tillåtna tecken.
 * Om steg 2 innehåller fel så hoppar vi förbi det 3 stycket.
 * 3) Stämmer inloggningsuppgifter?
 * 4) OM inloggning är korrekt skicka användaren till användarsidan. OM inte skicka felmeddelande.
 * OM Datakontroll i steg 2 misslyckades så får användaren också felmeddelande på inloggningssidan.
 */
 
class Auth
{
 
    public $db;
    
    function __construct($db) {
        $this->db = $db;

    }
    
    function ValidateLogin()
    {
        if(isset($_POST["user"]))
        {
            $o_pass=""; //Outputed password from db
            $o_salt=""; //Outputed salt from db
            $email=$_POST["user"];
            $pass=$_POST["pass"];

            foreach($this->db->query("SELECT * FROM user WHERE email='".$email."'") as $i)
            {
                $o_pass=$i["password"];
                $o_salt=$i["salt"];
            }

            if(validate_password($pass.$o_salt,$o_pass))
            {
                return [true, "Lösenordet är rätt!"];
            } else {
                return [false, "Lösenordet är fel!"];
            }
        } else {
            return [false, "Saknar argument"];
        }
    }
  
}
 
class Teacher extends User 
{
 
    function __construct() {

    }
  
    //Ge $value antingen värdet TRUE om konto ska aktiveras eller FALSE om det ska avaktiveras, andra argumentet är kontot som berörs.
    function Activation(bool $value, $accountId)
    {
  
    }
  
}
 
class User
{
 
    function __construct() {

    }

    //$values är en vektor med användarinmatad information
    //för att registrera sig - som skickas med en post request via ajax.
    function Register($values)
    {

    }

    function Login($values)
    {

    }
 
}

  
?>  
