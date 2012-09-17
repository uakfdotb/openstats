<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>	
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="DotA OpenStats, php/MySQL webstatistic" />
  <meta name="keywords" content="dota, games, openstats" />
  <link rel="stylesheet" href="css/style.css" />
  <title>OpenStats 3.0 Installation</title>

 <style>
 .container { margin-top: 32px; }
 .topbar { background-color: #ccc; padding: 10px;}
 .warning { color: #FF0000; margin-bottom: 10px; background-color: #611919;}
 h4 { color: #f1f1f1; }
 </style>
  
</head>
  <body>
 <div class="topbar">

        <div class="container">
          <h1 class="brand" href="<?php echo $website; ?>">OpenStats 3.0</h1>
        </div>
  </div>
  
<div align="center" style="width: 960px; height: 690px;" >
 <div class="container">
    <div class="content">
    <h2>Install OpenStats 3.0</h2>
	
	<?php
	//ч
	if (!isset($_POST["step"]) ) $step = 1; else
	if (isset($_POST["step"]) AND is_numeric($_POST["step"]) ) $step = (int)$_POST["step"]; else die ("Error");
	
	if ($step == 1) {
	if (isset($_POST["server"]) )   $server = $_POST["server"];
	if (isset($_POST["username"]) ) $username = $_POST["username"];
	if (isset($_POST["password"]) ) $password = $_POST["password"];
	if (isset($_POST["db"]) )       $database = $_POST["db"];
	
	if (isset($_POST["website"]) )  $website = $_POST["website"]; 
	else $website = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if (isset($_POST["sitename"]) ) $SiteName = $_POST["sitename"];
	?>
	<div class="warning">This will install a fresh version of OpenStats. This means that the entire database <b>will be deleted</b> and re-created.
	</div>
	<form action="" method="post"> 
	<table class="zebra-striped">
	  <tr>
	    <td width="100">Website url:</td>
		<td><input style="width: 250px; height: 25px;" type="text" value="<?php echo $website ;?>" name="website" /></td>
	  </tr>
	  
	  <tr>
	    <td width="100">Site name:</td>
		<td><input style="width: 250px; height: 25px;" type="text" value="<?php echo $HomeTitle; ?>" name="sitename" /></td>
	  </tr>
	</table>
	<h2>Database</h2>
	<table class="zebra-striped">
	  <tr>
	    <td width="100">Server:</td>
		<td><input style="width: 250px; height: 25px;" type="text" value="<?php echo $server; ?>" name="server" /></td>
	  </tr>
	  
	  <tr>
	    <td width="100">DB username:</td>
		<td><input style="width: 250px; height: 25px;" type="text" value="<?php echo $username; ?>" name="username" /></td>
	  </tr>
	  <tr>
	    <td width="100">DB password:</td>
		<td><input style="width: 250px; height: 25px;" type="password" value="<?php echo $password; ?>" name="password" /></td>
	  </tr>
	  <tr>
	    <td width="100">Database:</td>
		<td><input style="width: 250px; height: 25px;" type="text" value="<?php echo $database; ?>" name="db" /></td>
	  </tr>
	</table>
	<input style="margin-top: 10px;" type="submit" class="menuButtons" value="Test connection" name="db_test" />
	</form>
	<div>&nbsp;</div>
<?php 
  if ($_SERVER["REQUEST_METHOD"]=="POST" AND isset($_POST["db_test"]) AND $step==1) {
     $con = @mysql_connect($_POST["server"],$_POST["username"],$_POST["password"]);
	 if ($con) { ?>
	 <div style="color: green; margin-top: 16px;">Successfully connected to database</div>
	 <?php if (mysql_select_db($_POST["db"], $con)) { ?>
	 <div>&nbsp;</div>
	 <form action="" method="post">
	 <input type="hidden" name="step" value="2" />
	 <input type="hidden" name="www" value="<?php echo $website; ?>" />
	 <input type="hidden" name="sn" value="<?php echo $SiteName; ?>" />
	 <input type="hidden" name="srv" value="<?php echo $server; ?>" />
	 <input type="hidden" name="un" value="<?php echo $username; ?>" />
	 <input type="hidden" name="pw" value="<?php echo $password; ?>" />
	 <input type="hidden" name="db" value="<?php echo $database; ?>" />
	 <input class="menuButtons" type="submit" name="success" value="Continue" />
	 </form>
	 <?php } else { ?><div style="color: red">Could not select database</div><?php } ?>
	 <?php } else {
	 ?><div style="color: red">Could not connect <?=mysql_error()?></div><?php
	 }
  }
}
	
	if ($step == 2 AND isset($_POST["success"]) ) {
	   $www = trim( ($_POST["www"]));
	   $sn = trim($_POST["sn"]);
	   $srv = trim($_POST["srv"]);
	   $un = trim($_POST["un"]);
	   $pw = trim($_POST["pw"]);
	   $db = trim($_POST["db"]);
	   
	   if (substr($www,-1) !="/") $www.="/"; 
	   write_value_of('$website', "$website",             ($www),"config.php");
	   write_value_of('$server',  "$server",              ($srv),"config.php");
	   write_value_of('$username', "$username",           ($un),"config.php");
	   write_value_of('$password',"$password",            ($pw),"config.php");
	   write_value_of('$database',"$database",            ($db),"config.php");
	   write_value_of('$HomeTitle',"$HomeTitle",            ($sn),"config.php");
	   
	   ?>
	   <div>Data successfully updated</div>
	    <div>&nbsp;</div>
	   <form action="" method="post">
	   <input class="menuButtons"  type="submit" name="data" value="Continue" />
	   <input type="hidden" name="step" value="3" />
	   </form>
	   <?php
	}
	
	if ($step == 3 AND isset($_POST["data"]) ) {
	?>
	<div>Creating tables...</div>
	<?php
	   if (file_exists("install/sql_data.sql") ) {
	   $con = mysql_connect($server,$username,$password);
	   mysql_select_db($database, $con);
	   $result = file_get_contents("install/sql_data.sql");
	   $items = explode(";", $result);
	   foreach ($items as $data) {
	   if (!empty($data) )
	   $result = mysql_query($data) or die( mysql_error() ) ;
	   }
	   
	   $insert = mysql_query("INSERT INTO `news` (`news_id`, `news_title`, `news_content`, `news_date`, `news_updated`, `views`, `status`) 
	   VALUES (1, 'Hello world!', '<p>Welcome to OpenStats 2.0. This is your first post. Edit or delete it.</p>', '".(int)time()."', 0, 1, 1);") or die( mysql_error() );
	   
	   $insert_comment = mysql_query("INSERT INTO `comments` (`id`, `user_id`, `page`, `post_id`, `text`, `date`, `user_ip`) 
	   VALUES (1, 1, 'news', 1, 'Hi, this is a comment.\r\nTo delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.', '".(int)time()."', '".$_SERVER["REMOTE_ADDR"]."');") or die( mysql_error() );
	   ?>
	   <div>Tables successfuly created</div>
	   <div>Creating tables for heroes and items...</div>
	   <?php
	   if (!file_exists("install/sql_heroes_items.sql")) die("ERROR: missing file: install/sql_heroes_items.sql");
	   $result = file_get_contents("install/sql_heroes_items.sql");
	   $items = explode(";--", $result);
	   foreach ($items as $data) {
	   if (!empty($data) )
	   $result = mysql_query($data) or die( mysql_error() ) ;
	   }
	   ?><div>All tables successfully created.</div>
	   
	   <hr />
	   <form action="" method="post">
	   <input type="hidden" value="4" name="step" />
	   <div>Admin Username:</div>
       <input style="width: 250px; height: 25px;" type="text" size="60" value="" name="admin" />
       <div>Admin Password:</div>
       <input style="width: 250px; height: 25px;" type="password" size="60" value="" name="password" />	
	   <div>Admin Email:</div>
       <input style="width: 250px; height: 25px;" type="text" size="60" value="" name="email" />	
	   <div>&nbsp;</div>
       <div><input class="menuButtons" type="submit" name="adm" value="Finish Installation" /></div>	
       <div>&nbsp;</div>	   
	   </form>
	   <?php
	   } else {
	   echo "ERROR: Missing file: install/sql_data.sql";
	   }
	}
	 if ($step==4 AND isset($_POST["adm"]) ) {
		$con = mysql_connect($server,$username,$password);
	    mysql_select_db($database, $con);
	    $admin = trim($_POST["admin"]);
		$pw = trim($_POST["password"]);
		$email = trim($_POST["email"]);
		if (strlen($admin)<=2 OR strlen($pw)<=2) {
		$admin = "admin"; $pw="admin"; $email = "admin@openstats"; 
		?>
		<div>Admin username or password have too few characters</div>
		<div>Inserting default admin username and password</div>
		<div><b>Admin username:</b> admin</div>
		<div><b>Admin password:</b> admin</div>
		<div>&nbsp;</div>
		<div>Don't forget to change admin username and password via admin panel</div>
		<?php
		}
		$hash = generate_hash(16,1);
		$pass = generate_password($pw, $hash);

		$result = mysql_query("INSERT INTO users(user_name, user_password, password_hash, user_email, user_joined, user_level,user_ip, confirm, can_comment) VALUES('$admin', '$pass', '$hash', '$email', '".time()."', '9', '".$_SERVER["REMOTE_ADDR"]."', '', '1')") or die( mysql_error() );
		if ($result) {
		?>
		<div>&nbsp;</div>
		<div><b>Admin successfully created.</b></div>
		<div style="display:none;">Please delete <b>install.php</b>, <b>sql_data.sql</b> and <b>sql_heroes_items.sql</b> from install  directory.</div>
		
		<div style="display:none;">Please delete or rename <b>install/</b> folder.</div>
		
		<div>&nbsp;</div>
		<input type="button" class="menuButtons" value="Go to OpenStats 3.0" onclick="location.href='<?=$website?>'" />
		<?php
		write_value_of('$OS_INSTALLED', "$OS_INSTALLED", '1', "config.php");
		} else echo "ERROR: mysql error!";
		} 
	
 	 if ($step<=3) { ?>
    <div>Step: <b><?php echo $step ; ?></b> / 3 </div>
	<?php } ?>
    </div>
 </div>
</div>
  
  <footer>
        <div id="footer-wrapper">@<?php echo date("Y"); ?> Powered by DotA OpenStats 3.0</div>
  </footer>
</body>
</html>