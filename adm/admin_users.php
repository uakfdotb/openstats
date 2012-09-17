<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
$errors = "";
if ( isset($_GET["search_users"]) ) $s = safeEscape($_GET["search_users"]); else $s=""; 
?>
<div align="center" class="padBottom">
	 <form action="" method="get">
	 <table>
	   <tr>
	    <td width="290">
		  <input type="hidden" name="users" />
		  <input style="width: 180px; height: 24px;" type="text" name="search_users" value="<?=$s?>" />
		  <input class="menuButtons" type="submit" value="Search users" />
		</td>
	   </tr>
	 </table>
	 </form>
</div>
<?php
if ( isset($_GET["activate"]) AND is_numeric($_GET["activate"]) ) {
   $id = safeEscape( $_GET["activate"]);
   $update = $db->query("UPDATE users SET code = '' WHERE user_id = '".(int) $id."' LIMIT 1");
} 

if ( $SafelistPage == 1) {
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $delete = $db->query("DELETE FROM users WHERE user_id ='".(int)$id."' LIMIT 1 ");
	  
	  ?>
	  <div align="center">
	  <h2>User successfully deleted. <a href="<?=$website?>adm/?users">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
//eDIT
  if ( (isset( $_GET["edit"]) AND is_numeric($_GET["edit"]) ) OR isset($_GET["add"])  ) {
   $name = ""; $email = "";
   if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) $id = safeEscape( (int) $_GET["edit"] );
   //UPDATE
    if ( isset($_POST["edit_user"]) ) {
	  $name     = safeEscape( $_POST["name"]);
	  $email   = safeEscape( $_POST["email"]);
	  $level   = safeEscape( $_POST["level"]);
	  $sql_update_pw = "";
	   
	  if ( isset( $_POST["chpw"]) AND $_POST["chpw"] == 1 AND !isset($_GET["add"]) ) {
	    $password = $_POST["password_"];
	    $password2 = $_POST["password_2"];
		
		if ( strlen($password)<=2 ) $errors.="<div>Field Password does not have enough characters</div>";
		if ($password!=$password2)  $errors.="<div>Password and confirmation password do not match</div>";
		
		if ( empty($errors) ) {
		  $hash = generate_hash(16,1);
		  $password_db = generate_password($password, $hash);
		  $sql_update_pw = ", user_password = '".$password_db."', password_hash = '".$hash."' ";
		}
		
	  }
	  
	  if ( isset($_GET["add"]) ) {
	    $password = $_POST["password_"];
	    $password2 = $_POST["password_2"];
		
		if ( strlen($password)<=2 ) $errors.="<div>Field Password does not have enough characters</div>";
		if ($password!=$password2)  $errors.="<div>Password and confirmation password do not match</div>";
		$hash = generate_hash(16,1);
		$password_db = generate_password($password, $hash);
		
	  }
	  
	  if ( strlen( $name)<=2 ) $errors.="<div>Field Name does not have enough characters</div>";
	  if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $email)) 
	  $errors.="<div>E-mail address is not valid</div>";
	  
	  $time = date( "Y-m-d H:i:s", time() );
	  
	  if ( isset($_GET["edit"]) ) $sql = "UPDATE users SET 
	  user_name= '".$name."', user_email = '".$email."', user_level = '".$level."' $sql_update_pw 
	  WHERE user_id ='".$id."' LIMIT 1 ";
	  
	  if ( isset($_GET["add"]) ) $sql = "INSERT INTO users(user_name, user_email, user_password, password_hash) VALUES('".$name."', '".$email."', '".$password_db."', '".$hash."')";
	  
	  $check1 = $db->query("SELECT * FROM users WHERE LOWER(user_name) = LOWER('".$name."')  ");
	  if ( $db->num_rows($check1) >=1 )  $errors.="<div>Username already taken</div>";
	  
	  $check2 = $db->query("SELECT * FROM users WHERE LOWER(user_email) = LOWER('".$email."')  ");
	  if ( $db->num_rows($check2) >=1 )  $errors.="<div>E-mail already taken</div>";
	  
	  if ( empty($errors) ) {
	  $result = $db->query($sql);
	  
	  if ( $result ) {
	  	  ?>
	  <div align="center">
	    <h2>User successfully updated. <a href="<?=$website?>adm/?users">&laquo; Back</a></h2>
	  </div>
	  <?php 
	  }
	 } else {
	?>
	<div align="center"><?=$errors?></div>
	<?php
	}
	}
  
     if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) {
	 $result = $db->query("SELECT * FROM users WHERE user_id = '".$id."' ");
	 $row = $db->fetch_array($result,'assoc');
	 $name       = ( $row["user_name"]);
	 $email     = ( $row["user_email"]);
	 $level     = ( $row["user_level"]);
	 $button = "Edit User";
	 } else { $button = "Add User"; $level = ""; }
	 ?>
	 
	 <form action="" method="post">
	 <div align="center">
	 <h2><?=$button?></h2>
	 <table>
	   <tr class="row">
	     <td width="80" class="padLeft">Name:</td>
		 <td><input name="name" style="width: 380px; height: 28px;" type="text" value="<?=$name ?>" /></td>
	   </tr>
	   <tr class="row">
	     <td width="80"  class="padLeft">E-mail:</td>
		 <td><input name="email" style="width: 380px; height: 28px;" type="text" value="<?=$email?>" /></td>
	   </tr>
	   <?php if ( !isset($_GET["add"]) ) { ?>
	   <tr class="row">
	     <td width="80"  class="padLeft">Password:</td>
		 <td>
		 <input type="checkbox" name="chpw" value="1" onclick="showhide('cw')" /> Change password?
		 <div id="cw" style="display: none;">
		   <div><input type="password" value="" name="password_" /></div>
		   <div>Confirm password:</div>
		   <div><input type="password" value="" name="password_2" /></div>
		 </div>
		 </td>
	   </tr>
	   <?php } else { ?>
	   <tr class="row">
	     <td width="80"  class="padLeft">Password:</td>
		 <td>
		   <div><input type="password" value="" name="password_" /></div>
		   <div>Confirm password:</div>
		   <div><input type="password" value="" name="password_2" /></div>
		 </td>
	   </tr>
	   <?php } ?>
	   <tr class="row">
	     <td width="80"  class="padLeft">Role:</td>
		 <td>
		 <div class="padTop"></div>
		 <select name="level">
		 <?php if ($level<=1) $sel='selected="selected"'; else $sel = ""; ?>
		   <option <?=$sel?> value="0">Member</option>
		 <?php if ($level==9) $sel='selected="selected"'; else $sel = ""; ?>
		   <option <?=$sel?> value="9">Admin</option>
		 </select>
		 <div class="padBottom"></div>
		 </td>
	   </tr>
	   <tr>
	     <td width="80"></td>
		 <td class="padTop padBottom">
		 <input type="submit" value="Submit" name="edit_user" class="menuButtons" />
		 <a class="menuButtons" href="<?=$website?>adm/?admins">&laquo; Back</a>
		 </td>
	   </tr>
	  </table>
	  </div>
	 </form>
	 <?php
  }
  
  if ( isset($_GET["search_users"]) AND strlen($_GET["search_users"])>=2 ) {
     $search_users = safeEscape( $_GET["search_users"]);
	 $sql = " AND LOWER(user_name) LIKE LOWER('%".$search_users."%') ";
  } else {
   $sql = "";
   $search_users= "";
  }

  $result = $db->query("SELECT COUNT(*) FROM users WHERE user_id>=1 $sql ");

  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 30;
