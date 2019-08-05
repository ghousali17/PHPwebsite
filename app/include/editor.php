<?php
include 'dbh.php';
include 'filterhelper.php'; 

$currentversion = 0; 
$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);

$tempDestination = $upload;
$nextversion = $currentversion+1;


if(strpos($fullUrl, "version=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$currentversion = $version;
}
$outputDestination = getNextPath($tempDestination,$currentversion);
if(strpos($fullUrl, "filter=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$filterFunction = $filter;
#echo 'filter function'.$filterFunction;
// call edit functions!
if($filterFunction == 'border'){
  #echo 'border is called!';
  border($tempDestination,$outputDestination);
  $tempDestination = $outputDestination;
}elseif($filterFunction == 'blur'){
  blur($tempDestination,$outputDestination);
  $tempDestination = $outputDestination;

}elseif($filterFunction == 'lomo'){
  lomo($tempDestination, $outputDestination);
  $tempDestination = $outputDestination;
}elseif($filterFunction == 'lensflare'){
  lensflare($tempDestination,$outputDestination);
  $tempDestination = $outputDestination;
}elseif($filterFunction == 'bw'){
  blackwhite($tempDestination, $outputDestination);
  $tempDestination = $outputDestination;
}


}
if(strpos($fullUrl, "command=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$commandmode = $command;
if($commandmode == 'discard'){
  $tempArray = explode('=', basename($tempDestination));
  if($tempArray['0'] == 'version'){
    $filename = $tempArray['2'];

    }else{
      $filename = basename($tempDestination);
          }
  
    
  $sql = "DELETE FROM gallery WHERE imgFullName='".$filename."'";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      echo "error in preparation";

     }else{
       mysqli_stmt_execute($stmt);
     } 

  $temppath = $tempDestination;
  $tempDestination = getPreviousPath($tempDestination);
  while($temppath != $tempDestination)
  {
      unlink($temppath);
      $temppath = $tempDestination;
      $tempDestination = getPreviousPath($tempDestination);
  
  
  }
  unlink($tempDestination);
  header("Location: ../index.php");
  exit();
}elseif($commandmode == 'undo'){
  $temppath = $tempDestination;
  $tempDestination = getPreviousPath($tempDestination);
  if($tempDestination != $temppath){
   unlink($temppath);
   
  }
  if($currentversion > 0)
  {
    $currentversion--;
    header("Location: ?upload=".$tempDestination."&version=".$currentversion);
    exit();

  }
  else{
   
     header("Location: ?upload=".$tempDestination);
     exit();
}
  
  }elseif($commandmode == 'finish'){
    $tempArray = explode('=', basename($tempDestination));
    if($tempArray['0'] == 'version'){
      $filename = $tempArray['2'];

    }else{
      $filename = basename($tempDestination);
    }
    $finalPath = "../images/".$filename;
    copy($tempDestination,$finalPath);
    $temppath = $tempDestination;
   $tempDestination = getPreviousPath($tempDestination);
    while($temppath != $tempDestination)
    {
      unlink($temppath);
      $temppath = $tempDestination;
      $tempDestination = getPreviousPath($tempDestination);
  
  
    }
    unlink($tempDestination);
  
    unlink($tempDestination);
    #echo "Temp:".$tempDestination;
    #echo "Final".$finalPath;
    header("Location: uploadcomplete.php?upload=".$finalPath);
    exit();



  }

}
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/editor.css">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar"> &#124 </a>

    
</div>

<div class="editor-container">
<div class = "editor-row">
<div class = "editor-column-img">
<?php

if(isset($_POST['border'])){
  $currentversion++;
  header("Location: ?filter=border&upload=".$tempDestination."&version=".$currentversion);
  exit();
}elseif(isset($_POST['lomo'])){
   $currentversion++;
  header("Location: ?filter=lomo&upload=".$tempDestination."&version=".$currentversion);

  exit();

}elseif(isset($_POST['lensflare'])){
   $currentversion++;
  header("Location: ?filter=lensflare&upload=".$tempDestination."&version=".$currentversion);
 exit();


}elseif(isset($_POST['bw'])){
  $currentversion++;
  header("Location: ?filter=bw&upload=".$tempDestination."&version=".$currentversion);

  exit();


}elseif(isset($_POST['blur'])){
  $currentversion++;
  header("Location: ?filter=blur&upload=".$tempDestination."&version=".$currentversion);

  exit();


}else {echo '<img src='.$tempDestination.'>';}

if(isset($_POST['undo'])){
  header("Location: ?command=undo&upload=".$tempDestination."&version=".$currentversion);
  exit();

}elseif(isset($_POST['discard'])){
    header("Location: ?command=discard&upload=".$tempDestination."&version=".$currentversion);
    exit();


  }elseif(isset($_POST['finish'])){
    header("Location: ?command=finish&upload=".$tempDestination."&version=".$currentversion);
    exit();
}
?>

</div>
<div class="editor-column">
<?php echo'<form method="POST" action="editor.php?upload='.$tempDestination."&version=".$currentversion.'">'; ?>

<button class="button filter-button" name="border" type="submit">Border</button>
<button class="button filter-button" name="lomo" type="submit">Lomo</button>
<button class="button filter-button" name="lensflare" type="submit">Lens Flare</button>
<button class="button filter-button" name="bw" type="submit">Black White</button>
<button class="button filter-button" name="blur" type="submit">Blur</button>
</form>
</div>
</div>
<div class="editor-row">
<div class= "editor-column-lg">
<?php echo'<form method="POST" action="editor.php?upload='.$tempDestination."&version=".$currentversion.'">'; ?>
<button class="button filter-control yellow" name="undo" type="submit">Undo</button>
<button class="button filter-control red"name="discard" type="submit">Discard</button>
<button class="button filter-control green"name="finish" type="submit">Finish</button>
</form>
</div>
</div>
</div>




</body>







</html>
