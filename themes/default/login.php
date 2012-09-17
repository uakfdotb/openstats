<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
?>

<div align="center">
  
  <h2><?=$lang["login"]?></h2>
  
  <?php if (isset($errors) AND !empty($errors) ) { ?>
  <div><?=$errors?></div>
  <?php } ?>
  
  <form action="" method="post">
  <table>
   <tr><th width="100"></th><th class="alignleft" width="270"><?=$lang["login"]?></th></tr>
  <tr class="row">
   <td width="100" class="padLeft"><?=$lang["email"]?>: </td>
   <td width="250" class="padLeft"><input type="text" value="" name="login_email" /></td>
   </tr>
   <tr class="row">
   <td width="100" class="padLeft"><?=$lang["password"]?>: </td>
   <td width="250" class="padLeft"><input type="password" value="" name="login_pw" /></td>
  </tr>
   <tr class="row">
   <td width="100" class="padLeft"></td>
   <td width="250" class="padLeft">
     <div class="padTop"></div>
     <input type="submit" value="<?=$lang["login"]?>" class="menuButtons" name="login_" />
     <div class="padBottom"></div>
   </td>
  </tr>
  </table>
  </form>
  <div class="padTop"></div>
  <div class="padTop"></div>
  <div class="padTop"></div>
  
  <h2><?=$lang["register"]?></h2>
  
  <?php if (isset($registration_errors) AND !empty($registration_errors) ) { ?>
  <div><?=$registration_errors?></div>
  <?php } ?>
  
  <form action="" method="post">
  <table>
  <tr><th width="140"></th><th class="alignleft" width="270"><?=$lang["register"]?></th></tr>
  <tr class="row">
   <td width="140" class="padLeft"><?=$lang["username"]?>: </td>
   <td width="250" class="padLeft"><input type="text" value="" name="reg_un" /></td>
   </tr>
  <tr class="row">
   <td width="140" class="padLeft"><?=$lang["email"]?>: </td>
   <td width="250" class="padLeft"><input type="text" value="" name="reg_email" /></td>
   </tr>
   <tr class="row">
   <td width="140" class="padLeft"><?=$lang["password"]?>: </td>
   <td width="250" class="padLeft"><input type="password" value="" name="reg_pw" /></td>
  </tr>
   <tr class="row">
   <td width="140" class="padLeft"><?=$lang["confirm_password"]?>: </td>
   <td width="250" class="padLeft"><input type="password" value="" name="reg_pw2" /></td>
  </tr>
   <tr class="row">
   <td width="100" class="padLeft"></td>
   <td width="250" class="padLeft">
   <div class="padTop"></div>
     <input type="submit" value="<?=$lang["register"]?>" class="menuButtons" name="register_" />
   <div class="padBottom"></div>
   </td>
  </tr>
  </table>
  </form>
  
</div>