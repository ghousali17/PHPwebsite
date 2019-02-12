<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once 'dbh.php';
$cookie_name = 'asg1';

if(isset($_COOKIE[$cookie_name])){
  if($_COOKIE[$cookie_name] == 'admin'){
    if (isset($_POST['init'])){
      echo 'deleted shit!';
      $files = glob('../images/*'); // get all file names
      foreach($files as $file){ // iterate files
        echo 'deleting';
        if(is_file($file))
          unlink($file); // delete file
      }
      $files = glob('../temp/*'); // get all file names
      foreach($files as $file){ // iterate files
        echo 'deleting';
        if(is_file($file))
          unlink($file);
        }
      $sql = "DELETE FROM gallery";
      mysqli_query($conn,$sql);
      $sql = "DELETE FROM user";
      mysqli_query($conn,$sql);
      $sql = "INSERT INTO users (u_name, u_passwd) values ('admin', 'minda123')";
      mysqli_query($conn,$sql);
      $sql = "INSERT INTO users (u_name, u_passwd) values ('Alice', 'csci4140')";
      mysqli_query($conn,$sql);
      
      
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
