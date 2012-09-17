<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
$errors = "";

if ( isset($_GET["search_bans"]) ) $s = safeEscape($_GET["search_bans"]); else $s=""; 
?>
<div align="center" class="padBottom">
	 <form action="" method="get">
	 <table>
	   <tr>
	    <td width="290">
		
		  <input type="hidden" name="bans" />
		  <input style="width: 180px; height: 24px;" type="text" name="search_bans" value="<?=$s?>" />
		  <input class="menuButtons" type="submit" value="Search" />
		</td>
	    <td><a class="menuButtons" href="<?=$website?>adm/?bans&amp;add">[+] Add Ban</a></td>
	   </tr>
	 </table>
	 </form>
</div>
<?php
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $delete = $db->query("DELETE FROM bans WHERE id ='".(int)$id."' LIMIT 1 ");
	  
	  ?>
	  <div align="center">
	  <h2>Ban successfully deleted. <a href="<?=$website?>adm/?bans">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
//eDIT
  if ( (isset( $_GET["edit"]) AND is_numeric($_GET["edit"]) ) OR isset($_GET["add"])  ) {
   $name = ""; $server = ""; $reason = ""; $ip = ""; $admin = ""; $gn="";
   if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) $id = safeEscape( (int) $_GET["edit"] );
   //UPDATE
    if ( isset($_POST["edit_ban"]) ) {
	  $name     = safeEscape( $_POST["name"]);
	  $server   = safeEscape( $_POST["server"]);
	  $reason   = EscapeStr( convEnt2($_POST["reason"]));
	  $ip       = safeEscape( $_POST["ip"]);
	  $admin    = safeEscape( $_POST["admin"]);
	  $gn       = safeEscape( $_POST["gn"]);
	  
	  if ( strlen( $name)<=2 ) $errors.="<div>Field Name does not have enough characters</div>";
	  
	  
	  $time = date( "Y-m-d H:i:s", time() );
	  
	  if ( isset($_GET["edit"]) ) $sql = "UPDATE bans SET 
	  name= '".$name."', server = '".$server."', reason = '".$reason."', ip='".$ip."', admin = '".$admin."', gamename='".$gn."' WHERE id ='".$id."' LIMIT 1 ";
	  
	  if ( isset($_GET["add"]) ) $sql = "INSERT INTO bans(name, server, reason, ip, admin, gamename, date) 
	  VALUES('".$name."', '".$server."', '".$reason."', '".$ip."', '".$admin."', '".$gn."', '".$time ."' )";
	  
	  if ( empty($errors) ) {
	  $result = $db->query($sql);
	  
	  if ( $result ) {
	  	  ?>
	  <div align="center">
	    <h2>Ban successfully updated. <a href="<?=$website?>adm/?bans">&laquo; Back</a></h2>
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
	 $result = $db->query("SELECT * FROM bans WHERE id = '".$id."' ");
	 $row = $db->fetch_array($result,'assoc');
	 $name     = ( $row["name"]);
	 $server   = ( $row["server"]);
	 $reason   = ($row["reason"]);
	 $ip       = ( $row["ip"]);
	 $admin    = ( $row["admin"]);
	 $gn       = ( $row["gamename"]);
	 $button = "Edit Ban";
	 } else { $button = "Add Ban"; }
	 ?>
	 <div align="center">
	 <form action="" method="post">
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
	     <td width="80"  class="padLeft">Reason:</td>
		 <td><input name="reason" style="width: 380px; height: 28px;" type="text" value="<?=$reason?>" /></td>
	   </tr>
	   <tr class="row">
	     <td width="80"  class="padLeft">Gamename:</td>
		 <td><input name="gn" style="width: 380px; height: 28px;" type="text" value="<?=$gn?>" /></td>
	   </tr>
	   <tr class="row">
	     <td width="80"  class="padLeft">IP:</td>
		 <td><input name="ip" style="width: 380px; height: 28px;" type="text" value="<?=$ip?>" /></td>
	   </tr>
	   <tr class="row">
	     <td width="80"  class="padLeft">Banned by:</td>
		 <td><input name="admin" style="width: 380px; height: 28px;" type="text" value="<?=$admin?>" /></td>
	   </tr>
	   <tr>
	     <td width="80"></td>
		 <td class="padTop padBottom">
		 <input type="submit" value="Submit" name="edit_ban" class="menuButtons" /> &nbsp; &nbsp; &nbsp; &nbsp;
		 <a class="menuButtons" href="<?=$website?>adm/?bans">&laquo; Back to Bans</a>
<?php if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) { ?>
		 <a onclick="if (confirm('Delete ban?') ) { location.href='<?=$website?>adm/?bans&amp;del=<?=$id?>' }" class="menuButtons" href="javascript:;">&times; Delete Ban</a><?php } ?>
		 </td>
	   </tr>
	  </table>
	 </form>
	  </div>
	  <div class="padBottom"></div>
	 <?php
  }
  
  if ( isset($_GET["search_bans"]) AND strlen($_GET["search_bans"])>=2 ) {
     $search_bans = safeEscape( $_GET["search_bans"]);
	 $sql = " AND LOWER(name) LIKE LOWER('%".$search_bans."%') ";
  } else {
   $sql = "";
   $search_bans= "";
  }

  $result = $db->query("SELECT COUNT(*) FROM bans WHERE id>=1 $sql");

  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 20;
?>
<div align="center">
<?php
  
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
   $result = $db->query("SELECT * FROM bans WHERE id>=1 $sql ORDER BY id DESC LIMIT $offset, $rowsperpage");
   ?>
   <table>
    <tr>
	  <th width="180" class="alignleft padLeft">Player</th>
	  <th width="64" class="alignleft">Action</th>
	  <th width="260" class="alignleft">Reason</th>
	  <th width="140" class="alignleft">Banned by</th>
	  <th width="120">Date</th>
	</tr>
   <?php
   while ($row = $db->fetch_array($result,'assoc')) { ?>
   <tr class="row">
     <td width="180" class="alignleft padLeft"><a href="<?=$website?>adm/?bans&amp;edit=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	 <td width="64" class="alignleft">
	 <a href="<?=$website?>adm/?bans&amp;edit=<?=$row["id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete ban?') ) { location.href='<?=$website?>adm/?bans&amp;del=<?=$row["id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	 <td width="260" class="alignleft overflow_hidden"><span title="<?=$row["reason"]?>"><?=stripslashes($row["reason"])?></span></td>
	 <td width="140" class="alignleft"><?=$row["admin"]?></td>
	 <td width="120"><i><?=date($DateFormat, strtotime($row["date"]))?></i></td>
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