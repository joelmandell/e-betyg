<?php
require 'config/routes.php';

$framework_path=constant("framework_path");

require $framework_path.'libs/Router.php';
require $framework_path.'libs/DatabaseConnection.php';
require $framework_path.'libs/Controller.php';
require $framework_path.'libs/Model.php';
require $framework_path.'libs/View.php';
session_start();
session_regenerate_id();

$router = new Router();



