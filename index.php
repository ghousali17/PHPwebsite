<?php
include_once 'include/dbh.php';
$cookie_name = 'asg1';

?>
<!DOCTYPE html>
<html>
<head>
  <title>Webgram</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="style/index.css"
</head>
<body >
<div class="top-nav">
<?php

if(isset($_COOKIE[$cookie_name])){
echo '<p class="nav-txt">Welcome '.$_COOKIE[$cookie_name].'!</p>';
echo '<form method="POST" action="include/logout.php">
<button class="logout-button">logout</button>
</form>';

}else{
  echo '<p class="nav-txt">Sigin in for the complete experience!</p>';
  echo
  '<form method="POST" action="loginform.php">
  <button class="login-button" name="login-submit" type="submit" >log in</button>
  </form>';
}


?>
</div>
<div class="gallery-container">
<?php
echo '<div class="img-row">';
$sql = "SELECT * FROM gallery ORDER BY imgID DESC;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
  echo "error";
}else{
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $rowCount = mysqli_num_rows($result);
  $count = 0;
  while($row = mysqli_fetch_assoc($result) ){

    if($row['imgMode'] == 'private')
    {
      if(isset($_COOKIE[$cookie_name])){
        if($row['imgOwner'] == $_COOKIE[$cookie_name] ){
        echo '<div class="img-col"><img src="temp/'.$row['imgFullName'].'">
        <h3 class="img-title">'.$row['imgName'].'</h3>
        </div>';
           if($count %2 == 0)
           {
             echo '</div>';
             echo '<div class="img-row">';
           }
          $count = $count+1;

      }


    }

    }
    elseif($row['imgMode'] == 'public'){
      echo '<div class="img-col"><img src="temp/'.$row['imgFullName'].'">
      <h3 class="img-title">'.$row['imgName'].'</h3>
      </div>';
         if($count %2 == 0)
         {
           echo '</div>';
           echo '<div class="img-row">';
         }
        $count = $count+1;

    }

  }
}

?>
</div>


</div>

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
