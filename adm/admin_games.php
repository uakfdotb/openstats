<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

$duration = 0; //
$filter = "";
$orderby = "id DESC";

if ( isset($_GET["del"]) AND is_numeric($_GET["del"]) ) {
   $id = safeEscape( $_GET["del"]);
   $del1 = $db->query("DELETE FROM dotagames WHERE gameid = '".$id."' ");
   $del2 = $db->query("DELETE FROM dotaplayers WHERE gameid = '".$id."' ");
   $del3 = $db->query("DELETE FROM gameplayers WHERE gameid = '".$id."' ");
   $del4 = $db->query("DELETE FROM games WHERE id = '".$id."' ");
   
   if ($del1 AND $del2 AND $del3 AND $del4 ) { ?>
   <div align="center">
   <h2>Game successfully deleted</h2>
   <a href="<?=$website?>adm/?games">&laquo; Back</a></h2>
   </div>
   <?php } 
   
}

if ( isset($_GET["edit"]) AND is_numeric($_GET["edit"]) ) {
   $gameid = safeEscape( (int) $_GET["edit"] );
   $result = $db->query(  getSingleGame( (int)$gameid ) );
   $row = $db->fetch_array($result,'assoc');
	 
	$creatorname  = ($row["creatorname"]);
	$duration  = secondsToTime($row["duration"]);
	$datetime  = date($DateFormat,strtotime($row["datetime"]));
	$date  = ($row["datetime"]);
	$gamename  = ($row["gamename"]);
	$winner = ($row["winner"]);
	$db->free($result);
	
	$result = $db->query( getGameInfo($gameid) );
	?>
	<div align="center">
	<h2>
	<?php if ($row["stats"] == 1) { ?><img src="<?=$website?>adm/ranked.png" width="16" height="16" class="imgvalign" alt="ranked" /><?php } ?>
	<?php if ($row["stats"] == 0) { ?><img src="<?=$website?>adm/unranked.png" width="16" height="16" class="imgvalign" alt="ranked" /><?php } ?>
	<?=($row["gamename"])?>
	</h2>
	<table>
	<tr>
	 <th width="80"  class="alignleft padLeft">Duration</th>
	 <th width="140" class="alignleft">Date</th>
	 <th width="140" class="alignleft">Creator</th>
	 <th width="64"  class="alignleft">Winner</th>
	 <th width="80"  class="alignleft">Views</th>
	</tr>
	<tr>
	 <td width="80" class="alignleft padLeft"><?=secondsToTime($row["duration"])?></td>
	 <td width="140" class="alignleft"><?=date($DateFormat,strtotime($row["datetime"]))?></td>
	 <td width="140" class="alignleft"><?=$row["creatorname"]?></td>
	 <td width="64" class="alignleft">
	 <b>
	   <?php if ($row["winner"] == 1) { ?>Sentinel<?php } ?>
	   <?php if ($row["winner"] == 2) { ?>Scourge<?php } ?>
	   <?php if ($row["winner"] == 0) { ?>Draw<?php } ?>
	 </b>
	 </td>
	 <td width="80" class="alignleft"><?=$row["views"]?></td>
	</tr>
	</table>
	<div class="padTop"></div>
	<!--
	<select name="stats">
	<?php if ($row["stats"] == 0)  $sel ='selected="selected"'; else $sel = ""; ?>
	  <option <?=$sel?> value="0">Unranked</option>
	<?php if ($row["stats"] == 1)  $sel ='selected="selected"'; else $sel = ""; ?>
	  <option <?=$sel?> value="1">Ranked</option>
	</select>
	<input type="button" value="Update" class="menuButtons" onclick="if (confirm('It is not recommended to manually change game status. Are you sure you want to continue?')) { location.href='<?=$website?>adm/?games&amp;edit=<?=$gameid?>&amp;update=<?=$row["stats"]?>' }" />
	<div class="padTop"></div>
	-->
	<table>
	<tr>
	  <th width="32" class="alignleft padLeft">Hero</th>
	  <th width="150" class="alignleft">Player</th>
	  <th width="170" class="alignleft">Items</th>
	  <th width="80" class="alignleft">K/D/A</th>
	  <th width="80" class="alignleft">C/D/N</th>
	  <th width="220" class="alignleft">Left</th>
	</tr>
	<?php
	$scourge = 0;
	while ($row = $db->fetch_array($result,'assoc')) {
	if ( $row["newcolour"]>5  AND $scourge == 0 ) { $scourge = 1; ?></table> <table><tr><th width="745">Scourge</th></tr></table> <table><?php } 
	?>
	<tr class="row">
	 <td width="32" class="alignleft padLeft"><img src="<?=$website?>img/heroes/<?=$row["hero"]?>.gif" alt="hero" width="24" height="24" /></td>
	 <td width="150" class="alignleft">
	 <a href="<?=$website?>?u=<?=strtolower($row["name"]) ?>"><?php if ( strtolower($row["name"]) == strtolower($row["banname"]) ) { ?><span class="banned"><?=($row["name"])?></span><?php } 
	 else { ?><?=($row["name"])?><?php } ?></a>
	 </td>
	 <td width="170" class="alignleft">
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon1"])) echo $row["itemicon1"]; else echo "empty.gif"; ?>" alt="item1" width="24" height="24" />
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon2"])) echo $row["itemicon2"]; else echo "empty.gif"; ?>" alt="item2" width="24" height="24" />
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon3"])) echo $row["itemicon3"]; else echo "empty.gif"; ?>" alt="item3" width="24" height="24" />
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon4"])) echo $row["itemicon4"]; else echo "empty.gif"; ?>" alt="item4" width="24" height="24" />
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon5"])) echo $row["itemicon5"]; else echo "empty.gif"; ?>" alt="item5" width="24" height="24" />
	 <img src="<?=$website?>img/items/<?php if (!empty($row["itemicon6"])) echo $row["itemicon6"]; else echo "empty.gif"; ?>" alt="item6" width="24" height="24" />
	 </td>
	 <td width="80" class="alignleft">
	  <span class="won"><?=($row["kills"])?></span> / 
	  <span class="lost"><?=$row["deaths"]?></span> / 
	  <span class="assists"><?=$row["assists"]?></span>
	 </td>
	 <td width="80" class="alignleft">
  	  <span class="won"><?=($row["creepkills"])?></span> / 
	  <span class="lost"><?=$row["creepdenies"]?></span> / 
	  <span class="assists"><?=$row["neutralkills"]?></span>
	 </td>
	 <td width="220" class="alignleft overflow_hidden"><?=$row["leftreason"]?></td>
	</tr>
    <?php	
	}
	$db->free($result);
	?>
	</table>
	<div class="padTop"></div>
	<div class="padTop bottom"></div>
	<?php
	
}

