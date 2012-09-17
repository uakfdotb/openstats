<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

$errors = "";

if ( isset($_GET["logout"]) AND is_logged() ) {
os_logout();
header("location: ".$website."adm/");
die;
}

  if ( isset($_POST["login_"])  ) {
    
	$email    = $_POST["login_email"];
	$password = $_POST["login_password"];
	
	if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $email)) 
	$errors.="<div>Invalid e-mail or password</div>";
	
	if ( empty($errors)  ) {
	  $result = $db->query("SELECT * FROM users WHERE user_email = '".$email."' LIMIT 1");
	  
	  if ( $db->num_rows($result)>=1 ) {
	  $row = $db->fetch_array($result,'assoc');
	  $CheckPW = generate_password($password, $row["password_hash"]);
	  if (!empty($row["code"]) ) $errors.="<div>Account is not activated yet</div>";
	  
	  if ($row["user_password"] == $CheckPW AND empty($errors) ) {
	  $_SESSION["user_id"] = $row["user_id"];
	  $_SESSION["username"] = $row["user_name"];
	  $_SESSION["email"]    = $row["user_email"];
	  $_SESSION["level"]    = $row["user_level"];
	  $_SESSION["can_comment"]    = $row["can_comment"];
	  $_SESSION["logged"]    = time();
	  }
	  
	  } else $errors.="<div>Invalid e-mail or password</div>";
	  
	}
	
  }
?>