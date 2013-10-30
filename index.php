<?php

require 'config/routes.php';

$framework_path=constant("framework_path");

require $framework_path.'libs/Controller.php';
require $framework_path.'libs/load.php';
require $framework_path.'libs/Model.php';
require $framework_path.'libs/View.php';

$program = new load();
