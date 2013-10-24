<?php


if(isset($_GET["c"]))
{

	$controller_file="/controller/"+$_GET["c"]+".php";

	if(file_exists($controller_file))
	{
		$controller = new $controller_file;	
	}

} else {

	echo "No GET!";
}

?>