<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

$code = generate_hash(8);
$_SESSION["code"] = $code;
if ( isset($errors) AND !empty($errors) ) { ?>
<div><?=$errors?></div>
<?php
}

if ( isset($CommentsData) AND !empty($CommentsData) ) {
?>
<a name="comments"></a>
<h2><?=$lang["comments"]?></h2>
<table>
<?php
   foreach($CommentsData as $Comment) {
   ?>
   <tr class="row">
      <td width="810" class="padLeft">
	  <div style="padding-top: 10px;">
	    <b><?=$Comment["username"]?></b>, <i><?=$Comment["date"]?></i>
        <?php if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) { ?>
		<a style="float: right; padding-right: 10px;" href="javascript:;" onclick="if (confirm('Delete comment?') ) { location.href='<?=$website?>?post_id=<?=$Comment["post_id"]?>&delete_comment=<?=$Comment["id"]?>' }" >&times;</a>
        | <a style="padding-left: 10px; font-size:11px;" href="<?=$website?>adm/?comments&amp;edit=<?=$Comment["id"]?>">edit comment</a>
		<?php } ?>
	  </div>
	  <div style="padding-top: 10px; padding-bottom: 20px;"><?=$Comment["text"]?></div>
	  </td>
    </tr>
   <?php
   }
?>
</table>
<?php
$numrows = $total_comments;
$result_per_page = $CommentsPerPage;
include('inc/pagination.php');
}
//COMMENT FORM
?>
<form action method="post">
	<table>
<?php if ( !is_logged() ) { ?> 
	<tr>
	  <td class="padLeft padTop padBottom">
	     <?=$lang["comment_not_logged"]?>
	  </td>
	</tr>
<?php } ?> 
	<tr>
	  <th width="810" class="alignleft padLeft"><?=$lang["add_comment"]?></th>
	</tr>
	<tr>
	  <td class="padLeft padTop padBottom">
	     <textarea <input <?php if ( !is_logged() ) { ?>disabled<?php } ?>  style="width: 420px; height: 120px;" name="post_comment"></textarea>
	  </td>
	</tr>
	<tr>
	  <td class="padLeft padTop padBottom">
	    <input <?php if ( !is_logged() ) { ?>disabled<?php } ?> class="menuButtons" type="submit" value="<?=$lang["add_comment_button"]?>" name="add_comment" />
	  </td>
	</tr>
	</table>
	
	<input type="hidden" value="<?=(int)safeEscape( $_GET["post_id"] )?>" name="pid" />
	<input type="hidden" value="<?=$code?>" name="code" />
</form>