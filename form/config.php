<?php
$baseurl = $_SERVER['SERVER_NAME'];
//echo $baseurl;
if($baseurl=='localhost')
{
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ret_dbs";
}else{
 $servername = "localhost";
 $username = "crmwala_mbdbun";
 $password = "rkt.ftp@1215";
 $dbname = "crmwala_admindb";
}


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>

