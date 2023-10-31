<?php
// Initialize the session
session_name("MyAppSession");
require_once("db.php");

$value1 = $_GET['name'];
$value2 = $_GET['family'];
$value3 = $_GET['phone'];
$value4 = $_GET['vin'];
$value5 = $_GET['des'];
$value6 = $_GET['address'];
$value7 = $_GET['kind'];
$value8 = $_GET['car'];
$value9 = $_GET['callinfo'];
$value10 =  $_SESSION["id"];
$isold = $_GET['isold'];
$pin = empty($_GET['pin']) ? 'unpin' : 'pin';


if (strlen($value9) > 0) {

    $sql = "INSERT INTO record (phone,callinfo,user, pin) VALUES ('$value3', '$value9','$value10','$pin');";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
        die();
    } else {
    }
}





if ($isold == 0) {

    $sql = "INSERT INTO customer (name,family,phone,vin,des,address,kind,car) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5', '$value6', '$value7', '$value8');";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
        die();
    } else {
        echo '<p class="ok"> مشتری <span>' . $value1 . '</span> <span>' . $value2 . '</span> به شماره <span>' . $value3 . '</span> با موفقیت وارد سیستم شد </p>';
    }
}

if ($isold == 1) {




    $sql = "UPDATE customer SET name='$value1',family='$value2',vin='$value4',des='$value5',address='$value6',kind='$value7',car='$value8'
WHERE phone LIKE '$value3' ";


    $result = mysqli_query($con, $sql);
    if (!$result) {
        echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
        die();
    } else {
        echo '<p class="ok"> تغییرات برای مشتری <span>' . $value1 . '</span> <span>' . $value2 . '</span> به شماره <span>' . $value3 . '</span> با موفقیت ثبت شد </p>';
    }
}

mysqli_close($con);
