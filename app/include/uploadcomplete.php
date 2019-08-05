<?php
$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$cookie_name = 'asg1';
include_once 'authenticate.php';
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);


$tempDestination = $upload;
}else{
	header("Location: ../index.php");
	exit();
}


?>
<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="../style/index.css">
  <link rel="stylesheet" href="../style/uploadcomplete.css">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">

</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar">&#124</a>


<?php

if(authenticate($conn)){
  if($_COOKIE[$cookie_name] == 'admin')
  {
    echo '<a class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</a>';
    echo '<a class="nav-button"><form method="POST" action="logout.php">
    <button style="float:right;"class="button logout-button">logout</button>
    </form></a>';
    echo '<a class="nav-button"><form method="POST" action="initform.php">
    <button style="float:right;"class="button init-button">Initialise</button>
    </form></a>';
  }
  else{
    echo '<a class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</a>';
    echo '<a class="nav-button"><form method="POST" action="logout.php">
    <button style="float:right;"class="button logout-button">logout</button>
    </form></a>';
  }


}else{
  echo '<a class="nav-txt">Log in for the complete experience...</a>';
  echo
  '<a class="nav-button"><form method="POST" action="loginform.php">
  <button style="float:right;" class="button login-button" name="login-submit" type="submit" >log in</button>
  </form></a>';
}

?>
</div>
<div class="complete-container">
	<div class="complete-row">
    <div class="complete-column">
	<?php	echo '<img src="'.$tempDestination.'">' ?>
  <?php echo '<p> PERMALINK: http://'.$_SERVER[HTTP_HOST].'/images/'.basename($tempDestination).'</p>' ?>
	</div>
</div>
	<div class="complete-row">
		<form method = "POST" action ="../index.php">
			<button class = "button" type ="submit"> Return to gallery</button>
		</form>
	</div>
	
</div>
</body>
</html>