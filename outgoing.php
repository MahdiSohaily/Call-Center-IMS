<?php
global $con;
$con = mysqli_connect('localhost', 'root', '', 'callcenter');

if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset($con, "utf8");




$phone = $_GET['phone'];
$user = $_GET['user'];
$callid = $user . "-" . $phone . "-" . date("Y-m-d") . "-" . $_GET['callid'];



$sql = "INSERT INTO outgoing (phone,user,callid) VALUES ('$phone', '$user','$callid');";
$result = mysqli_query($con, $sql);
if (!$result) {
  echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
  die();
} else {
  echo "done";
}
