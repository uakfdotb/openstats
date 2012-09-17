<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

if ( !$_GET ) {
   	$result = $db->query( "SELECT COUNT(*) FROM games 
	WHERE map LIKE '%dota%' AND stats = 0 AND duration>='".$MinDuration."'" );
    $r = $db->fetch_row($result);
    $TotalGamesForUpdate = $r[0];
	
   	$result = $db->query( "SELECT COUNT(*) FROM games 
	WHERE map LIKE '%dota%' AND stats = 1" );
    $r = $db->fetch_row($result);
    $TotalRankedGames = $r[0];
	
	$result = $db->query( "SELECT COUNT(*) FROM bans 
	WHERE id>=1" );
    $r = $db->fetch_row($result);
    $TotalBans = $r[0];
	
	$result = $db->query( "SELECT COUNT(*) FROM stats 
	WHERE id>=1" );
    $r = $db->fetch_row($result);
    $TotalRankedUsers = $r[0];
	
	$result = $db->query( "SELECT COUNT(*) FROM admins 
	WHERE id>=1" );
    $r = $db->fetch_row($result);
    $TotalAdmins = $r[0];
	
	$result = $db->query( "SELECT COUNT(*) FROM users 
	WHERE user_id>=1" );
    $r = $db->fetch_row($result);
    $TotalUsers = $r[0];
	
	$result = $db->query( "SELECT COUNT(*) FROM news 
	WHERE news_id>=1" );
    $r = $db->fetch_row($result);
    $TotalNews = $r[0];
	
   	$result = $db->query( "SELECT COUNT(*) FROM comments 
	WHERE id >= 1" );
    $r = $db->fetch_row($result);
    $TotalComments = $r[0];
	
	?>
	<div align="center" style="margin-top: 40px; margin-bottom: 100px;">
	<table>
	  <tr>
	    <th class="padLeft alignleft" width="200">Dashboard</th>
		<th width="140"></th>
	  </tr>
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Unranked games:</b></td>
	    <td width="140">
		<?=number_format($TotalGamesForUpdate,0)?>
		<?php if ($TotalGamesForUpdate>=1) { ?><a href="<?=$website?>adm/update_stats.php">Update</a><?php } ?>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Ranked games:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?games"><?=number_format($TotalRankedGames,0)?></a>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Ranked Players:</b></td>
	    <td width="140">
		<a href="<?=$website?>?top"><?=number_format($TotalRankedUsers,0)?></a>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Total Bans:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?bans"><?=number_format($TotalBans,0)?></a>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Total Admins:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?admins"><?=number_format($TotalAdmins,0)?></a>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Total Members:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?users"><?=number_format($TotalUsers,0)?></a>
		</td>
	  </tr>
	  
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Total Posts:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?posts"><?=number_format($TotalNews,0)?></a>
		</td>
	  </tr>
	  <tr class="row">
	    <td width="200" class="padLeft"><b>Total Comments:</b></td>
	    <td width="140">
		<a href="<?=$website?>adm/?comments"><?=number_format($TotalComments,0)?></a>
		</td>
	  </tr>
	  
	</table>
	<div class="padTop"></div>
	<table>
	<tr>
	<td width="200" class="padLeft"><a href="javascript:;" onclick="if (confirm('Are you sure you want to reset all statistics?') ) {  location.href='<?=$website?>adm/update_stats.php?reset' }" >Reset Statistics</a></td>
	</tr>
	</table>
	</div>
<div style="margin-top: 220px;">&nbsp;</div>
	<?php
} else 
if ( isset( $_GET["posts"]) )     include('admin_posts.php');
if ( isset( $_GET["bans"]) )      include('admin_bans.php');
if ( isset( $_GET["admins"]) )    include('admin_admins.php');
if ( isset( $_GET["safelist"]) )  include('admin_safelist.php');
if ( isset( $_GET["users"]) )     include('admin_users.php');
if ( isset( $_GET["games"]) )     include('admin_games.php');
if ( isset( $_GET["comments"]) )  include('admin_comments.php');
?>
