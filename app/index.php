<?php


$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
include_once 'include/dbh.php';
include_once 'include/authenticate.php';
$cookie_name = 'asg1';
$currentPage = 1;
if(strpos($fullUrl, "page=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$currentPage = $page;

}

function getItems($conn){
  $cookie_name = 'asg1';

  $items = array();

  if(authenticate($conn)){
    $sql = "SELECT * FROM gallery WHERE imgMode = 'public' OR ( imgMode = 'private' AND imgOwner = '" .$_COOKIE[$cookie_name]."' ) ORDER BY imgID DESC;";

  }else{
    $sql = "SELECT * FROM gallery WHERE imgMode = 'public'  ORDER BY imgID DESC;";
    }

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    echo "error in databse connection";
  }else{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);
    $items = [];
    while($row = mysqli_fetch_assoc($result) ){
      $path = 'images/'.$row['imgFullName'];
      if(file_exists($path))
      {
      $items[] = '<div class="gallery-column">
      <a href = "http://'.$_SERVER['HTTP_HOST'].'/images/'.$row['imgFullName'].'"><img src="images/'.$row['imgFullName'].'"></a>
      <h3></h3>
      
      </div>';
    }


    }
  }

return $items;

}



function integerdivision($a, $b){
    return ($a - $a % $b) / $b;
}
function numPages($a,$b){
  if(($a/$b) > (integerdivision($a,$b)))
  {
    return integerdivision($a,$b)+1;
  }else{
    return ($a/$b);
  }
}

function print_page($items, $pagenumber, $last,$offset){
$pagenumber--;
$count = 0;
$start = $pagenumber*$offset;
if($last > $start + $offset)
{
  $last = $start + $offset;
}
for($i = $start; $i < $last; $i++)
{   if($count == 4)
  {
    echo '</div>';
    echo '<div class = "gallery-row">';
    echo $items[$i];


  }else{
      echo $items[$i];
  }
 $count++;
}
}
?>




<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="style/index.css">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar">&#124</a>


<?php

if(authenticate($conn)){
  if($_COOKIE[$cookie_name] == 'admin')
  {
    echo '<a class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</a>';
    echo '<a class="nav-button"><form method="POST" action="include/logout.php">
    <button style="float:right;"class="button logout-button">logout</button>
    </form></a>';
    echo '<a class="nav-button"><form method="POST" action="include/initform.php">
    <button style="float:right;"class="button init-button">Initialise</button>
    </form></a>';
  }
  else{
    echo '<a class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</a>';
    echo '<a class="nav-button"><form method="POST" action="include/logout.php">
    <button style="float:right;"class="button logout-button">logout</button>
    </form></a>';
  }


}else{
  echo '<a class="nav-txt">Log in for the complete experience...</a>';
  echo
  '<a class="nav-button"><form method="POST" action="include/loginform.php">
  <button style="float:right;" class="button login-button" name="login-submit" type="submit" >log in</button>
  </form></a>';
}


  ?>
</div>

<?php
//pagination configuration
$offset = 8;
$items = getItems($conn);
if(sizeof($items) >0){
$numpage = numPages(sizeof($items),$offset);
//gallery display
$last = sizeof($items);
$start = 0;
$current = 0;

//navigation controller for gallery pages
if(($currentPage + 1) > $numpage)
{
  $next = $numpage;
}else{
$next = $currentPage + 1;
}
if(($currentPage - 1) < 1){
  $previous = 1;
}else{
$previous = $currentPage - 1;
}
if($currentPage > $numpage){
  header("Location: ?page=".$numpage);
  exit();
}
if($currentPage < 1){
  header("Location: ?page=1");
  exit();
}
echo '<div class = "gallery-container">';
echo '<div class = "gallery-row">';
print_page($items, $currentPage, $last, 8);
echo '</div>';
echo '</div>';
if($numpage > 1){

echo  '<div class = "page-nav">
<div class="nav-row">
<div class="nav-column">
<a href="?page=' .$previous.'"><button class="button">Previous</button></a>
</div>';
echo '
<div class="nav-column-middle">
<p>page '.$currentPage. ' of '.$numpage.'</p>
</div>
<div class="nav-column">
<a href="?page='.$next.'"><button class="button">Next</button></a>
</div>
</div>
</div>';
}else{
  echo  '<div class = "page-nav">
  <p>1 of 1 page</p>
  </div>';}
}
else{
echo '<div class = "gallery-container">';
      echo '<div class = "gallery-row" style="font-size:22px; text-align:left;">
      Your newsfeed is empty :(
      </div>';
echo '</div>';


}
?>



<?php

if(authenticate($conn)){

echo '<div class="upload-container">
<form action="include/upload.php" method = "post" enctype="multipart/form-data">
<input class = "upload-file" type ="file" name="file">
<select class="upload-select" name="filemode">
  <option value="private">Private</option>
  <option value="public">Public</option>
</select>

<button class = "button" type="submit" name="file-submit">upload</button>
</form>';
echo '<div class="error">';

$fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($fullUrl, "upload=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
if($upload == 'mismatch')
{
  echo 'Invalid file type!';
}elseif($upload == 'error'){
  echo 'Please select a file!';
}elseif($upload == 'internalerror'){
  echo 'Unable to upload file!';
}
}

echo '</div></div>';

}else{
 echo '';

}?>
</div>
</body>







</html>
