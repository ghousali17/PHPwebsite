<?php
$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/loginform.css">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar"> &#124 </a>

    <a class="nav-button"><form method="POST" action="../index.php">
    <button class="button login-button" name="home" type="submit" >Home</button>
  </form></a>
</div>

<div class="login-container">
<form method="POST" action="login.php">
<p> Username</p>
<div class="input"><input type="text" name ="u_name" ></div>
<p> Password</p>
<div class="input"> <input type="password" name ="u_passwd" ></div>
<?php
if(strpos($fullUrl, "login=incomplete") == true) {
echo '<p class="error"> Please fill in the form!</p>';

}elseif(strpos($fullUrl, "login=notfound") == true) {
echo '<p class="error"> User does not exist!</p>';

}elseif(strpos($fullUrl, "login=mismatch") == true) {
echo '<p class="error"> Wrong username or password!</p>';

}

?>
<button class="button login-button"type="submit" name="submit">log in</button>
</form>
</div>




</body>







</html>
