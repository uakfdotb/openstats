<?php  

if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) {header('HTTP/1.1 404 Not Found'); die; }
	
if (!isset($_SESSION)) {session_start();}

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'ghost';

$website = 'http://localhost/openstats/';
$HomeTitle = 'DotA OpenStats 3.0';
$HomeDesc = 'DotA OpenStats 3.0';
$HomeKeywords = 'dota, games, heroes, players, best players, top list, top players, statistics';

$default_language = 'english';
$DateFormat = 'd.m.Y, H:i';
$DefaultStyle = 'default';

$HeroFileExt = 'gif';

$GamesPerPage = '20';
$TopPlayersPerPage = '50';
$CommentsPerPage = '10';

//When user register: 1 - user must confirm registration via email, 0 - instant activation
$UserActivation = '1';

$MaxPaginationLinks = '3';

$TopPage       = '1';
$HeroesPage    = '1';
$ItemsPage     = '1';
$BansPage      = '1';
$WarnPage      = '1';
$AdminsPage    = '1';
$SafelistPage  = '1';

//Minimum game duration > 5*60 = 5 min (or 300 sec) 
//Only games with defined time (longer then $MinDuration ) will be counted in the statistics

$MinDuration = 5*60;

//Enable/disable info about page generation and total queries on every page
$pageGen = '1'; 
//Enable error reportings
$_debug = '1'; 

$OS_INSTALLED = '0';

?>
