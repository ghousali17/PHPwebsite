<?php
if(isset($_POST['next'])){
  echo 'move next';
  header("Location: ../index.php?move=next");
  exit();
}elseif(isset($_POST['previous'])){
  echo 'move back';
  header("Location: ../index.php?move=back");
  exit();
}




?>
