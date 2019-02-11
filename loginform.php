<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
?>
<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="style/index.css"
</head>
<body >
<div class="top-nav">
  <p class="nav-txt">Webgram!</p>
  <form method="POST" action="index.php">
  <button class="login-button" name="home" type="submit" >Home</button>
  </form>
</div>
<div class="login-container">
<form method="POST" action="include/login.php">
<p> Username</p>
<input type="text" name ="u_name" >
<p> Password</p>
<input type="text" name ="u_passwd" >
<?php
if(strpos($fullUrl, "login=incomplete") == true) {
echo '<p class="log-error"> Please fill in the form!</p>';

}elseif(strpos($fullUrl, "login=notfound") == true) {
echo '<p class="log-error"> User does not exist!</p>';

}elseif(strpos($fullUrl, "login=mismatch") == true) {
echo '<p class="log-error"> Wrong username or password!</p>';

}

?>
<button type="submit" name="submit">log in</button>
</form>
</div>




</body>







</html>
