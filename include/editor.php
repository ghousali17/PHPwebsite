<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$tempDestination = $upload;
if(strpos($fullUrl, "filter=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$filterFunction = $filter;
echo 'filter function'.$filterFunction;
// call edit functions!
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
  header("Location: ?filter=border&upload=".$tempDestination);
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


}elseif(isset($_POST['blue'])){
  header("Location: ?filter=blue&upload=".$tempDestination);
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
