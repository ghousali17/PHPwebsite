<?php
include_once 'dbh.php';

function authenticate($conn){
$cookie_name = 'asg1';
$cookie_key = 'asg1-key';


if(isset($_COOKIE[$cookie_name]) && isset($_COOKIE[$cookie_key])){
	$u_name = $_COOKIE[$cookie_name];
	$sql = "SELECT * FROM users where u_name = '$u_name'";

$stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    return False;
  }else{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);
    if($rowCount < 1)
    {

    	return False;
    }else{

    	if($row = mysqli_fetch_assoc($result)){
    		$token = $_COOKIE[$cookie_key];
    		#echo 'TOKEN RECEIVED'.$token; 
    		#exit();
    		if($token == $row['u_token'])
    		{
    			return True;
    		}else{
    			return False;
    		}

    	}else{
    		return False; 
    	}

    }
  }



}else{
	return False;
}
}





?>