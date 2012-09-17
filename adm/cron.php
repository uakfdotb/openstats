<?php

//Call this file only from server
if ($_SERVER["REMOTE_ADDR"] != $_SERVER["SERVER_ADDR"]) die("Invalid Request"); 
    
	include("../config.php");
	include("../inc/common.php");
    include("../inc/class.database.php");
	include("../inc/db_connect.php");

	$updateGames = 20;
	
	$ScoreStart = '1000';
	$ScoreWins = '5';
	$ScoreLosses = '-3';
	$ScoreDisc = '-10';

	
	$result = $db->query( "SELECT COUNT(*) FROM games 
	WHERE map LIKE '%dota%' AND stats = 0 AND duration>='".$MinDuration."'" );
    $r = $db->fetch_row($result);
    $Total = $r[0];
	
	if ( $Total>=1 ) {
	
	$_result = $db->query( "SELECT id FROM games 
	WHERE map LIKE '%dota%' AND stats = 0 AND duration>='".$MinDuration."' LIMIT ".$updateGames." " );
	
	while ($row = $db->fetch_array($_result,'assoc')) {
	$gid = $row["id"];
	}
	
  }
?>