<?php
global $con;
$con = mysqli_connect('localhost','root','','callcenter');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset($con, "utf8");
$phone = $_GET['phone'];  
$user = $_GET['user'];    
$callid = $_GET['callid'];    
$callid = $user."-".$phone."-".date("Y-m-d")."-".$_GET['callid'];

$sql="UPDATE incoming SET status=1,starttime=CURRENT_TIMESTAMP WHERE callid LIKE '$callid' AND  user='$user' ";
 

$result = mysqli_query($con,$sql);
if(!$result)
{
    echo "Error MySQLI QUERY: ".mysqli_error($con)."";
    die();
}
else
{
  echo "done";
} 

?>
