<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

if ( isset($_GET["logout"]) AND is_logged() ) {
  os_logout();
  header("location: ".$website.""); die;
}

//Activation - login&code=$code&e=$email
if ( !is_logged() AND isset($_GET["login"]) AND isset($_GET["code"]) AND isset($_GET["e"]) ) {
   $code = safeEscape( $_GET["code"]);
   $e = $_GET["e"];
   $errors = "";
   
   if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $e)) 
   $errors.="<div>".$lang["error_email"]."</div>";
   if ( empty($errors) ) {
   $result = $db->query("SELECT * FROM users WHERE user_email ='".$e."' AND code = '".$code."'");
   
   if ( $db->num_rows($result)>=1 ) {
     $update = $db->query("UPDATE users SET code = '' WHERE user_email ='".$e."' AND code = '".$code."' LIMIT 1");
	 $errors.="<div>Account successfully activated. Now you can login.</div>"; //Not error...just a message
   } else $errors.="<div>Link is not valid or expired</div>";
   
   }
}


//LOGIN
if ( isset( $_GET["login"]) AND !is_logged() AND isset($_POST["login_"] ) ) {
   $email = safeEscape( $_POST["login_email"]);
   $password = safeEscape( $_POST["login_pw"]);
   $errors = "";
   if ( strlen($password)<=2 ) $errors.="<div>".$lang["error_short_pw"]."</div>";
   
   if ( empty($errors) ) {
      $result = $db->query("SELECT * FROM users WHERE user_email = '".$email."' LIMIT 1");
	  if ( $db->num_rows($result)>=1 ) {
	  
	  $row = $db->fetch_array($result,'assoc');
	  $CheckPW = generate_password($password, $row["password_hash"]);
	  
	  if (!empty($row["code"]) ) $errors.="<div>".$lang["error_inactive_acc"]."</div>";
	  
	  if ( $CheckPW == $row["user_password"] AND empty($errors)) {
	  $_SESSION["user_id"] = $row["user_id"];
	  $_SESSION["username"] = $row["user_name"];
	  $_SESSION["email"]    = $row["user_email"];
	  $_SESSION["level"]    = $row["user_level"];
	  $_SESSION["can_comment"]    = $row["can_comment"];
	  $_SESSION["logged"]    = time();
	  header("location: ".$website."");
	  }
	  
	 }  else $errors.="<div>".$lang["error_invalid_login"]."</div>";
   }
}

//REGISTER
if ( isset( $_GET["login"]) AND !is_logged() AND isset($_POST["register_"] ) ) {
   $username = safeEscape( trim($_POST["reg_un"]));
   $email = safeEscape( trim($_POST["reg_email"]));
   $password = safeEscape( $_POST["reg_pw"]);
   $password2 = safeEscape( $_POST["reg_pw2"]);
   $registration_errors = "";
   if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $email)) 
   $registration_errors.="<div>E-mail address is not valid</div>";
   if ( strlen($username)<=2 )  $registration_errors.="<div>".$lang["error_short_un"]."</div>";
   if ( strlen($password)<=2 )  $registration_errors.="<div>".$lang["error_short_pw"]."</div>";
   if ( $password!=$password2 ) $registration_errors.="<div>".$lang["error_passwords"]."</div>";
   
   if ( empty($registration_errors) ) {
     $result = $db->query("SELECT COUNT(*) FROM users WHERE LOWER(user_name) = LOWER('".$username."') ");
	 $r = $db->fetch_row($result);
     if ( $r[0] >=1 )
	 $registration_errors.="<div>".$lang["error_un_taken"]."</div>";
	 
     $result = $db->query("SELECT COUNT(*) FROM users WHERE LOWER(user_email) = LOWER('".$email."') ");
	 $r = $db->fetch_row($result);
     if ( $r[0] >=1 )
	 $registration_errors.="<div>".$lang["error_email_taken"]."</div>";
	 
	  if ( empty($registration_errors) ) {
	  
	  $hash = generate_hash(16,1);
	  $password_db = generate_password($password, $hash);
	  
	  if ($UserActivation == 1) $code = generate_hash(16,1); else $code = '';
	  
	  $result = $db->query("INSERT INTO users(user_name, user_password, password_hash, user_email, user_joined, user_level, user_ip, can_comment, code) VALUES('".$username."', '".$password_db."', '".$hash."', '".$email."', '".(int) time()."', '0', '".EscapeStr($_SERVER["REMOTE_ADDR"])."', '1', '".$code."' ) ");
	  
	  //SEND EMAIL
	  if ($UserActivation == 1) {
	  	    $message = $lang["email_activation1"]." $username,<br />";
	        $message.= $lang["email_activation2"]." $website <br />";
			$message.= $lang["email_activation3"]."<br />";
			$message.= $website."?login&code=$code&e=$email<br />";
	        $message.="------------------------------------------<br />";
	        $message.="$website<br />";
	 
		    //$send_mail = mail($email, "Account Activation", $message, $headers);
			require("inc/class.phpmailer.php");
	        $mail  = new PHPMailer();
			$mail->CharSet = $lang["email_charset"];
			$mail->SetFrom($lang["email_from"], $lang["email_from_full"]);
			$mail->AddReplyTo($lang["email_from"], $lang["email_from_full"]);
			$mail->AddAddress($email, "");
			$mail->Subject = $lang["email_subject_activation"];
			$mail->MsgHTML($message);
			$mail->Send();
	       }
	  
	  }
   }
}


