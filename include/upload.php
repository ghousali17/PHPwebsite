<?php
$cookie_name = 'asg1';

if(isset($_POST['file-submit'])){

  if(isset($_POST['file-name'])){
    $newFileName =strtolower(str_replace(" ", "-", $_POST['filename'] ));

  }else{

    $newFileName = "gallery";
  }
$imageTitle = $_POST['filename'];
$imageMode = $_POST['filemode'];
$file = $_FILES['file'];
$fileName = $file['name'];
$fileType = $file['type'];
$fileTempName = $file['tmp_name'];
$fileError = $file['error'];
$fileSize = $file['size'];

$fileExt = explode(".", $fileName);
$fileActualExt = strtolower(end($fileExt));

$allowed = array("jpg", "jpeg", "png","gif");

if(in_array($fileActualExt,$allowed)){
  if($fileError == 0){
    $imageFullName = $newFileName. ".". uniqid("",true).".".$fileActualExt;
    $tempDestination = "../temp/".$imageFullName;
    $fileDestination = "../images/".$imageFullName;



    include_once 'dbh.php';
    $sql = "SELECT * FROM gallery;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo "error";
    } else{
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $rowCount = mysqli_num_rows($result);
      $setImageOrder = $rowCount + 1;

      $sql = "INSERT INTO gallery (imgName,imgMode,imgOwner, imgFullName, imgOrder) VALUES(?,?,?,?,?);";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "error in preparation";
      }else{
        mysqli_stmt_bind_param($stmt, "sssss", $imageTitle, $imageMode, $_COOKIE[$cookie_name], $imageFullName, $setImageOrder);
        mysqli_stmt_execute($stmt);
        move_uploaded_file($fileTempName,$tempDestination);

        #echo '<img src="'.$tempDestination.'">';
        #header('Content-type: image/jpg');
        #echo $tempDestination;
        #$image = new Imagick($tempDestination);

// If 0 is provided as a width or height parameter,
// aspect ratio is maintained
        #$image->thumbnailImage(100, 0);

        #echo $image;
        #unlink($tempDestination);
        #move_uploaded_file($fileTempName,$fileDestination);
        header("Location: editor.php?upload=".$tempDestination);
        exit();
      }


    }
  }else{
    echo 'Error uploading file';
    exit();

  }
}else{
  echo 'you need to upload proper file';
  exit();
}

}else{


}


?>
