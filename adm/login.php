<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
?>
<div id="wrapper">
<div id="logo">
  <h1>Login</h1>
</div>  

<div class="mainmenu">
    <a class="menuButtons" href="<?=$website?>"><?=$lang["home"]?></a>  
    <a class="menuButtons" href="<?=$website?>?top"><?=$lang["top"]?></a> 
    <a class="menuButtons" href="<?=$website?>?games"><?=$lang["games"]?></a>   
    <a class="menuButtons" href="<?=$website?>?heroes"><?=$lang["heroes"]?></a> 
    <a class="menuButtons" href="<?=$website?>?items"><?=$lang["items"]?></a> 
    <a class="menuButtons" href="<?=$website?>?bans"><?=$lang["bans"]?></a> 
    <a class="menuButtons" href="<?=$website?>?warn"><?=$lang["warn"]?></a>   
    <a class="menuButtons" href="<?=$website?>?search"><?=$lang["search"]?></a>  
    <a class="menuButtons" href="<?=$website?>?admins"><?=$lang["admins"]?></a> 
    <a class="menuButtons" href="<?=$website?>?safelist"><?=$lang["safelist"]?></a> 
</div>


<div align="center" style="margin-top: 30px;">

<?php if (isset($errors) AND !empty($errors) ) { ?>
<div style="color: red;"><?=$errors?></div>
<?php } ?>
     <form action="" method="post">
	 <table>
	 <tr>
	   <th width="100"></th>
	   <th width="300" class="alignleft">Please login to continue:</th>
	  </tr>
	  
	  <tr class="row">
	   <td width="100" class="alignleft padLeft">E-mail:</td>
	   <td width="300"> <input type="text" value="" name="login_email" /></td>
	  </tr>
	  
	  <tr class="row">
	   <td width="100" class="alignleft padLeft">Password:</td>
	   <td width="300"> <input type="password" value="" name="login_password" /></td>
	  </tr>
	  
	  <tr class="row">
	   <td width="100" class="alignleft padLeft"></td>
	   <td width="300"> <input class="menuButtons" type="submit" value="Login" name="login_" /></td>
	  </tr>
	 
	 </table>
	
	 </form>
</div>

<?php
//var_dump($_SESSION);
include('../footer.php');
?>
</div>