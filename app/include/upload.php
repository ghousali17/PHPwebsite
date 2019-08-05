<?php

$cookie_name = 'asg1';

if(isset($_POST['file-submit'])){

  if(isset($_POST['file-name'])){
    $newFileName =strtolower(str_replace(" ", "-", $_POST['filename'] ));

  }else{

    $newFileName = "gallery";
  }

$imageMode = $_POST['filemode'];
$file = $_FILES['file'];
$fileName = $file['name'];
$imageTitle = $file['name'];
$fileType = $file['type'];
$fileTempName = $file['tmp_name'];
$fileError = $file['error'];
$fileSize = $file['size'];

$fileExt = explode(".", $fileName);
$fileActualExt = strtolower(end($fileExt));



  if($fileError == 0){
    $imageFullName = $newFileName. ".". uniqid("",true).".".$fileActualExt;
    $tempDestination = "../temp/".$imageFullName;
    $fileDestination = "../images/".$imageFullName;
    move_uploaded_file($fileTempName,$tempDestination); 



	
    $mime_result = mime_content_type ($tempDestination);
   
    if(($fileActualExt == 'jpg' && $mime_result == 'image/jpeg' ) || ($fileActualExt == 'jpeg' && $mime_result == 'image/jpeg' ) || ($fileActualExt == 'gif' && $mime_result == 'image/gif') || ($fileActualExt == 'png' && $mime_result == 'image/png'))
    {
    include_once 'dbh.php';
    $sql = "SELECT * FROM gallery;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      unlink($tempDestination);
      header("Location: ../index.php?upload=internalerror"); 
      exit();
    } else{
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $rowCount = mysqli_num_rows($result);
      $setImageOrder = $rowCount + 1;

    $sql = "INSERT INTO gallery (imgName,imgMode,imgOwner, imgFullName, imgOrder) VALUES(?,?,?,?,?);";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
        
        unlink($tempDestination);
        header("Location: ../index.php?upload=internalerror");
        exit();
      }else{

        mysqli_stmt_bind_param($stmt, "sssss", $imageTitle, $imageMode, $_COOKIE[$cookie_name], $imageFullName, $setImageOrder);
        mysqli_stmt_execute($stmt);
        
        header("Location: editor.php?upload=".$tempDestination);
        exit();
      }
     }
    }
    else{
      unlink($tempDestination);
      header("Location: ../index.php?upload=mismatch");     
      exit(); 
  } 
 }else{
    header("Location: ../index.php?upload=error"); 
    exit();
  }

}else{

header("Location: ../index.php"); 
exit();      
}


?>