?>
<div align="center">
<div class="padBottom padTop"><a class="menuButtons" href="<?=$website?>adm/?users&amp;add">[+] Add User</a></div>
<?php
  
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
   $result = $db->query("SELECT * FROM users WHERE user_id>=1 $sql 
   ORDER BY user_id DESC LIMIT $offset, $rowsperpage");
   ?>
   <table>
    <tr>
	  <th width="160" class="alignleft padLeft">Username</th>
	  <th width="64" class="alignleft">Action</th>
	  <th width="64" class="alignleft">Role</th>
	  <th width="80" class="alignleft">Confirmed</th>
	  <th width="150" class="alignleft">Email</th>
	  <th width="120" class="alignleft">Joined</th>
	</tr>
   <?php
   while ($row = $db->fetch_array($result,'assoc')) { ?>
   <tr class="row">
     <td width="160" class="alignleft padLeft"><a href="<?=$website?>adm/?users&amp;edit=<?=$row["user_id"]?>"><?=$row["user_name"]?></a></td>
	 <td width="64" class="alignleft">
	 <a href="<?=$website?>adm/?users&amp;edit=<?=$row["user_id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete User?') ) { location.href='<?=$website?>adm/?users&amp;del=<?=$row["user_id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	 <td width="64" class="alignleft">
	 <?php if ($row["user_level"]==9) { ?>
	 Admin
	 <?php } else { ?>
	 Member
	 <?php } ?>
	 </td>
	 <td width="80" class="alignleft">
	   <?php if (!empty($row["code"]) ) { ?><a href="<?=$website?>adm/?users&amp;activate=<?=$row["user_id"]?>">Activate</a><?php } else { ?>Y<?php } ?>
	 </td>
	 <td width="150" class="alignleft overflow_hidden"><span title="<?=$row["user_email"]?>"><?=stripslashes($row["user_email"])?></span></td>
	 <td width="120" class="alignleft overflow_hidden"><?=date( $DateFormat, ($row["user_joined"]) )?></td>
    </tr>
   <?php 
   }
   $db->free($result);
?>
  </table>
<?php
include('pagination.php');
?>
  </div>
<?php } else { ?>
<div align="center">
  <h2>SafeList disabled</h2>
  <div>Please enable SafeList</div>
</div>
<div style="margin-top: 480px;">&nbsp;</div>
<?php } ?>
  
  <div style="margin-top: 180px;">&nbsp;</div>