//GAMES HISTORY
if ( isset($_GET["sort"]) ) {
   if ( $_GET["sort"] == "id" )       $orderby = "id DESC"; else 
   if ( $_GET["sort"] == "duration" ) $orderby = "duration DESC"; else 
   if ( $_GET["sort"] == "type" )     $orderby = "type ASC"; else 
   if ( $_GET["sort"] == "creator" )  $orderby = "LOWER(creatorname) ASC"; 
} 

if ( isset($_GET["game_id"]) AND is_numeric($_GET["game_id"]) ) {
    $id = safeEscape( (int) $_GET["game_id"]);
	$filter = " AND g.id = '".$id."' ";
}

?>
<div align="center">

  <form action="" method="get">  
  Sort by:
  <input type="hidden" name="games" />
<select name="sort">
<?php if (isset($_GET["sort"]) AND $_GET["sort"] =="id") $sel = 'selected="selected"'; else $sel = ""; ?>
    <option <?=$sel?> value="id">ID</option>
<?php if (isset($_GET["sort"]) AND $_GET["sort"] =="duration") $sel = 'selected="selected"'; else $sel = ""; ?>
	<option <?=$sel?> value="duration">Duration</option>
<?php if (isset($_GET["sort"]) AND $_GET["sort"] =="type") $sel = 'selected="selected"'; else $sel = ""; ?>
	<option <?=$sel?> value="type">Type</option>
<?php if (isset($_GET["sort"]) AND $_GET["sort"] =="creator") $sel = 'selected="selected"'; else $sel = ""; ?>
	<option <?=$sel?> value="creator">Creator</option>
</select>
   <input type="submit" class="menuButtons" value="Submit" />
   
   &nbsp; &nbsp; <input type="button" value="Update Stats" class="menuButtons" onclick="location.href='<?=$website?>adm/update_stats.php'" />
</form>

<?php

  $result = $db->query("SELECT COUNT(*) FROM games as g
  WHERE g.map LIKE '%dota%' AND g.duration>='".$duration."' $filter
  LIMIT 1");
	 
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $GamesPerPage;
  $draw_pagination = 1;
  $SHOW_TOTALS = 1;
  include('pagination.php');
  
  $result = $db->query( getAllGames( $duration, $offset, $rowsperpage, $filter, $orderby ) );
  ?>
  <table>
    <tr>
	 <th width="200" class="padLeft alignleft">Game name</th>
	 <th width="64"  class="alignleft">Action</th>
	 <th width="80"  class="alignleft">Duration</th>
	 <th width="40"  class="alignleft">Type</th>
	 <th width="140" class="alignleft">Date</th>
	 <th width="150" class="alignleft">Creator</th>
	 <th width="80"  class="alignleft">Views</th>
	</tr>
  <?php
  while ($row = $db->fetch_array($result,'assoc')) {
  ?>
  <tr class="row">
    <td width="200" class="padLeft alignleft overflow:hidden;">
	<?php if ($row["stats"] == 1) { ?><img title="Ranked game" src="<?=$website?>adm/ranked.png" width="16" height="16" class="imgvalign" alt="ranked" /><?php } ?>
	<?php if ($row["stats"] == 0) { ?><img title="Unranked game" src="<?=$website?>adm/unranked.png" width="16" height="16" class="imgvalign" alt="ranked" /><?php } ?>
	<a href="<?=$website?>adm/?games&amp;edit=<?=$row["id"]?>"><span class="winner<?=$row["winner"]?>"><?=$row["gamename"]?></span></a>
	</td>
	 <td width="64" class="alignleft">
	 <a href="<?=$website?>adm/?games&amp;edit=<?=$row["id"]?>"><img src="<?=$website?>adm/edit.png" alt="img" /></a>
	 <a href="javascript:;" onclick="if (confirm('Delete Game? (Note: After delete you\'ll have to reset and update the stats)') ) { location.href='<?=$website?>adm/?games&amp;del=<?=$row["id"]?>' }"><img src="<?=$website?>adm/del.png" alt="img" /></a>
	 </td>
	<td width="80"  class="alignleft"><?=secondsToTime($row["duration"])?></td>
	<td width="40"  class="alignleft"><?=$row["type"]?></td>
	<td width="140" class="alignleft"><?=date($DateFormat, strtotime($row["datetime"]))?></td>
	<td width="150" class="alignleft"><?=$row["creatorname"]?></td>
	<td width="80"  class="alignleft"><?=($row["views"])?></td>
  </tr>
  <?php
  }
  ?>
  </table>
  </div>
  <?php
  $db->free($result);
  include('pagination.php');
?>