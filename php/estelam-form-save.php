<?php
// Initialize the session
session_name("MyAppSession");
require_once("db.php");

$value1 = $_GET['code'];
$value2 = $_GET['sellerid'];
$value3 = $_GET['price'];
$value4 =  $_SESSION["id"];







foreach ($value1 as $index => $nisha) {






    $sql = "INSERT INTO estelam (codename,seller,price,user) VALUES ('$nisha', '$value2','$value3[$index]','$value4');";
    $result = mysqli_query($con, $sql);




    if (!$result) {
        echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
        die();
    } else {
        echo 'استعلام برای ' . $value2 . ' با موفقیت ثبت شد';
    }
}
