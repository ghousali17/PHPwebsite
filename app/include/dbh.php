<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];

$username = $url["user"];

$password = $url["pass"];

$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
} 
   $sql = "CREATE TABLE IF NOT EXISTS gallery (
    imgId int(255) AUTO_INCREMENT PRIMARY KEY not null,  
    imgName LONGTEXT not null,
    imgMode varchar(256) not null,
    imgOwner varchar(256) not null,
    imgFullName LONGTEXT not null,
    imgOrder LONGTEXT not null
     )";
$conn->query($sql);     
$sql = "CREATE TABLE IF NOT EXISTS users (
  u_id int(255) AUTO_INCREMENT PRIMARY KEY not null,  
  u_name varchar(256) not null, 
  u_passwd varchar(256) not null,
  u_token varchar(128)
  )";

$conn->query($sql);

$sql = "INSERT IGNORE INTO users (u_id, u_name, u_passwd) values ( 1, 'admin', 'minda123')";
$conn->query($sql);
$sql = "INSERT IGNORE INTO users (u_id, u_name, u_passwd) VALUES (2, 'Alice', 'csci4140')";
$conn->query($sql);


 ?>

