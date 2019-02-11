<?php

$cookie_name = 'asg1';
if(isset($_COOKIE[$cookie_name]))
{
  setcookie(  $cookie_name,0, time() - (86400 * 30), "/");
  header("Location: ../index.php?logout=success");
}else{
  header("Location: ../index.php?logout=fail");
  exit();
}
?>
