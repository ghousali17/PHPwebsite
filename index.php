<?php
$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once 'include/dbh.php';
$cookie_name = 'asg1';
$currentPage = 1;
if(strpos($fullUrl, "page=")){
$url = parse_url($fullUrl);
parse_str($url['query']);
$currentPage = $page;

}

function getItems(){
  include 'include/dbh.php';
  $cookie_name = 'asg1';

  $items = array();

  if(isset($_COOKIE[$cookie_name])){
    $sql = "SELECT * FROM gallery WHERE imgMode = 'public' OR ( imgMode = 'private' AND imgOwner = '" .$_COOKIE[$cookie_name]."' ) ORDER BY imgID DESC;";

  }else{
    $sql = "SELECT * FROM gallery WHERE imgMode = 'public'  ORDER BY imgID DESC;";
    }

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    echo "error";
  }else{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);
    $count = 0;
    while($row = mysqli_fetch_assoc($result) ){
      $items[] = '<div class="gallery-column"><img src="temp/'.$row['imgFullName'].'">
      <h3 class="img-title">'.$row['imgName'].'</h3>
      </div>';


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

</head>
<body >
  <div class="topnav">
    <a class="nav-heading" href="#home">Webgram </a>
    <a class="nav-bar">&#124</a>


<?php

if(isset($_COOKIE[$cookie_name])){
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
  '<a class="nav-button"><form method="POST" action="loginform.php">
  <button style="float:right;" class="button login-button" name="login-submit" type="submit" >log in</button>
  </form></a>';
}


?>
</div>

<?php
//pagination configuration
$offset = 8;
$items = getItems();
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
<button name="next" type="submit" action = "next"><a href="?page=' .$previous.'">Previous</a></button>';
echo '<p>page '.$currentPage. ' of '.$numpage.'</p>
<button name="next" type="submit" action = "next"><a href="?page='.$next.'">Next</a></button>
</div>';
}else{
  echo  '<div class = "page-nav">
  <p>1 of 1 page</p>
  </div>';
}
}
?>



<?php

if(isset($_COOKIE[$cookie_name])){
echo '<div class="upload-container">
<form action="include/upload.php" method = "post" enctype="multipart/form-data">
<input type="text" name="filename" placeholder="Image title">
<select name="filemode">
  <option value="private">private</option>
  <option value="public">public</option>
</select>
<input type ="file" name="file">
<button type="submit" name="file-submit">upload</button>
</form>
</div>';

}else{
 echo '';

}?>
</div>
</body>







</html>
