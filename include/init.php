<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once 'dbh.php';
$cookie_name = 'asg1';

if(isset($_COOKIE[$cookie_name])){
  if($_COOKIE[$cookie_name] == 'admin'){
    if (isset($_POST['init'])){
      echo 'deleted shit!';
      $files = glob('../test-1/*'); // get all file names
      foreach($files as $file){ // iterate files
        echo 'deleting';
        if(is_file($file))
          unlink($file); // delete file
      }
      $files = glob('../test-2/*'); // get all file names
      foreach($files as $file){ // iterate files
        echo 'deleting';
        if(is_file($file))
          unlink($file);
        }
        //deleted sqlsrv_begin_transaction
        //fix
    } elseif (isset($_POST['abort'])){
      header("Location: ../index.php");
      exit();
    }else{
      header("Location: ../index.php?init=notauthorised");
      exit();
    }

    }
    else{
    header("Location: ../index.php?init=notauthorised");
    exit();
  }
}
  else{
    header("Location: ../index.php?init=notauthorised");
    exit();
  }

$files = glob('path/to/temp/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}








?>
