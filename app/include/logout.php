<?php
include_once 'dbh.php';
$cookie_key = 'asg1-key';
$cookie_name = 'asg1';
if(isset($_COOKIE[$cookie_name]))
{
  setcookie(  $cookie_name,0, time() - (86400 * 30), "/");
  setcookie(  $cookie_key,0, time() - (86400 * 30), "/");
  $sql = "UPDATE users SET u_token = '' WHERE u_name = '$_COOKIE[$cookie_name]'";
  mysqli_query($conn, $sql);
  header("Location: ../index.php?logout=success");
}else{
  header("Location: ../index.php?logout=fail");
  exit();
}
?>
