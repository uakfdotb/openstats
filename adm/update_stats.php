<?php

include("../config.php");

	include('../lang/'.$default_language.'.php');
	include("../inc/common.php");
if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) {
	include("../inc/class.database.php");
	include("../inc/db_connect.php");

$ScoreStart = '1000';
$ScoreWins = '5';
$ScoreLosses = '-3';
$ScoreDisc = '-10';

$return = "";
	
//How many games to update at once
$updateGames = 100;

	if (isset($_GET["reset"])) {
	$r1 = $db->query("UPDATE games SET stats = 0 WHERE stats = 1");
	$r2 = $db->query("DELETE FROM stats");
	$r3 = $db->query("ALTER table stats auto_increment = 1");
	}
	
	if ( isset($_GET["start"]) ) {
	$_result = $db->query( "SELECT id FROM games 
	WHERE map LIKE '%dota%' AND stats = 0 AND duration>='".$MinDuration."' LIMIT ".$updateGames." " );
	
	 while ($row = $db->fetch_array($_result,'assoc')) {
	 $gid = $row["id"];
	 $result = $db->query("SELECT winner, dp.gameid, gp.colour, newcolour, kills, deaths, assists, creepkills, creepdenies, neutralkills, towerkills, gold,  raxkills, courierkills, g.duration as duration,
	   gp.name as name, 
	   gp.ip as ip,
	   b.name as banname 
	   FROM dotaplayers AS dp 
	   LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	   LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid 
	   LEFT JOIN games AS g ON g.id = dp.gameid 
	   LEFT JOIN bans as b ON b.name=gp.name
	   WHERE dp.gameid='".$gid."'
	   GROUP by gp.name
	   ORDER BY newcolour");
	   
	   if ($db->num_rows($result) <=0)  $update = $db->query("UPDATE games SET stats = 1 WHERE id = $gid ;");
	   
	   while ($list = $db->fetch_array($result,'assoc')) {
		$kills=$list["kills"];
		$deaths=$list["deaths"];
		$assists=$list["assists"];
		$creepkills=$list["creepkills"];
		$creepdenies=$list["creepdenies"];
		$neutralkills=$list["neutralkills"];
		$towerkills=$list["towerkills"];
		$raxkills=$list["raxkills"];
		$courierkills=$list["courierkills"];
		$duration=$list["duration"];
		$name=mysql_real_escape_string(trim($list["name"]));
		$IPaddress = $list["ip"];
		$banname=$list["banname"];
		$win=$list["winner"];
		$newcolour=$list["newcolour"];
		
		if ( strtolower($banname)==strtolower($name) ) $BANNED = 1; else $BANNED = 0;
		
		if ($win==1 AND $newcolour<=5) {$winner = 1; $loser = 0;}
		if ($win==0) {$winner = 0; $loser = 0;}
		if ($win==2 AND $newcolour>5) {$winner = 1; $loser = 0;}
		if ($win==1 AND $newcolour>5) {$winner = 0; $loser = 1;}
		if ($win==2 AND $newcolour<=5) {$winner = 0; $loser = 1;}
		
		if ($winner == 1) $score = $ScoreStart + $ScoreWins;
		if ($winner == 0) $score = $ScoreStart + $ScoreLosses;
		if ($win==0) $score = $ScoreStart;
		
		if ($win==0) $draw = 1; else $draw = 0;
		if (!empty($name) AND $duration >= $MinDuration) {
		$result2 = $db->query("SELECT player FROM stats WHERE LOWER(player) = LOWER('$name')");
		//Create a new player...
		  if ( $db->num_rows($result2) <=0) {
          $sql3 = "INSERT INTO stats(player, score, games, wins, losses, draw, kills, deaths, assists, creeps, denies, neutrals, towers, rax, banned, ip) 
		  VALUES('$name',$score,1,$winner,$loser,$draw,$kills,$deaths,$assists,$creepkills,$creepdenies,$neutralkills, $towerkills, $raxkills, $BANNED, '$IPaddress')";
          } else {
		  //...or update player data
		  if ($winner == 1) $score = "score = score + $ScoreWins,";
		  if ($winner == 0) $score = "score = score + $ScoreLosses,";
		  if ($win==0) $score = "";
		  $sql3 = "UPDATE stats SET 
		  $score
		  player = '$name',
		  games = games+1, 
		  wins = wins +$winner,
		  losses = losses+$loser,
		  draw = draw + $draw,
		  kills = kills + $kills,
		  deaths = deaths + $deaths,
		  assists = assists + $assists,
		  creeps = creeps + $creepkills,
		  denies = denies + $creepdenies,
		  neutrals = neutrals + $neutralkills,
		  towers = towers + $towerkills,
		  rax = rax + $raxkills,
          banned = $BANNED,
		  ip = '$IPaddress'
		  WHERE LOWER(player) = LOWER('$name');";
		   }
		  $result3 = $db->query($sql3);
		 }
		 //$return.="\nGame ($gid) updated!";
	     //Update "games" table so we can know what games have been updated
	     $update = $db->query("UPDATE games SET stats = 1 WHERE id = $gid ;");
		 
	   }
	   $return.="\nGame ($gid) updated!";
	 }
	 
	 
	}
	
if (isset($_GET["reset"])) {  
   header("location: ".$website."adm/update_stats.php"); die;
}

	$result = $db->query( "SELECT COUNT(*) FROM games 
	WHERE map LIKE '%dota%' AND stats = 0 AND duration>='".$MinDuration."'" );
    $r = $db->fetch_row($result);
    $TotalGamesForUpdate = $r[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if (isset($_GET["refresh"]) AND is_numeric($_GET["refresh"]) AND $TotalGamesForUpdate>=1) {  ?>
    <meta http-equiv="refresh" content="<?=(int) $_GET["refresh"]?>" />
<?php } ?>
<?php if (isset($_GET["reset"])) {  ?>
    <meta http-equiv="refresh" content="2" />
<?php } ?>
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta name="author" content="Ivan Antonijevic" />
	<meta name="rating" content="Safe For Kids" />
 	<meta name="description" content="<?=$HomeDesc?>" />
	<meta name="keywords" content="<?=$HomeKeywords?>" />
	<title><?=$HomeTitle?></title>
	<link rel="stylesheet" href="<?=$website?>css/style.css" type="text/css" />
	
</head>
  
<body>
<div id="wrapper">
<div id="logo">
  <h1>YOUR LOGO</h1>
</div>  

<div class="mainmenu">
    <a class="menuButtons" href="<?=$website?>adm">Dashboard</a>  
    <a class="menuButtons" href="<?=$website?>adm/?games">Games</a> 
	<a class="menuButtons" href="<?=$website?>adm/?posts">Posts</a> 
    <a class="menuButtons" href="<?=$website?>adm/?users">Users</a>   
    <a class="menuButtons" href="<?=$website?>adm/?bans"><?=$lang["bans"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?heroes"><?=$lang["heroes"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?items"><?=$lang["items"]?></a> 
    <a class="menuButtons" href="<?=$website?>adm/?admins"><?=$lang["admins"]?></a> 
	<?php if ($SafelistPage == 1) { ?>
    <a class="menuButtons" href="<?=$website?>adm/?safelist"><?=$lang["safelist"]?></a> 
	<?php } ?>
	<a class="menuButtons" href="<?=$website?>">Go to OS&raquo; </a> 
	
	<a class="menuButtons" href="<?=$website?>adm/?logout"><?=$_SESSION["username"]?> (logout)</a> 
</div>
   <?php if ($TotalGamesForUpdate>=1) { ?>
   Unranked Games: <?=$TotalGamesForUpdate?>
   <a class="menuButtons" href="<?=$website?>adm/update_stats.php?start&amp;refresh=5">Update ALL</a>
   <a class="menuButtons" href="<?=$website?>adm/update_stats.php?reset">Reset ALL stats</a>
   <?php
   if ( isset($return)  AND !empty($return) ) { ?>
   <div style="margin-top: 16px;">
   <textarea style="width: 400px; height: 290px;"><?=$return?></textarea>
   </div>
   <?php } ?>
   <?php } else { ?>
   <div>Unranked Games: <?=$TotalGamesForUpdate?></div>
   <h2>There is no games for update.</h2>
   <div class="padTop"></div>
   <a class="menuButtons" href="<?=$website?>adm/update_stats.php?reset">Reset ALL stats</a>
   <?php } ?>
</div>
<div style="margin-top: 320px;">&nbsp;</div>
<?php
include('../footer.php');
?>
<?php } ?>