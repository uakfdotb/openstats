<?php
if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) {header('HTTP/1.1 404 Not Found'); die; }

  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $start = $time;
  
   include('config.php');
   require_once('lang/'.$default_language.'.php');
   
   require_once('inc/class.database.php');
   require_once('inc/db_connect.php');
   require_once('inc/common.php');
   require_once('inc/sys.php');
?>