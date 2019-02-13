<?php
include 'dbh.php';
 
function execute($command)
    {
        # remove newlines and convert single quotes to double to prevent errors
        $command = str_replace(array("\n", "'"), array('', '"'), $command);
        $command = escapeshellcmd($command);
        # execute convert program
        exec($command);
    }
     
function border($input,$output, $color = 'black', $width = 20)
    {
        execute("convert $input -bordercolor $color -border {$width}x{$width} $output");
    }

function blur($input, $output, $radius = 10, $sigma= 5)
    {   $temp = $radius* $sigma;
        execute("convert $input -blur $temp $output");
    }

 function lomo($input)
    {
        # copy original file and assign temporary name
        $command = "convert $input -channel R -level 33% -channel G -level 33% $input";
        execute($command);
        vignette($input);
        
    }
function vignette($input, $color_1 = 'none', $color_2 = 'black', $crop_factor = 1.5)
    {   list($width,$height) = getimagesize($input);
        $crop_x = floor($width * $crop_factor);
        $crop_y = floor($height * $crop_factor);
         
        execute("convert 
        ( {$input} ) 
        ( -size {$crop_x}x{$crop_y} 
        radial-gradient:$color_1-$color_2
        -gravity center -crop {$width}x{$height}+0+0 +repage )
        -compose multiply -flatten $input");   
    }
function lensflare($input,$output){
  list($width,$height) = getimagesize($input);
  $temp = $width * $height;
  $cmd_1 = "convert '../tools/lensflare.png' -resize $width '../tools/tmp.png'";
  execute($cmd_1);
  $cmd_2 = "composite -compose screen -gravity northwest ../tools/tmp.png  $input $output";
  execute($cmd_2);
  $cmd_3 = "rm ../tools/tmp.png";
  execute($cmd_3);
}
function blackwhite($input)
{ 
  list($width,$height) = getimagesize($input);
  $temp = $width * $height;
  
  $cmd_1 = "convert $input -type grayscale $input";
  execute($cmd_1);
  $cmd_2= "convert ../tools/bwgrad.png -resize $temp '\! ../tools/tmp.png";
  execute($cmd_2);
  $cmd_3 = "composite -compose softlight -gravity center ../tools/tmp.png $input $input";
  execute($cmd_3);
  
}

function getNextPath($tempDestination, $currentversion){
  $output = "";
  $tempArray = explode('=', basename($tempDestination));
  if($tempArray['0'] == 'version'){
      $filename = $tempArray['2'];
      $version = $tempArray['1'];
      $version++;
      $output = dirname($tempDestination).'/version='.$version.'='.$filename;
  }else{

    $output =dirname($tempDestination).'/version=1'.'='.basename($tempDestination);
  }
return $output;
}

function getPreviousPath($tempDestination){
  $tempArray = explode('=', basename($tempDestination));
  $output = "";
  if($tempArray['0'] == 'version'){
      $filename = $tempArray['2'];
      $version = $tempArray['1'];
      if($version == 1)
      {
        $output= dirname($tempDestination).'/'.$filename;
  
      }else
      {
        $version--;
        $output = dirname($tempDestination).'/version='.$version.'='.$filename;
      }
      
  }else{

    $output = dirname($tempDestination).'/'.basename($tempDestination);
 
  }
return $output;

}

$currentversion = 0; 
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);


$tempDestination = $upload;
echo dirname($tempDestination);
echo('delimiter');
echo basename($tempDestination);
echo'\n';

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
  lomo($tempDestination);
}elseif($filterFunction == 'lensflare'){
  lensflare($tempDestination,$outputDestination);
  $tempDestination = $outputDestination;
}elseif($filterFunction == 'bw'){
  blackwhite($tempDestination);
}


}
if(strpos($fullUrl, "command=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$commandmode = $command;

if($commandmode == 'discard'){
  unlink($tempDestination);
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

    <a class="nav-button"><form method="POST" action="../index.php">
    <button class="button login-button" name="home" type="submit" >Home</button>
  </form></a>
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
<button class="button filter-control" name="undo" type="submit">Undo</button>
<button class="button filter-control"name="discard" type="submit">Discard</button>
<button class="button filter-control"name="finish" type="submit">Finish</button>
</form>
</div>
</div>
</div>




</body>







</html>
