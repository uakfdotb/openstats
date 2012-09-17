<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
  //GAMES
  if ( isset($_GET["games"]) AND isset($GamesData) AND !empty($GamesData) )
  include('themes/'.$DefaultStyle.'/games.php');
 
  //SINGLE GAME
  if ( isset($_GET["game"]) AND isset($GameData) AND !empty($GameData) ) 
  include('themes/'.$DefaultStyle.'/single_game.php');
 
  
  //TOP STATS
  if ( isset($_GET["top"]) AND isset($TopData) AND !empty($TopData) ) {
  include('themes/'.$DefaultStyle.'/top.php');
  }
  
  //USER DATA
  if ( isset($_GET["u"]) AND isset($UserData) AND !empty($UserData) ) {
  include('themes/'.$DefaultStyle.'/single_user.php');
  }
  
  //SEARCH
  if ( isset($_GET["search"]) AND isset($s) ) {
  include('themes/'.$DefaultStyle.'/search.php');
  }
  
  //BANS
  if ( isset($_GET["bans"]) AND isset($BansData) ) {
  include('themes/'.$DefaultStyle.'/bans.php');
  }
  
  //ADMINS
  if ( isset( $_GET["admins"]) AND $AdminsPage == 1 AND isset($AdminsData) AND !empty($AdminsData) ) {
  include('themes/'.$DefaultStyle.'/admins.php');
  }

  //WARN
  if ( isset($_GET["warn"]) AND isset($BansData) ) {
  include('themes/'.$DefaultStyle.'/warn.php');
  }

  //Safelist
  if ( isset($_GET["safelist"]) AND isset($SafelistData) AND $SafelistPage == 1) {
  include('themes/'.$DefaultStyle.'/safelist.php');
  }
  
  if ( isset( $NewsData ) AND !empty( $NewsData ) ) {
  include('themes/'.$DefaultStyle.'/news.php');
  }
  
  //LOGIN
  if ( isset($_GET["login"]) AND !is_logged() ) {
  include('themes/'.$DefaultStyle.'/login.php');
  }
  
  if ( isset($_GET["404"]) ) {
  include('themes/'.$DefaultStyle.'/404.php');
   } ?>