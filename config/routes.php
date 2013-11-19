<?php

/*
 * This file will contain route to wich controller to load as standard,
 * and some other possible settings.
 * 
 */

//index_controller contains the standard controller that should load
//when root of page is loaded.
define('index_controller','Main');

//Framework path - where the library files are.
define('framework_path','./');
define('webapp_path','/e-betyg');

//Some config-settings for database.
define('db_pass','');
define('db_user','root');
define('db_host','127.0.0.1');
define('db_port','3306');
define('db_charset','utf8');