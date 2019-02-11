<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once 'dbh.php';
$cookie_name = 'asg1';

if(isset($_COOKIE[$cookie_name])){
  if($_COOKIE[$cookie_name] != 'admin'){
    header("Location: ../index.php?init=notauthorised");
    exit();


  }
}
  else{

    header("Location: ../index.php?init=notauthorised");
    exit();

  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/initform.css">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar"> &#124 </a>
  <?php  echo '<a class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</a>';?>
    <a class="nav-button"><form method="POST" action="../index.php">
    <button class="button login-button" name="home" type="submit" >Home</button>
  </form></a>
</div>

<div class="init-container">
<div class="init-heading">System Initialization</div>
<div class="init-explanation">Warning: all data would be deleted</div>
<form method="POST" action = "init.php">
<button class="button init-button go" name="init" type="submit" >Please Go Ahead</button>
<button class="button init-button return" name="abort" type="submit" >Go Back</button>
</form>
</div>




</body>







</html>
