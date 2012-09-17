<?php
if (!isset($website) ) {header('HTTP/1.1 404 Not Found'); die; }

if ( !isset($s) ) $s="Search players...";
?>
<body>
<div id="wrapper">
<div id="logo">
  <h1>YOUR LOGO</h1>
  <div style="float: right; margin-top: -20px; margin-right: 48px;">
  <form action="" method="get">
   <input 
   type="text" 
   value="<?=$s?>" 
   style="height: 26px;" 
   onblur= "if (this.value == '')  {this.value = '<?=$s?>';}"
   onfocus="if (this.value == '<?=$s?>') {this.value = '';}" 
   name="search"
   />
   <input type="submit" value="<?=$lang["search"]?>" class="menuButtons" />
   </form>
  </div>
</div>  

<div class="mainmenu">
    <a class="menuButtons" href="<?=$website?>"><?=$lang["home"]?></a>  
	<?php if ($TopPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?top"><?=$lang["top"]?></a> 
	<?php } ?>
    <a class="menuButtons" href="<?=$website?>?games"><?=$lang["game_archive"]?></a>
	<?php if ($HeroesPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?heroes"><?=$lang["heroes"]?></a>
	<?php } ?>
	<?php if ($ItemsPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?items"><?=$lang["items"]?></a> 
	<?php } ?>
	<?php if ($BansPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?bans"><?=$lang["bans"]?></a> 
	<?php } ?>
	<?php if ($WarnPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?warn"><?=$lang["warn"]?></a> 
    <?php } ?>	
	<?php if ($AdminsPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?admins"><?=$lang["admins"]?></a>
    <?php } ?>		
	<?php if ($SafelistPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>?safelist"><?=$lang["safelist"]?></a> 
	<?php } ?>
	<?php if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) { ?>
	<a class="menuButtons" href="<?=$website?>adm/"><b>Admin Panel</b></a> 
	<?php } ?>
<?php if (is_logged()) { ?>
	<a class="menuButtons" href="<?=$website?>?logout"><?=$_SESSION["username"]?> (logout)</a> 
<?php } else { ?>
    <a class="menuButtons" href="<?=$website?>?login">Login/Register</a> 
<?php } ?>
</div>
  
<?php
include('inc/template.php');
?>