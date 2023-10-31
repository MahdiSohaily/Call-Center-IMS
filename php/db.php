<?php
// Initialize the session
session_name("MyAppSession");
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  echo 'لطفا مجدد لاگین کنید';
  exit;
}
global $con;
$con = mysqli_connect('localhost', 'root', '', 'callcenter');

if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset($con, "utf8");
