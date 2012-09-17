<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
$errors = "";

if ( $SafelistPage == 1) {
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $delete = $db->query("DELETE FROM safelist WHERE id ='".(int)$id."' LIMIT 1 ");
	  
	  ?>
	  <div align="center">
	  <h2>User successfully deleted. <a href="<?=$website?>adm/?safelist">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
//eDIT
  if ( (isset( $_GET["edit"]) AND is_numeric($_GET["edit"]) ) OR isset($_GET["add"])  ) {
   $name = ""; $server = ""; $voucher = "";
   if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) $id = safeEscape( (int) $_GET["edit"] );
   //UPDATE
    if ( isset($_POST["edit_list"]) ) {
	  $name     = safeEscape( $_POST["name"]);
	  $server   = safeEscape( $_POST["server"]);
	  $voucher   = safeEscape( $_POST["voucher"]);
	  
	  if ( strlen( $name)<=2 ) $errors.="<div>Field Name does not have enough characters</div>";
	  
	  
	  $time = date( "Y-m-d H:i:s", time() );
	  
	  if ( isset($_GET["edit"]) ) $sql = "UPDATE safelist SET 
	  name= '".$name."', server = '".$server."', voucher = '".$voucher."'
	  WHERE id ='".$id."' LIMIT 1 ";
	  
	  if ( isset($_GET["add"]) ) $sql = "INSERT INTO safelist(name, server, voucher) VALUES('".$name."', '".$server."', '".$voucher."' )";
	  
	  if ( empty($errors) ) {
	  $result = $db->query($sql);
	  
	  if ( $result ) {
	  	  ?>
	  <div align="center">
	    <h2>User successfully updated. <a href="<?=$website?>adm/?safelist">&laquo; Back</a></h2>
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
	 $result = $db->query("SELECT * FROM safelist WHERE id = '".$id."' ");
	 $row = $db->fetch_array($result,'assoc');
	 $name       = ( $row["name"]);
	 $server     = ( $row["server"]);
	 $voucher     = ( $row["voucher"]);
	 $button = "Edit User";
	 } else { $button = "Add User to Safelist"; }
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
	     <td width="80"  class="padLeft">Server:</td>
		 <td><input name="server" style="width: 380px; height: 28px;" type="text" value="<?=$server?>" /></td>
	   </tr>
	   <tr class="row">
	     <td width="80"  class="padLeft">Voucher:</td>
		 <td><input name="voucher" style="width: 380px; height: 28px;" type="text" value="<?=$voucher?>" /></td>
	   </tr>
	   <tr>
	     <td width="80"></td>
		 <td class="padTop padBottom">
		 <input type="submit" value="Submit" name="edit_list" class="menuButtons" />
		 <a class="menuButtons" href="<?=$website?>adm/?safelist">&laquo; Back</a>
		 </td>
	   </tr>
	  </table>
	  </div>
	 </form>
	 <?php
  }

  $result = $db->query("SELECT COUNT(*) FROM safelist");

  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 30;
?>
<div align="center">
<div class="padBottom padTop"><a class="menuButtons" href="<?=$website?>adm/?safelist&amp;add">[+] Add User to Safelist</a></div>
<?php
  
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
   $result = $db->query("SELECT * FROM safelist ORDER BY id DESC LIMIT $offset, $rowsperpage");
   ?>
   <table>
    <tr>
	  <th width="180" class="alignleft padLeft">User</th>
	  <th width="64" class="alignleft">Action</th>
	  <th width="150" class="alignleft">Server</th>
	  <th width="120" class="alignleft">Voucher</th>
	</tr>
   <?php
   while ($row = $db->fetch_array($result,'assoc')) { ?>
   <tr class="row">
     <td width="180" class="alignleft padLeft"><a href="<?=$website?>adm/?safelist&amp;edit=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	 <td width="64" class="alignleft">
	 <a href="<?=$website?>adm/?safelist&amp;edit=<?=$row["id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete User from Safelist?') ) { location.href='<?=$website?>adm/?safelist&amp;del=<?=$row["id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	 <td width="150" class="alignleft overflow_hidden"><span title="<?=$row["server"]?>"><?=stripslashes($row["server"])?></span></td>
	 <td width="120" class="alignleft overflow_hidden"><span title="<?=$row["voucher"]?>"><?=stripslashes($row["voucher"])?></span></td>
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