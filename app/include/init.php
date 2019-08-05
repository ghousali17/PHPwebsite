<?php
$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
include_once 'dbh.php';
include_once 'authenticate.php';
$cookie_name = 'asg1';

if(authenticate($conn)){
  if($_COOKIE[$cookie_name] == 'admin'){
    if (isset($_POST['init'])){
      
      $files = glob('../images/*'); // get all file names
      foreach($files as $file){ // iterate files
      
        if(is_file($file))
          unlink($file); // delete file
      }
      $files = glob('../temp/*'); // get all file names
      foreach($files as $file){ // iterate files
      
        if(is_file($file))
          unlink($file);
        }
      $sql = "DELETE FROM gallery;";
      mysqli_query($conn,$sql);
      $sql = "DELETE FROM users WHERE u_name != 'admin';";
      mysqli_query($conn,$sql);
      #$sql = "INSERT IGNORE INTO users (u_id, u_name, u_passwd) values (1, 'admin', 'minda123')";
      mysqli_query($conn,$sql);
      $sql = "INSERT IGNORE INTO users (u_id u_name, u_passwd) values (2, 'Alice', 'csci4140')";
      mysqli_query($conn,$sql);
      header("Location: initcomplete.php");
      exit();
      
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