//DELETE COMMENT - ADMIN
if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 AND isset($_GET["delete_comment"]) AND isset($_GET["post_id"]) ) { 
   $id = safeEscape( (int) $_GET["delete_comment"] );
   $pid = safeEscape( (int) $_GET["post_id"] );
   
   $del = $db->query("DELETE FROM comments WHERE id = '".$id."' AND page_id = '".$pid."' LIMIT 1");
   $get = $db->query("SELECT COUNT(*) FROM comments WHERE page_id= '".$pid."' LIMIT 1");
   $r = $db->fetch_row($get);
   $TotalComments = $r[0];
   $update = $db->query("UPDATE news SET comments = '".$TotalComments."' WHERE news_id = '".$pid."' ");
   
   header('location: '.$website.'?post_id='.$pid.'#comments'); die;
}

  if ( isset($_POST["add_comment"]) AND is_logged() AND isset($_GET["post_id"]) AND is_numeric($_GET["post_id"]) AND isset($_SESSION["code"]) AND isset($_POST["code"]) AND isset($_POST["pid"]) ) {
  $id = safeEscape( (int) $_GET["post_id"]);
  $text = my_nl2br( trim($_POST["post_comment"]) );
  //$text = str_replace("\n", "<br />", $text);
  $text = nl2br($text);
  $text = EscapeStr( ($text) );
  $text = (($text));
  $errors = "";
  
  if ( $_SESSION["code"] != $_POST["code"])  $errors.="<div>Invalid form</div>";
  if ( $_POST["pid"] != $id )                $errors.="<div>Invalid post</div>";
  if ( strlen($text)<=3 )   $errors.="<div>Text does not have enough characters</div>";
  
  
  if ( empty($errors) ) {
     $result = $db->query("INSERT INTO comments(user_id, page, page_id, text, date, user_ip) 
	 VALUES('".$_SESSION["user_id"]."', 'news', '".(int) $id."', '".$text."', '".time()."', '".$_SERVER["REMOTE_ADDR"]."')");
	 
	 $get = $db->query("SELECT COUNT(*) FROM comments WHERE page_id= '".$id."' LIMIT 1");
	 $r = $db->fetch_row($get);
     $TotalComments = $r[0];
	 
	 if ( $result ) {
	    header("location: ".$website."?post_id=".$id.""); die;
	 }
  }
  
  }

  //GAMES
  if ( isset($_GET["games"]) OR isset($_GET["u"]) ) {
  
	  
  if ( (isset($_GET["uid"]) AND is_numeric($_GET["uid"])) OR isset($_GET["u"])  ) {
     
	 if ( isset($_GET["u"]) ) $id = safeEscape( (int) $_GET["u"] );
	 else
	 $id = safeEscape( (int) $_GET["uid"] );

  $result = $db->query("SELECT COUNT(*) 
	 FROM stats as s 
	 LEFT JOIN gameplayers as gp ON LOWER(gp.name) = LOWER(s.player)
	 LEFT JOIN games as g ON g.id = gp.gameid
	 LEFT JOIN dotagames as dg ON g.id = dg.gameid 
	 WHERE s.id = '".$id."' AND g.map LIKE '%dota%' AND g.duration>='".$MinDuration."' LIMIT 1");
  
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $GamesPerPage;
  $draw_pagination = 0;
  include('inc/pagination.php');
  $draw_pagination = 1;
	 
	$sql = getUserGames ($id, $MinDuration, $offset, $rowsperpage );
	 
  }
  else   
  {
  $result = $db->query("SELECT COUNT(*) FROM games 
  WHERE map LIKE '%dota%' AND duration>='".$MinDuration."' LIMIT 1");
  
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $GamesPerPage;
  $draw_pagination = 0;
  include('inc/pagination.php');
  $draw_pagination = 1;
	  
   $sql = getAllGames($MinDuration, $offset, $rowsperpage );
	  
    }
  
  $result = $db->query( $sql  );
		  
	$c=0;
    $GamesData = array();
	
	while ($row = $db->fetch_array($result,'assoc')) {
	$GamesData[$c]["id"]        = (int)($row["id"]);
	$GamesData[$c]["map"]  = convEnt2(substr($row["map"], strripos($row["map"], '\\')+1));
	$GamesData[$c]["map"] = reset( explode(".w", $GamesData[$c]["map"] ) );
	$GamesData[$c]["map"] = substr($GamesData[$c]["map"],0,20);
	$GamesData[$c]["datetime"]  = ($row["datetime"]);
	$GamesData[$c]["gamename"]  = ($row["gamename"]);
	$GamesData[$c]["ownername"]  = ($row["ownername"]);
	$GamesData[$c]["duration"]  = ($row["duration"]);
	$GamesData[$c]["creatorname"]  = ($row["creatorname"]);
	$GamesData[$c]["winner"]  = ($row["winner"]);
	$GamesData[$c]["type"]  = ($row["type"]);
	
	if (isset($row["hero"]) )         
	{
	$GamesData[$c]["hero"]  = strtoupper($row["hero"]);   
    if ( empty($row["hero"])  ) $GamesData[$c]["hero"] = "blank";
	
	if ( !file_exists("img/heroes/".$GamesData[$c]["hero"].".gif") ) $GamesData[$c]["hero"]  = "blank";
	
	}
	else $GamesData[$c]["hero"]  = "blank";
	if (isset($row["kills"]) )        $GamesData[$c]["kills"]  = ($row["kills"]);   else $GamesData[$c]["kills"]  = "0";
	if (isset($row["deaths"]) )       $GamesData[$c]["deaths"]  = ($row["deaths"]); else $GamesData[$c]["deaths"]  = "0";
	if (isset($row["creepkills"]) )   $GamesData[$c]["creepkills"]  = ($row["creepkills"]);   else $GamesData[$c]["creepkills"]  = "0";
	if (isset($row["creepdenies"]) )  $GamesData[$c]["creepdenies"]  = ($row["creepdenies"]); else $GamesData[$c]["creepdenies"]  = "0";
	if (isset($row["assists"]) )      $GamesData[$c]["assists"]  = ($row["assists"]);       else $GamesData[$c]["assists"]  = "0";
	if (isset($row["neutralkills"]) ) $GamesData[$c]["neutrals"]  = ($row["neutralkills"]); else $GamesData[$c]["neutrals"]  = "0";
	
	if (isset($row["player"]) ) $GamesData[$c]["player"]  = ($row["player"]);
	$c++;
	}
	$db->free($result);	
  }
  
  // ----- > END GAMES
  
  //SINGLE GAME
  if ( isset($_GET["game"]) AND is_numeric($_GET["game"]) ) {
     $gameid = safeEscape( (int) $_GET["game"]);
	 $c=0;
     $GameData = array();
	 
	 $result = $db->query(  getSingleGame( (int)$gameid ) );
	 
	 if ( $db->num_rows($result)<=0 ) { header('location: '.$website.'?404'); die; }
	 
	 $row = $db->fetch_array($result,'assoc');
	 
	 $GameData[$c]["creatorname"]  = ($row["creatorname"]);
	 $GameData[$c]["duration"]  = secondsToTime($row["duration"]);
	 $GameData[$c]["datetime"]  = date($DateFormat,strtotime($row["datetime"]));
	 $GameData[$c]["dt"]  = ($row["datetime"]);
	 $GameData[$c]["gamename"]  = ($row["gamename"]);
	 $GameData[$c]["winner"]  = ($row["winner"]);
	 //SET META INFORMATION AND PAGE NAME
	 $HomeTitle = ($row["gamename"]);
	 $HomeDesc = strip_quotes($row["gamename"]);
	 $HomeKeywords = strtolower( strip_quotes($row["gamename"])).','.$HomeKeywords;
	 
	 $db->free($result);
	 
	 $temp_points = 0;

	 if ( file_exists("inc/geoip/geoip.inc") ) {
	 include("inc/geoip/geoip.inc");
	 $GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	 $GeoIP = 1;
	 }
	 
	 $result = $db->query(  getGameInfo($gameid)  );
	 while ($row = $db->fetch_array($result,'assoc')) {
	if ($GeoIP == 1 ) {
	$GameData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	$GameData[$c]["country"]  = geoip_country_name_by_addr($GeoIPDatabase, $row["ip"]);
	}
	if ($GeoIP == 1 AND empty($GameData[$c]["letter"]) ) {
	$GameData[$c]["letter"]  = "blank";
	$GameData[$c]["country"] = "Reserved";
	}
	 
	 
     $GameData[$c]["kills"]  = ($row["kills"]);
	 $GameData[$c]["deaths"]  = ($row["deaths"]);
	 $GameData[$c]["assists"]  = ($row["assists"]);
	 $GameData[$c]["creepkills"]  = ($row["creepkills"]);
	 $GameData[$c]["creepdenies"]  = ($row["creepdenies"]);
	 $GameData[$c]["neutralkills"]  = ($row["neutralkills"]);
	 $GameData[$c]["towerkills"]  = ($row["towerkills"]);
	 $GameData[$c]["raxkills"]  = ($row["raxkills"]);
	 $GameData[$c]["courierkills"]  = ($row["courierkills"]);
	 $GameData[$c]["spoofedrealm"]  = ($row["spoofedrealm"]);
	 $GameData[$c]["gold"]  = ($row["gold"]);
	 $GameData[$c]["item1"]  = ($row["item1"]);
	 $GameData[$c]["item2"]  = ($row["item2"]);
	 $GameData[$c]["item3"]  = ($row["item3"]);
	 $GameData[$c]["item4"]  = ($row["item4"]);
	 $GameData[$c]["item5"]  = ($row["item5"]);
	 $GameData[$c]["item6"]  = ($row["item6"]);
	 
	 $GameData[$c]["itemname1"]  = strip_quotes($row["itemname1"]);
	 $GameData[$c]["itemname2"]  = strip_quotes($row["itemname2"]);
	 $GameData[$c]["itemname3"]  = strip_quotes($row["itemname3"]);
	 $GameData[$c]["itemname4"]  = strip_quotes($row["itemname4"]);
	 $GameData[$c]["itemname5"]  = strip_quotes($row["itemname5"]);
	 $GameData[$c]["itemname6"]  = strip_quotes($row["itemname6"]);
	 
	 $GameData[$c]["description"]  = strip_quotes($row["description"]);
	 
	 if ( !isset($BestPlayer)  ) {
	 $BestPlayer = ($row["name"]);
	 }
	 
	 $score_points = ($row["kills"] -  $row["deaths"]) + ($row["assists"]*0.3);
	 if ( $score_points > $temp_points ) {
	 $BestPlayer = ($row["name"]);
	 $temp_points = $score_points;
	 }
	 
	 if (!empty($row["hero"]) ) $GameData[$c]["hero"]  = ($row["hero"].".$HeroFileExt");
	 else  $GameData[$c]["hero"]  = "blank.gif";
	 
	 if (!empty( $row["itemicon1"] ) ) $GameData[$c]["itemicon1"]  = ($row["itemicon1"]);
	 else $GameData[$c]["itemicon1"] = "empty.gif";
	 
	 if (!empty( $row["itemicon2"] ) ) $GameData[$c]["itemicon2"]  = ($row["itemicon2"]);
	 else $GameData[$c]["itemicon2"] = "empty.gif";
	 if (!empty( $row["itemicon3"] ) ) $GameData[$c]["itemicon3"]  = ($row["itemicon3"]);
	 else $GameData[$c]["itemicon3"] = "empty.gif";
	 if (!empty( $row["itemicon4"] ) ) $GameData[$c]["itemicon4"]  = ($row["itemicon4"]);
	 else $GameData[$c]["itemicon4"] = "empty.gif";
	 if (!empty( $row["itemicon5"] ) ) $GameData[$c]["itemicon5"]  = ($row["itemicon5"]);
	 else $GameData[$c]["itemicon5"] = "empty.gif";
	 if (!empty( $row["itemicon6"] ) ) $GameData[$c]["itemicon6"]  = ($row["itemicon6"]);
	 else $GameData[$c]["itemicon6"] = "empty.gif";
	 
	 $GameData[$c]["left"]  = secondsToTime($row["left"]);
	 $GameData[$c]["leftreason"]  = ($row["leftreason"]);
	 
	 $GameData[$c]["banname"]  = ($row["banname"]);
	 $GameData[$c]["name"]  = ($row["name"]);
	 
	 //CHECK IF USER IS BANNED
	 if ( strtolower($row["name"]) == strtolower($row["banname"]) ) {
	    $GameData[$c]["full_name"]  = '<span class="banned">'.($row["name"])."</span>";
	 } 
	 else 
	 $GameData[$c]["full_name"]  = ($row["name"]);
	 
	 $GameData[$c]["newcolour"]  = ($row["newcolour"]);
	 $GameData[$c]["gameid"]  = ($row["gameid"]);
	 $GameData[$c]["banname"]  = ($row["banname"]);
	 $GameData[$c]["ip"]  = ($row["ip"]);
	 $GameData[$c]["newcolour"]  = ($row["newcolour"]);
	 $c++;
	}
	$db->free($result);	
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
  }
   // ----- > END SINGLE GAME
   
   //TOP STATS
   if ( isset( $_GET["top"]) AND $TopPage==1) {
   	 //SET META INFORMATION AND PAGE NAME
	 $HomeTitle = $lang["top"];
	 $HomeDesc = $lang["top"];
	 //$HomeKeywords = strtolower($row["gamename"]).','.$HomeKeywords;
   
   $orderby = "`score` DESC";
   
   if ( isset($_GET["sort"]) ) {
     if ( $_GET["sort"] == "score") $orderby = "`score` DESC";
	 if ( $_GET["sort"] == "player_name") $orderby = "LOWER(`player`) ASC";
	 if ( $_GET["sort"] == "games") $orderby = "(`games`) DESC";
	 if ( $_GET["sort"] == "wins") $orderby = "(`wins`) DESC";
	 if ( $_GET["sort"] == "losses") $orderby = "(`losses`) DESC";
	 if ( $_GET["sort"] == "draw") $orderby = "(`draw`) DESC";
	 if ( $_GET["sort"] == "kills") $orderby = "(`kills`) DESC";
	 if ( $_GET["sort"] == "deaths") $orderby = "(`deaths`) DESC";
	 if ( $_GET["sort"] == "assists") $orderby = "(`assists`) DESC";
	 if ( $_GET["sort"] == "ck") $orderby = "(`creeps`) DESC";
	 if ( $_GET["sort"] == "cd") $orderby = "(`denies`) DESC";
	 if ( $_GET["sort"] == "nk") $orderby = "(`neutrals`) DESC";
   }
   
  $result = $db->query("SELECT COUNT(*) FROM stats WHERE id>=1 LIMIT 1");
  
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $TopPlayersPerPage;
  $draw_pagination = 0;
  include('inc/pagination.php');
  $draw_pagination = 1;
  
  $result = $db->query("SELECT * FROM stats WHERE id>=1 ORDER BY $orderby LIMIT $offset, $rowsperpage");
   
  if ( isset($_GET["page"]) AND is_numeric($_GET["page"]) AND $db->num_rows($result) >=1 ) {
     $HomeTitle.=" | Page ".(int) $_GET["page"];
  }
   
   	$c=0;
    $TopData = array();
	$counter = 0;
	
	if ( isset( $_GET["page"]) AND is_numeric($_GET["page"]) ) {
	  $counter = (($_GET["page"]-1) * $TopPlayersPerPage) ;
	}
	
	if ( file_exists("inc/geoip/geoip.inc") ) {
	include("inc/geoip/geoip.inc");
	$GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	$GeoIP = 1;
	}
	
	while ($row = $db->fetch_array($result,'assoc')) {
	$TopData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	
	if ($GeoIP == 1 AND empty($TopData[$c]["letter"]) ) $TopData[$c]["letter"] = "blank";
	
	$counter++;
	
	$TopData[$c]["counter"]        = $counter;
	$TopData[$c]["id"]        = (int)($row["id"]);
	$TopData[$c]["player"]  = ($row["player"]);
	$TopData[$c]["score"]  = number_format($row["score"],0);
	$TopData[$c]["games"]  = number_format($row["games"],0);
	$TopData[$c]["wins"]  = number_format($row["wins"],0);
	$TopData[$c]["losses"]  = number_format($row["losses"],0);
	$TopData[$c]["draw"]  = number_format($row["draw"],0);
	$TopData[$c]["kills"]  = number_format($row["kills"],0);
	$TopData[$c]["deaths"]  = number_format($row["deaths"],0);
	$TopData[$c]["assists"]  = number_format($row["assists"],0);
	$TopData[$c]["creeps"]  = number_format($row["creeps"],0);
	$TopData[$c]["denies"]  = number_format($row["denies"],0);
	$TopData[$c]["neutrals"]  = number_format($row["neutrals"],0);
	$TopData[$c]["towers"]  = ($row["towers"]);
	$TopData[$c]["rax"]  = ($row["rax"]);
	$TopData[$c]["banned"]  = ($row["banned"]);
	$TopData[$c]["ip"]  = ($row["ip"]);
	
	if ($row["wins"] >0 )
	$TopData[$c]["winslosses"] = ROUND($TopData[$c]["wins"]/($TopData[$c]["wins"]+$TopData[$c]["losses"]), 3)*100;
	else $TopData[$c]["winslosses"] = 0;
		
	//Highlight - sort
	if ( (isset($_GET["sort"]) AND $_GET["sort"] == "score") OR !isset($_GET["sort"]) ) 
	$TopData[$c]["score"] = "<span class='highlight_top'>".$TopData[$c]["score"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "games") 
	$TopData[$c]["games"] = "<span class='highlight_top'>".$TopData[$c]["games"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "wins") 
	$TopData[$c]["wins"] = "<span class='highlight_top'>".$TopData[$c]["wins"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "losses") 
	$TopData[$c]["losses"] = "<span class='highlight_top'>".$TopData[$c]["losses"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "losses") 
	$TopData[$c]["losses"] = "<span class='highlight_top'>".$TopData[$c]["losses"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "draw") 
	$TopData[$c]["draw"] = "<span class='highlight_top'>".$TopData[$c]["draw"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "kills") 
	$TopData[$c]["kills"] = "<span class='highlight_top'>".$TopData[$c]["kills"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "deaths") 
	$TopData[$c]["deaths"] = "<span class='highlight_top'>".$TopData[$c]["deaths"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "assists") 
	$TopData[$c]["assists"] = "<span class='highlight_top'>".$TopData[$c]["assists"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "ck") 
	$TopData[$c]["creeps"] = "<span class='highlight_top'>".$TopData[$c]["creeps"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "cd") 
	$TopData[$c]["denies"] = "<span class='highlight_top'>".$TopData[$c]["denies"]."</span>";
	
	if ( isset($_GET["sort"]) AND $_GET["sort"] == "nk") 
	$TopData[$c]["neutrals"] = "<span class='highlight_top'>".$TopData[$c]["neutrals"]."</span>";
	
	$c++;
	}
	$db->free($result);	
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
	
   }
   
   if ( isset( $_GET["u"]) ) {
   
   $uid = safeEscape( (int) $_GET["u"] );
      
	  if ( !is_numeric( $_GET["u"]) ) {
	  $u = safeEscape( $_GET["u"] );
	  
	  $result = $db->query("SELECT * FROM stats WHERE LOWER(player) = LOWER('".$u."') ");
	  
	  if ( $db->num_rows($result)>=1 ) {
	     $row = $db->fetch_array($result,'assoc');
		 $uid = $row["id"];
		 $db->free($result);
		 header("location: ".$webiste."?u=".$uid.""); die;
	     }
	  }
	  
	$result = $db->query("SELECT s.*, b.name as banname, b.reason, b.admin
	FROM stats as s  
	LEFT JOIN bans as b ON LOWER(b.name) = LOWER(s.player)
	WHERE s.id = '".(int) $uid."' ");
	$c=0;
    $UserData = array();
	
	 if ( file_exists("inc/geoip/geoip.inc") ) {
	 include("inc/geoip/geoip.inc");
	 $GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	 $GeoIP = 1;
	 }
	
	while ($row = $db->fetch_array($result,'assoc')) {
	
	if ( isset($GeoIP) AND $GeoIP == 1) {
	$UserData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	$UserData[$c]["country"]  = geoip_country_name_by_addr($GeoIPDatabase, $row["ip"]);
	}
	if ($GeoIP == 1 AND empty($UserData[$c]["letter"]) ) {
	$UserData[$c]["letter"] = "blank";
	$UserData[$c]["country"]  = "Reserved";
	}
	
	$UserData[$c]["id"]        = (int)($row["id"]);
	$UserData[$c]["player"]   = ($row["player"]);
	$UserData[$c]["banname"]  = ($row["banname"]);
	$UserData[$c]["reason"]  = ($row["reason"]);
	$UserData[$c]["admin"]  = ($row["admin"]);
	$UserData[$c]["score"]  = number_format($row["score"],0);
	$UserData[$c]["games"]  = number_format($row["games"],0);
	$UserData[$c]["wins"]  = number_format($row["wins"],0);
	$UserData[$c]["losses"]  = number_format($row["losses"],0);
	$UserData[$c]["draw"]  = number_format($row["draw"],0);
	$UserData[$c]["kills"]  = number_format($row["kills"],0);
	$UserData[$c]["deaths"]  = number_format($row["deaths"],0);
	$UserData[$c]["assists"]  = number_format($row["assists"],0);
	$UserData[$c]["creeps"]  = number_format($row["creeps"],0);
	$UserData[$c]["denies"]  = number_format($row["denies"],0);
	$UserData[$c]["neutrals"]  = number_format($row["neutrals"],0);
	$UserData[$c]["towers"]  = ($row["towers"]);
	$UserData[$c]["rax"]  = ($row["rax"]);
	$UserData[$c]["banned"]  = ($row["banned"]);
	$UserData[$c]["ip"]  = ($row["ip"]);
	
	//SET META INFORMATION AND PAGE NAME
	 $HomeTitle = ($row["player"]);
	 $HomeDesc = ($row["player"]);
	 $HomeKeywords = strtolower($row["player"]).','.$HomeKeywords;
	
	if ($row["games"]>=1 AND $row["kills"]>=1) {
	$UserData[$c]["kpm"] = ROUND($row["kills"]/$row["games"],2); 
	}
	else $UserData[$c]["kpm"] = 0;
	
	if ($row["games"]>=1 AND $row["deaths"]>=1) {
	$UserData[$c]["dpm"] = ROUND($row["deaths"]/$row["games"],2); 
	}
	else $UserData[$c]["dpm"] = 0;
	
	if ($row["deaths"]>=1) $UserData[$c]["kd"]  = ROUND($row["kills"] / $row["deaths"],2);
    else $UserData[$c]["kd"] = $row["kills"];
	
	if ($row["wins"] >0 )
	$UserData[$c]["winslosses"] = ROUND($UserData[$c]["wins"]/($UserData[$c]["wins"]+$UserData[$c]["losses"]), 3)*100;
	else $UserData[$c]["winslosses"] = 0;
	$c++;
	}
	$db->free($result);	
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
   }
   
   //SEARCH 
   
   if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2  ) {
      $s = safeEscape( $_GET["search"]);
	  $result = $db->query("SELECT COUNT(*) FROM stats WHERE LOWER(player) LIKE LOWER('%".$s."%') LIMIT 1");
	  $r = $db->fetch_row($result);
	  $numrows = $r[0];
	  $result_per_page = $TopPlayersPerPage;
	  $draw_pagination = 0;
	  include('inc/pagination.php');
	  $draw_pagination = 1;
	  
	  
	  $result = $db->query("SELECT * FROM stats WHERE LOWER(player) LIKE LOWER('%".$s."%') 
	  ORDER BY score DESC
	  LIMIT $offset, $rowsperpage");
	  
	$c=0;
    $SearchData = array();
	if ( file_exists("inc/geoip/geoip.inc") ) {
	include("inc/geoip/geoip.inc");
	$GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	$GeoIP = 1;
	}
	while ($row = $db->fetch_array($result,'assoc')) {
	if ( isset($GeoIP) AND $GeoIP == 1) {
	$SearchData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	$SearchData[$c]["country"]  = geoip_country_name_by_addr($GeoIPDatabase, $row["ip"]);
	}
	if ($GeoIP == 1 AND empty($SearchData[$c]["letter"]) ) { 
	$SearchData[$c]["letter"] = "blank";
	$SearchData[$c]["country"]  = "Reserved";
	}
	$SearchData[$c]["id"]        = (int)($row["id"]);
	$SearchData[$c]["player"]  = ($row["player"]);
	$SearchData[$c]["score"]  = number_format($row["score"],0);
	$SearchData[$c]["games"]  = number_format($row["games"],0);
	$SearchData[$c]["wins"]  = number_format($row["wins"],0);
	$SearchData[$c]["losses"]  = number_format($row["losses"],0);
	$SearchData[$c]["draw"]  = number_format($row["draw"],0);
	$SearchData[$c]["kills"]  = number_format($row["kills"],0);
	$SearchData[$c]["deaths"]  = number_format($row["deaths"],0);
	$SearchData[$c]["assists"]  = number_format($row["assists"],0);
	$SearchData[$c]["creeps"]  = number_format($row["creeps"],0);
	$SearchData[$c]["denies"]  = number_format($row["denies"],0);
	$SearchData[$c]["neutrals"]  = number_format($row["neutrals"],0);
	$SearchData[$c]["towers"]  = ($row["towers"]);
	$SearchData[$c]["rax"]  = ($row["rax"]);
	$SearchData[$c]["banned"]  = ($row["banned"]);
	$SearchData[$c]["ip"]  = ($row["ip"]);
	
	$c++;
	}
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
	
	$db->free($result);	
	
   }
   
   //BANS
   if ( isset($_GET["bans"]) AND $BansPage == 1) {
   
     if ( isset($_GET["search_bans"]) AND strlen($_GET["search_bans"])>=2  ) {
	    $search_bans = safeEscape( $_GET["search_bans"]);
		$sql = "AND LOWER(name) LIKE LOWER('%".$search_bans."%') ";
	 } else $sql = "";
   
     $result = $db->query("SELECT COUNT(*) FROM bans WHERE id>=1 $sql LIMIT 1");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $TopPlayersPerPage;
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	  
	 $result = $db->query("SELECT * FROM bans WHERE id>=1 $sql ORDER BY id DESC LIMIT $offset, $rowsperpage");
	 
	 $c=0;
    $BansData = array();
	if ( file_exists("inc/geoip/geoip.inc") ) {
	include("inc/geoip/geoip.inc");
	$GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	$GeoIP = 1;
	}
	while ($row = $db->fetch_array($result,'assoc')) {
	if ( isset($GeoIP) AND $GeoIP == 1) {
	$BansData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	$BansData[$c]["country"]  = geoip_country_name_by_addr($GeoIPDatabase, $row["ip"]);
	}
	if ($GeoIP == 1 AND empty($BansData[$c]["letter"]) ) {
	$BansData[$c]["letter"] = "blank";
	$BansData[$c]["country"]  = "Reserved";
	}
	
	$BansData[$c]["id"]        = (int)($row["id"]);
	$BansData[$c]["server"]  = ($row["server"]);
	$BansData[$c]["name"]  = ($row["name"]);
	$BansData[$c]["ip"]  = ($row["ip"]);
	$BansData[$c]["date"]  = date($DateFormat, strtotime($row["date"]));
	$BansData[$c]["gamename"]  = ($row["gamename"]);
	$BansData[$c]["admin"]  = ($row["admin"]);
	$BansData[$c]["reason"]  = stripslashes($row["reason"]);
	//$BansData[$c]["expiredate"]  = ($row["expiredate"]);
	//$BansData[$c]["warn"]  = ($row["warn"]);
	$c++;
	}
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
	
	$db->free($result);	
   
   }
   
   //A D M I N S
   
   if ( isset( $_GET["admins"]) AND $AdminsPage == 1 ) {
    
	 $result = $db->query("SELECT COUNT(*) FROM admins WHERE id>=1 LIMIT 1");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $TopPlayersPerPage;
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 
	 $c=0;
    $AdminsData = array();
	
	$result = $db->query("SELECT * FROM admins WHERE id>=1 ORDER BY id DESC LIMIT $offset, $rowsperpage");
	
	while ($row = $db->fetch_array($result,'assoc')) {
	$AdminsData[$c]["id"]        = (int)($row["id"]);
	$AdminsData[$c]["name"]  = ($row["name"]);
	$AdminsData[$c]["server"]  = ($row["server"]);
	$c++;
	}
	$db->free($result);	
    
   }
   
   
   
   //WARN
   if ( isset($_GET["warn"]) AND $WarnPage == 1) {
   
     if ( isset($_GET["search_bans"]) AND strlen($_GET["search_bans"])>=2  ) {
	    $search_bans = safeEscape( $_GET["search_bans"]);
		$sql = "AND LOWER(name) LIKE LOWER('%".$search_bans."%') ";
	 } else $sql = "";
   
     $result = $db->query("SELECT COUNT(*) FROM bans WHERE id>=1 AND warn = 1 $sql LIMIT 1");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $TopPlayersPerPage;
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	  
	 $result = $db->query("SELECT * FROM bans WHERE id>=1 $sql AND warn = 1 LIMIT $offset, $rowsperpage");
	 
	 $c=0;
    $BansData = array();
	if ( file_exists("inc/geoip/geoip.inc") ) {
	include("inc/geoip/geoip.inc");
	$GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	$GeoIP = 1;
	}
	while ($row = $db->fetch_array($result,'assoc')) {
	if ( isset($GeoIP) AND $GeoIP == 1)
	$BansData[$c]["letter"]   = geoip_country_code_by_addr($GeoIPDatabase, $row["ip"]);
	if ($GeoIP == 1 AND empty($BansData[$c]["letter"]) ) $BansData[$c]["letter"] = "blank";
	$BansData[$c]["id"]        = (int)($row["id"]);
	$BansData[$c]["server"]  = ($row["server"]);
	$BansData[$c]["name"]  = ($row["name"]);
	$BansData[$c]["ip"]  = ($row["ip"]);
	$BansData[$c]["date"]  = date($DateFormat, strtotime($row["date"]));
	$BansData[$c]["gamename"]  = ($row["gamename"]);
	$BansData[$c]["admin"]  = ($row["admin"]);
	$BansData[$c]["reason"]  = ($row["reason"]);
	//$BansData[$c]["expiredate"]  = ($row["expiredate"]);
	//$BansData[$c]["warn"]  = ($row["warn"]);
	$c++;
	}
	if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
	
	$db->free($result);	
   
   }
   
   
   
   //SAFELIST
   if ( isset($_GET["safelist"]) AND $SafelistPage == 1) {

     $result = $db->query("SELECT COUNT(*) FROM  safelist WHERE id>=1 LIMIT 1");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $TopPlayersPerPage;
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	  
	 $result = $db->query("SELECT * FROM  safelist WHERE id>=1 LIMIT $offset, $rowsperpage");
	 
	 $c=0;
     $SafelistData = array();

	while ($row = $db->fetch_array($result,'assoc')) {
	$SafelistData[$c]["id"]        = (int)($row["id"]);
	$SafelistData[$c]["server"]  = ($row["server"]);
	$SafelistData[$c]["name"]  = ($row["name"]);
	$SafelistData[$c]["voucher"]  = ($row["voucher"]);
	$c++;
	}	
	$db->free($result);	
   
   }
   //HOME PAGE
   if ( !$_GET OR ( !isset($_GET["top"]) AND !isset( $_GET["games"] ) AND !isset( $_GET["game"] ) AND !isset( $_GET["bans"] ) AND !isset( $_GET["warn"]) AND !isset( $_GET["admins"] ) AND !isset( $_GET["safelist"]) AND !isset( $_GET["search"])  AND !isset( $_GET["search_bans"]) AND !isset( $_GET["u"]) AND !isset( $_GET["uid"]) AND !isset($_GET["login"])  ) ) {
   
     $HOME_PAGE = 1;
	 
	 if ( isset($_GET["post_id"]) AND is_numeric($_GET["post_id"]) ) {
	 
	 $id = safeEscape( (int) $_GET["post_id"]);
	 $sql = " AND news_id = '".$id."' ";
	//GET COMMENTS
	 $result = $db->query("SELECT COUNT(*) FROM comments WHERE page_id='".$id."'");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $CommentsPerPage;
	 $draw_pagination = 0;
	 $total_comments  = $numrows;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 
	 $CommentOrder = "id DESC";
	 
	  $result = $db->query("SELECT c.*, u.user_name
	  FROM  comments as c
	  LEFT JOIN users as u ON u.user_id = c.user_id
	  WHERE c.page_id='".$id."' ORDER BY c.$CommentOrder LIMIT $offset, $rowsperpage");
	  $c=0;
     $CommentsData = array();
	 
	 while ($row = $db->fetch_array($result,'assoc')) {
	$CommentsData[$c]["id"]        = (int)($row["id"]);
	$CommentsData[$c]["username"]  = ($row["user_name"]);
	$CommentsData[$c]["user_id"]  = ($row["user_id"]);
	$CommentsData[$c]["post_id"]  = ($row["page_id"]);
	$CommentsData[$c]["text"]  = convEnt($row["text"]);
	$CommentsData[$c]["date"]  = date($DateFormat, $row["date"]);
	$CommentsData[$c]["user_ip"]  = ($row["user_ip"]);
	$c++;
	}	
	$db->free($result);	
		
		
	 } else $sql = "";
	 
	 $result = $db->query("SELECT COUNT(*) FROM news WHERE news_id>=1 $sql LIMIT 1");
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = 5;
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 
	 $result = $db->query("SELECT news_id, news_title, news_content, news_date, COUNT(comments.id) FROM news LEFT JOIN comments ON comments.page_id = news_id WHERE news_id>=1 AND status=1 $sql GROUP BY news_id ORDER BY news_id DESC 
	 LIMIT $offset, $rowsperpage");
	 $c=0;
     $NewsData = array();
	 
	while ($row = $db->fetch_array($result,'assoc')) {
	$NewsData[$c]["id"]        = (int)($row["news_id"]);
	$NewsData[$c]["title"]  = ($row["news_title"]);
	$NewsData[$c]["text"]  = convEnt($row["news_content"]);
	//$NewsData[$c]["text"]  = str_replace("\n","<br />", $NewsData[$c]["text"]);
	$NewsData[$c]["date"]  = ($row["news_date"]);
	$NewsData[$c]["comments"]  = ($row["COUNT(comments.id)"]);
	$c++;
	}	
	$db->free($result);	
	
   }
?>
