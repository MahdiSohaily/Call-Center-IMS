<?php
require_once('../../database/connect.php');
if (isset($_POST['store_price'])) {
    $partnumber = $_POST['partNumber'];
    $price = $_POST['price'];
    $customer_id = $_POST['customer_id'];
    $notification_id = $_POST['notification_id'];
    store($conn, $partnumber, $price, $customer_id, $notification_id);
}



if (isset($_POST['askPrice'])) {
    $partnumber = $_POST['partNumber'];
    $customer_id = $_POST['customer_id'];
    $user_id = $_POST['user_id'];
    date_default_timezone_set("Asia/Tehran");
    $created_at = date("Y-m-d H:i:s");

    askPrice($conn, $partnumber, $customer_id, $user_id, $created_at);
}

function store($conn, $partnumber, $price, $customer_id, $notification_id)
{
    date_default_timezone_set("Asia/Tehran");
    $created_at = date("Y-m-d H:i:s");
    $pattern_sql = "INSERT INTO prices (partnumber, price, customer_id, created_at, updated_at)
            VALUES ('" . $partnumber . "', '" . $price . "', '" . $customer_id . "', '" . $created_at . "', '" . $created_at . "')";
    $conn->query($pattern_sql);
    if ($notification_id) {
        $sql = "UPDATE ask_price SET status= 'done' , notify = 'received', price = '$price' WHERE id = '$notification_id'";
        $conn->query($sql);
    }
    echo 'true';
}


function askPrice($conn, $partnumber, $customer_id, $user_id, $created_at)
{
    $pattern_sql = "INSERT INTO ask_price (customer_id, user_id, code, status, notify, created_at)
            VALUES ('" . $customer_id . "', '" . $user_id . "', '" . $partnumber . "', 'pending', 'send' , '" . $created_at . "')";

    if ($conn->query($pattern_sql) === TRUE) {
        echo 'true';
    }
}
