<?php

function execute($command)
    {
        # remove newlines and convert single quotes to double to prevent errors
        $command = str_replace(array("\n", "'"), array('', '"'), $command);
        $command = escapeshellcmd($command);
        # execute convert program
        exec($command);
    }
     
function border($input, $color = 'black', $width = 20)
    {
        execute("convert $input -bordercolor $color -border {$width}x{$width} $input");
    }

function blur($input, $radius = 10, $sigma= 5)
    {   $temp = $radius* $sigma;
        execute("convert $input -blur $temp $input");
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
function lensflare($input){
  list($width,$height) = getimagesize($input);
  $temp = $width * $height;
  $cmd_1 = "convert '../tools/lensflare.png' -resize $width '../tools/tmp.png'";
  execute($cmd_1);
  $cmd_2 = "composite -compose screen -gravity northwest ../tools/tmp.png  $input $input";
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

$currentversion = 0; 
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$tempDestination = $upload;
if(strpos($fullUrl, "version=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$currentversion = $version;
}
if(strpos($fullUrl, "filter=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$filterFunction = $filter;
#echo 'filter function'.$filterFunction;
// call edit functions!
if($filterFunction == 'border'){
  #echo 'border is called!';
  border($tempDestination);
}elseif($filterFunction == 'blur'){
  blur($tempDestination);
}elseif($filterFunction == 'lomo'){
  lomo($tempDestination);
}elseif($filterFunction == 'lensflare'){
  lensflare($tempDestination);
}elseif($filterFunction == 'bw'){
  blackwhite($tempDestination);
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
  header("Location: ?filter=lomo&upload=".$tempDestination);
  exit();

}elseif(isset($_POST['lensflare'])){
  header("Location: ?filter=lensflare&upload=".$tempDestination);
  exit();


}elseif(isset($_POST['bw'])){
  header("Location: ?filter=bw&upload=".$tempDestination);
  exit();


}elseif(isset($_POST['blur'])){
  header("Location: ?filter=blur&upload=".$tempDestination);
  exit();


}else {echo '<img src='.$tempDestination.'>';}

  if(isset($_POST['undo'])){

  }elseif(isset($_POST['discard'])){
    unlink($tempDestination);
    header("Location: ../index.php");
    exit();


  }elseif(isset($_POST['finish'])){

  }?>

</div>
<div class="editor-column">
<?php echo'<form method="POST" action="editor.php?upload='.$tempDestination.'">'; ?>

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
<?php echo'<form method="POST" action="editor.php?upload='.$tempDestination.'">'; ?>
<button class="button filter-control" name="undo" type="submit">Undo</button>
<button class="button filter-control"name="discard" type="submit">Discard</button>
<button class="button filter-control"name="finish" type="submit">Finish</button>
</form>
</div>
</div>
</div>




</body>







</html>
