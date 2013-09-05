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

 //Group-klassen innehåller funktioner för att ge grupptillhörigheter åt inloggad användare.
 class Group
 {
 
  function __construct() {
      print "In BaseClass constructor\n";
  }
  
  public function GetPriviligies()
  {
  }
  
  public function CheckUser($user)
  {
  
  }
 
 }
 
 class Teacher extends User 
 {
 
  function __construct() {
      print "In BaseClass constructor\n";
  }
 
 }
 
 
 class Auth
 {
 
  
 
 }
 
 class User
 {
 
  function __construct() {
      print "In BaseClass constructor\n";
  }
   
  public function Register()
  {
  }
 
 } 
  
  
  
 
 }