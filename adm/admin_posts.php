<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
?>
<div align="center"> 

<?php 
if ( isset($_GET["publish"])  AND is_numeric($_GET["publish"]) ) {
  $id = safeEscape( (int) $_GET["publish"] );
  $update = $db->query("UPDATE news SET status = 1 WHERE news_id = '".$id."' LIMIT 1");
}
if ( isset($_GET["draft"])  AND is_numeric($_GET["draft"]) ) {
  $id = safeEscape( (int) $_GET["draft"] );
  $update = $db->query("UPDATE news SET status = 0 WHERE news_id = '".$id."' LIMIT 1");
}
//delete
  if ( isset( $_GET["del"]) AND is_numeric($_GET["del"]) ) {
      $id = safeEscape( (int) $_GET["del"] );
	  $delete       = $db->query("DELETE FROM news WHERE news_id ='".(int)$id."' LIMIT 1 ");
	  $delete_posts = $db->query("DELETE FROM comments WHERE post_id ='".(int)$id."' LIMIT 1 ");
	  ?>
	  <div align="center">
	  <h2>Post successfully deleted. <a href="<?=$website?>adm/?posts">&laquo; Back</a></h2>
	  </div>
	  <?php 
  }
   
  //ADD / EDIT POST
  if ( isset( $_GET["add"]) OR (isset($_GET["edit"]) AND is_numeric($_GET["edit"]) ) ) {
  
  if ( isset($_POST["add_post"]) ) {
     $title = EscapeStr($_POST["post_title"]);
	 $status = EscapeStr( (int) $_POST["status"]);
	 $text = (my_nl2br(convEnt2(trim($_POST["post_text"]))));
	 $text = str_replace(array("&Scaron;", "&scaron;"),array("Š","š"), $text   );
	 $errors ="";
	 $time = date( "Y-m-d H:i:s", time() );
	 
	 if ( strlen($title)<=3 ) $errors.="<div>Field Title does not have enough characters</div>";
	 if ( strlen($text)<=5 )  $errors.="<div>Field Text does not have enough characters</div>";
	 
	 if ( empty($errors) ) {
	    
		if ( isset($_GET["add"]) ) {
		$insert = $db->query("INSERT INTO news(news_title, news_content, news_date)
		VALUES('".$title."', '".$text."', '".$time."') ");
		
		if ( $insert) {
		?>
	    <div align="center">
	       <h2>Post successfully added. <a href="<?=$website?>adm/?posts">&laquo; Back</a></h2>
	    </div>		
		<?php
		}
	}
		
		if ( isset($_GET["edit"]) ) {
		$id = safeEscape( (int) $_GET["edit"]);
		
		$update = $db->query("UPDATE news SET 
		news_title = '".$title."', news_content = '".$text."', status='".$status."' 
		WHERE news_id = '".$id."' ");
		
	    if ( $update) {
		?>
	    <div align="center">
	       <h2>Post successfully updated. <a href="<?=$website?>adm/?posts">&laquo; Back</a></h2>
	    </div>		
		<?php
		}
		
		}
		
	 }
  }
  
  if ( isset($_GET["edit"]) AND is_numeric($_GET["edit"]) ) {
    $id = safeEscape( (int) $_GET["edit"]);
	$result  = $db->query("SELECT * FROM news WHERE news_id = '".$id."' LIMIT 1 ");
	$row = $db->fetch_array($result,'assoc');
	$title = $row["news_title"];
	$text = $row["news_content"];
	$status = $row["status"];
  } else {
    $title = "";
	$text  = "";
	$status = 1;
  }
?>
<form action="" method="post">
  <table>
  <tr>
  <td class="padLeft">
  Post Title: <input style="width: 500px; height: 34px; background-color: #fafafa; color: #000;" type="text" value="<?=$title?>" name="post_title" size="75" maxlength="254" />
  
  </td>
  </tr>
  <tr>
  <td>
  <textarea class="ckeditor" cols="90" id="editor1" name="post_text" rows="20"><?=$text?></textarea>
  </td>
  </tr>
    <tr>
  <td class="padLeft">
  <div class="padTop"></div>
    <select name="status">
    <?php if ($status==0) $sel = 'selected="selected"'; else $sel = ""; ?>
    <option <?=$sel?> value="0">Draft</option>
    <?php if ($status==1) $sel = 'selected="selected"'; else $sel = ""; ?>
    <option <?=$sel?> value="1">Published</option>
    </select>
  <div class="padTop"></div>
  </td>
  </tr>
  <tr class="row">
   <td>
        <div class="padTop padLeft padBottom">
		<input type="submit" value="Submit" class="menuButtons" name="add_post" />
		<a class="menuButtons padPeft" href="<?=$website?>adm/?posts">&times; Back</a>
		</div>
	</td>
  </tr>
  </table>
</form>
	<script type="text/javascript" src="<?php echo $website;?>adm/editor.js"></script>
	
<?php } else { ?>
<div><h2><a href="<?=$website?>adm/?posts&amp;add">[+] Add Post</a></h2></div>
<?php 
if ( !isset($_GET["edit"])  ) {
  $result  = $db->query("SELECT COUNT(*) FROM news WHERE news_id>=1 ");
  
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = 30;
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
  $result  = $db->query("SELECT * FROM news WHERE news_id>=1 ORDER BY news_id DESC 
  LIMIT $offset, $rowsperpage");
  ?>
  <table>
    <tr>
	  <th width="400" class="padLeft alignleft">Title</th>
	  <th width="120"  class="alignleft">Action</th>
	  <th width="64"  class="alignleft">Comments</th>
	  <th width="120">Added</th>
	</tr>
  <?php
   while ($row = $db->fetch_array($result,'assoc')) { 
   $title = $row["news_title"];
   if ($row["status"] == 0) $title = "<span style='color: #fff;'>".$title."</span>";
   ?>
   <tr class="row">
     <td width="400" class="padLeft alignleft">
	   <a href="<?=$website?>adm/?posts&amp;edit=<?=$row["news_id"]?>"><?=$title?></a>
	 </td>
	 <td width="120" class="alignleft">
	 <?php 
	 if ( isset($_GET["page"]) AND is_numeric($_GET["page"]) ) $p = "&amp;page=".(int)$_GET["page"]; else $p="";
	 if ($row["status"] == 0) { ?><a href="<?=$website?>adm/?posts<?=$p?>&amp;publish=<?=$row["news_id"]?>">Publish</a><?php } ?>
	 <?php if ($row["status"] == 1) { ?><a href="<?=$website?>adm/?posts<?=$p?>&amp;draft=<?=$row["news_id"]?>">Draft</a><?php } ?>
	 <a href="<?=$website?>adm/?posts&amp;edit=<?=$row["news_id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete Post?') ) { location.href='<?=$website?>adm/?posts&amp;del=<?=$row["news_id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	 <td width="64"  class="alignleft"><a href="<?=$website?>adm/?comments&amp;post=<?=$row["news_id"]?>"><?=$row["comments"]?></a></td>
	 <td width="120"><?=date($DateFormat, strtotime($row["news_date"]))?></td>
   </tr>
   
   <?php } 
   $db->free($result);
   ?>
   </table>
<?php 
include('pagination.php');
  } 
}
?>
</div>

<div style="margin-top: 160px;">&nbsp;</div>