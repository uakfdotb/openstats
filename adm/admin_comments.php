<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
$errors = "";
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) AND isset($_GET["pid"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $pid = safeEscape( (int) $_GET["pid"] );
	  $delete = $db->query("DELETE FROM comments WHERE id ='".(int)$id."' LIMIT 1 ");
	  $get = $db->query("SELECT COUNT(*) FROM comments WHERE post_id= '".$pid."' LIMIT 1");
	  $r = $db->fetch_row($get);
	  $TotalComments = $r[0];
	  $update = $db->query("UPDATE news SET comments = '".$TotalComments."' WHERE news_id = '".$pid."' ");
	  
	  ?>
	  <div align="center">
	  <h2>Comment successfully deleted. <a href="<?=$website?>adm/?comments">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
//eDIT
  if ( (isset( $_GET["edit"]) AND is_numeric($_GET["edit"]) )  ) {
   $name = ""; $server = "";
   if ( isset($_GET["edit"])  AND is_numeric($_GET["edit"])  ) $id = safeEscape( (int) $_GET["edit"] );
   //UPDATE
    if ( isset($_POST["edit_comment"]) ) {
	$text = my_nl2br( trim($_POST["comment"]) );
	$text = nl2br($text);
	$text = EscapeStr( ($text) );
	$text = (($text));
	  
	  if ( strlen( $text)<=2 ) $errors.="<div>Field Text does not have enough characters</div>";
	  
	  
	  $time = date( "Y-m-d H:i:s", time() );
	  
	  if ( isset($_GET["edit"]) ) $sql = "UPDATE comments SET 
	  text= '".$text."' WHERE id ='".$id."' LIMIT 1 ";
	  
	  if ( empty($errors) ) {
	  $result = $db->query($sql);
	  
	  if ( $result ) {
	  	  ?>
	  <div align="center">
	    <h2>Comment successfully updated. <a href="<?=$website?>adm/?comments">&laquo; Back</a></h2>
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
	 $result = $db->query("SELECT * FROM comments WHERE id = '".$id."' ");
	 $row = $db->fetch_array($result,'assoc');
	 $text       = convEnt( $row["text"]);
	 $text = br2nl( $text );
	 $button = "Edit Comment";
	 } else {  }
	 ?>
	 
	 <form action="" method="post">
	 <div align="center">
	 <h2><?=$button?></h2>
	 <table>
	   <tr class="row">
	     <td width="80" class="padLeft">Comment:</td>
		 <td><textarea name="comment" style="width: 560px; height: 220px;"><?=$text ?></textarea></td>
	   </tr>
	   <tr>
	     <td width="80"></td>
		 <td class="padTop padBottom">
		 <input type="submit" value="Submit" name="edit_comment" class="menuButtons" />
		 <a class="menuButtons" href="<?=$website?>adm/?comments">&laquo; Back</a>
		 </td>
	   </tr>
	  </table>
	  </div>
	 </form>
	 <?php
  }
  
  if ( isset($_GET["post"]) AND is_numeric($_GET["post"]) ) {
     $pid = safeEscape( (int) $_GET["post"] );
	 $sql = "AND c.post_id = '".$pid."' ";
  } else $sql ="";

  $result = $db->query("SELECT COUNT(*) FROM comments as c 
  WHERE id >= 1 $sql");

  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 30;
?>
<div align="center">
<?php
  
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
   $result = $db->query("SELECT c.*, u.user_name, n.news_title, n.news_id
   FROM comments as c
   LEFT JOIN users as u ON u.user_id = c.user_id
   LEFT JOIN news as n ON n.news_id = c.post_id
   WHERE c.id>=1 $sql
   ORDER BY c.id 
   DESC LIMIT $offset, $rowsperpage");
   ?>
   <table>
    <tr>
	  <th width="150" class="padLeft alignleft">User</th>
	  <th width="450">Post</th>
      <th width="64">Action</th>
	</tr>
   <?php
   while ($row = $db->fetch_array($result,'assoc')) { ?>
   <tr class="row">
     <td width="150" class="padLeft alignleft"><a href="<?=$website?>adm/?comments&amp;edit=<?=$row["id"]?>"><?=$row["user_name"]?></a>
	 <div style="font-size:11px;"><?=date($DateFormat,$row["date"])?></div>
	 </td>
	  <td width="450">
      <div style="text-align:left; font-size:12px;"><a href="<?=$website?>adm/?comments&amp;edit=<?=$row["id"]?>"><?=$row["news_title"]?></a></div>
	  <?=limit_words(convEnt($row["text"]), 16)?>
	  </td>
	 <td width="64">
	 <a href="<?=$website?>adm/?comments&amp;edit=<?=$row["id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete Comment?') ) { location.href='<?=$website?>adm/?comments&amp;del=<?=$row["id"]?>&pid=<?=$row["news_id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
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