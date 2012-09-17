<?php
	include("../config.php");
	require_once('../lang/'.$default_language.'.php');
	include("../inc/class.database.php");
	include("../inc/db_connect.php");
	include("../inc/common.php");
	
	include("admin_sys.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta name="author" content="Ivan Antonijevic" />
	<meta name="rating" content="Safe For Kids" />
 	<meta name="description" content="<?=$HomeDesc?>" />
	<meta name="keywords" content="<?=$HomeKeywords?>" />
	<title><?=$HomeTitle?></title>
	<link rel="stylesheet" href="<?=$website?>css/style.css" type="text/css" />
	<script type="text/javascript" src="<?=$website?>scripts.js"></script>
<?php if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) { ?>
    <script type="text/javascript" src="<?php echo $website;?>adm/ckeditor/ckeditor.js"></script>
<?php } ?>
</head>
  
<body>

<?php if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) { ?>
<div id="wrapper">
<div id="logo">
  <h1>ADMINISTRATION</h1>
</div>  

<div class="mainmenu">
    <a class="menuButtons" href="<?=$website?>adm">Dashboard</a>  
    <a class="menuButtons" href="<?=$website?>adm/?games">Games</a> 
	<a class="menuButtons" href="<?=$website?>adm/?posts">Posts</a> 
	<a class="menuButtons" href="<?=$website?>adm/?comments">Comments</a> 
    <a class="menuButtons" href="<?=$website?>adm/?users">Users</a>   
    <a class="menuButtons" href="<?=$website?>adm/?bans"><?=$lang["bans"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?heroes"><?=$lang["heroes"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?items"><?=$lang["items"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?admins"><?=$lang["admins"]?></a> 
	<?php if ($SafelistPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>adm/?safelist"><?=$lang["safelist"]?></a> 
	<?php } ?>
	<a class="menuButtons" href="<?=$website?>">Go to OS&raquo; </a> 
	
	<a class="menuButtons" href="<?=$website?>adm/?logout"><?=$_SESSION["username"]?> (logout)</a> 
</div>


<?php
include('admin_pages.php');
include('../themes/'.$DefaultStyle.'/footer.php');
?>
</div>
<?php } else { 
  include("login.php");
} ?>