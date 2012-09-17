<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
$errors = "";
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $delete = $db->query("DELETE FROM admins WHERE id ='".(int)$id."' LIMIT 1 ");
	  
	  ?>
	  <div align="center">
	  <h2>Admin successfully deleted. <a href="<?=$website?>adm/?admins">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
//eDIT
  if ( (isset( $_GET["edit"]) AND is_numeric($_GET["edit"]) ) OR isset($_GET["add"])  ) {
   $name = ""; $server = "";
   if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) $id = safeEscape( (int) $_GET["edit"] );
   //UPDATE
    if ( isset($_POST["edit_admin"]) ) {
	  $name     = safeEscape( $_POST["name"]);
	  $server   = safeEscape( $_POST["server"]);
	  
	  if ( strlen( $name)<=2 ) $errors.="<div>Field Name does not have enough characters</div>";
	  
	  
	  $time = date( "Y-m-d H:i:s", time() );
	  
	  if ( isset($_GET["edit"]) ) $sql = "UPDATE admins SET 
	  name= '".$name."', server = '".$server."' WHERE id ='".$id."' LIMIT 1 ";
	  
	  if ( isset($_GET["add"]) ) $sql = "INSERT INTO admins(name, server) VALUES('".$name."', '".$server."' )";
	  
	  if ( empty($errors) ) {
	  $result = $db->query($sql);
	  
	  if ( $result ) {
	  	  ?>
	  <div align="center">
	    <h2>Admin successfully updated. <a href="<?=$website?>adm/?admins">&laquo; Back</a></h2>
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
	 $result = $db->query("SELECT * FROM admins WHERE id = '".$id."' ");
	 $row = $db->fetch_array($result,'assoc');
	 $name       = ( $row["name"]);
	 $server     = ( $row["server"]);
	 $button = "Edit Admin";
	 } else { $button = "Add Admin"; }
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
	   <tr>
	     <td width="80"></td>
		 <td class="padTop padBottom">
		 <input type="submit" value="Submit" name="edit_admin" class="menuButtons" />
		 <a class="menuButtons" href="<?=$website?>adm/?admins">&laquo; Back</a>
		 </td>
	   </tr>
	  </table>
	  </div>
	 </form>
	 <?php
  }

  $result = $db->query("SELECT COUNT(*) FROM admins");

  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 30;
?>
<div align="center">
<div class="padBottom padTop"><a class="menuButtons" href="<?=$website?>adm/?admins&amp;add">[+] Add Admin</a></div>
<?php
  
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
   $result = $db->query("SELECT * FROM admins ORDER BY id DESC LIMIT $offset, $rowsperpage");
   ?>
   <table>
    <tr>
	  <th width="180" class="alignleft padLeft">Admin</th>
	  <th width="64" class="alignleft">Action</th>
	  <th width="140" class="alignleft">Server</th>
	</tr>
   <?php
   while ($row = $db->fetch_array($result,'assoc')) { ?>
   <tr>
     <td width="180" class="alignleft padLeft"><a href="<?=$website?>adm/?admins&amp;edit=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	 <td width="64" class="alignleft">
	 <a href="<?=$website?>adm/?admins&amp;edit=<?=$row["id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete Admin?') ) { location.href='<?=$website?>adm/?admins&amp;del=<?=$row["id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	 <td width="140" class="alignleft overflow_hidden"><span title="<?=$row["server"]?>"><?=stripslashes($row["server"])?></span></td>
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
  
  <div style="margin-top: 180px;">&nbsp;</div>