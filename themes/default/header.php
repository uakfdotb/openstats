<?php
if (!isset($website) ) {header('HTTP/1.1 404 Not Found'); die; }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta name="author" content="Ivan Antonijevic" />
	<meta name="rating" content="Safe For Kids" />
 	<meta name="description" content="<?=$HomeDesc?>" />
	<meta name="keywords" content="<?=$HomeKeywords?>" />
	<title><?=$HomeTitle?></title>
	<link rel="stylesheet" href="<?=$website?>themes/<?=$DefaultStyle?>/style.css" type="text/css" />
	
	<script type="text/javascript" src="<?=$website?>scripts.js"></script>
</head>