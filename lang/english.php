<?php
if ( !isset($website ) ) { header('HTTP/1.1 404 Not Found'); die; }

$lang = array();

$lang["home"]       = "Home";
$lang["top"]        = "Top Players";
$lang["game_archive"]  = "Games History";
$lang["heroes"]     = "Heroes";
$lang["items"]      = "Items";
$lang["bans"]       = "Bans";
$lang["warn"]       = "Warn";
$lang["search"]     = "Search";
$lang["admins"]     = "Admins";
$lang["safelist"]   = "Safelist";

$lang["game"]       = "Game";
$lang["duration"]   = "Duration";
$lang["type"]       = "Type";
$lang["date"]       = "Date";
$lang["map"]        = "Map";
$lang["creator"]    = "Creator";

$lang["hero"]    = "Hero";
$lang["player"]  = "Player";
$lang["kda"]     = "K/D/A";
$lang["cdn"]     = "C/D/N";
$lang["trc"]     = "T/R/C";
$lang["gold"]    = "Gold";
$lang["left"]    = "Left";

$lang["sent_winner"]    = "Sentinel Winner";
$lang["scou_winner"]    = "Scourge Winner";
$lang["sent_loser"]     = "Sentinel Loser";
$lang["scou_loser"]     = "Scourge Loser";
$lang["draw_game"]      = "Draw Game";

$lang["score"]    = "Score";
$lang["games"]    = "Games";
$lang["wld"]     = "W/L/D";
$lang["wl"]     = "W/L";
$lang["tr"]     = "T/R";

$lang["sortby"]     = "Sort by:";

$lang["wins"]       = "Wins";
$lang["losses"]     = "Losses";
$lang["draw"]       = "Draw";

$lang["kills"]       = "Kills";
$lang["player_name"] = "Player name";
$lang["deaths"]      = "Deaths";
$lang["assists"]     = "Assists";
$lang["ck"]          = "Creep Kills";
$lang["cd"]          = "Creep Denies";
$lang["nk"]          = "Neutral Kills";
$lang["towers"]      = "Towers";
$lang["rax"]         = "Rax";
$lang["neutrals"]    = "Neutrals";

$lang["submit"]          = "Submit";

$lang["page"]          = "Page";
$lang["pageof"]        = "of";
$lang["total"]         = "total";

$lang["game_history"]         = "Game History:";
$lang["user_game_history"]    = "User Game History";
$lang["best_player"]          = "Best Player: ";

$lang["win_percent"]          = "Win %";
$lang["wl_percent"]           = "W/L%";
$lang["kd_ratio"]             = "K/D Ratio";
$lang["kpm"]                  = "KPM";
$lang["dpm"]                  = "DPM";

$lang["search_results"]       = "Search results for: ";
$lang["user_not_found"]       = "User not found";

$lang["admins"]      = "Admins";
$lang["admin"]       = "Admin";
$lang["server"]      = "Server";
$lang["voucher"]     = "Voucher";

$lang["banned"]     = "BANNED";
$lang["reason"]     = "Reason";
$lang["game_name"]  = "Game Name";
$lang["bannedby"]   = "Banned by";

$lang["comments"]             = "Comments";
$lang["add_comment"]          = "Add Comment";
$lang["add_comment_button"]   = "Add Comment";

//Login / Registration
$lang["login"]   = "Login";
$lang["email"]   = "E-mail";
$lang["password"]   = "Password";

$lang["register"]   = "Register";
$lang["username"]   = "Username";
$lang["confirm_password"]   = "Confirm Password";

$lang["comment_not_logged"]   = "You need to be logged in to post a comment.";

//Errors
$lang["error_email"]      = "E-mail address is not valid";
$lang["error_short_pw"]   = "Field Password does not have enough characters";
$lang["error_passwords"]  = "Password and confirmation password do not match";
$lang["error_inactive_acc"]   = "Account is not activated yet";
$lang["error_invalid_login"]  = "Invalid e-mail or password";
$lang["error_short_un"]   = "Field Username does not have enough characters";
$lang["error_un_taken"]   = "Username already taken";
$lang["error_email_taken"]= "E-mail already taken";

//Email
$lang["email_charset"] = "UTF-8";
$lang["email_subject_activation"] = "Account Activation";
$lang["email_from"] = "no_reply@openstats.iz.rs";
$lang["email_from_full"] = "OpenStats";

//Email text
$lang["email_activation1"] = "Hello";
$lang["email_activation2"] = "You have successfully registered to the site ";
$lang["email_activation3"] = "Click on the following link to confirm your email address and activate your account";
?>