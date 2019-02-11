<?php
if(isset($_POST['submit'])){

    include 'dbh.php';
    $u_name = $_POST['u_name'];
    $u_passwd = $_POST['u_passwd'];
    if(empty($u_name) || empty($u_passwd)){
      header("Location: ../loginform.php?login=incomplete");
      exit();


    }
    else{

    $sql = "SELECT * FROM users where u_name = '$u_name'";
    $result = mysqli_query($conn, $sql);
    $result_count = mysqli_num_rows($result); //returns the number of rows returned from our earlier query.
    if($result_count < 1){
      header("Location: ../loginform.php?login=notfound");
      exit();

    }else{
      if($row = mysqli_fetch_assoc($result)){

          if($u_passwd == $row['u_passwd']){
            //$_SESSION['u_name'] = $row['u_name'];
            //$_SESSION['u_id'] = $row['u_id'];
            $cookie_name = 'asg1';
            $cookie_value = $u_name;
            if(setcookie(  $cookie_name,$cookie_value, time() + (86400 * 30), "/") == true){
              header("Location: ../index.php?login=succes");
              exit();

            }else{
              echo 'echo error!';
            }

          }else{
            header("Location: ../loginform.php?login=mismatch");
            exit();

          }

      }else{
        header("Location: ../loginform.php?login=error");
        exit();

      }

    }
    }


}else{
  header("Location: ../loginform.php?login=error");
  exit();
}








//

?>
