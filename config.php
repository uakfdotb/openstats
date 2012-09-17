<?php  

if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) {header('HTTP/1.1 404 Not Found'); die; }

require("config_default.php");

if(file_exists("config_local.php")) {
	include("config_local.php");
}

?>
