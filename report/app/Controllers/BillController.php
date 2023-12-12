<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
if (isset($_POST['check_notification'])) {

    $user_id = $_SESSION['user_id'];

    echo index($conn, $user_id);
}

function index($conn, $id)
{
    $similar_sql = "SELECT roll FROM yadakshop1402.users WHERE id = ' $id' ";

    $similar = mysqli_query($conn, $similar_sql);

    if ($similar->num_rows > 0) {
        $data = mysqli_fetch_array($similar);
    }
 

    $adminNotification = [];
    if ($data['roll'] == 1) {
        $similar_sql = "SELECT * FROM ask_price WHERE status = 'pending' ";
        $similar = mysqli_query($conn, $similar_sql);
        if ($similar->num_rows > 0) {
            while ($row = mysqli_fetch_array($similar)) {
                array_push($adminNotification, $row);
            }
        }
    }
 
    $hasNotification = [];

    $similar_sql = "SELECT * FROM ask_price WHERE user_id = '" . $id . "'  AND notify='received'";
    $similar = mysqli_query($conn, $similar_sql);
    if ($similar->num_rows > 0) {
        while ($row = mysqli_fetch_array($similar)) {
            array_push($hasNotification, $row);
        }
    }
    return count($hasNotification) + count($adminNotification);
}

if (isset($_POST['weDontHave'])) {
    $id = $_POST['id'];
    $code = $_POST['code'];
    $customer = $_POST['customer'];
    echo clearNotification($conn, $id, $code, $customer);
}

function clearNotification($conn, $id, $code, $customer)
{

    if ($id) {
        $sql = "UPDATE ask_price SET status= 'done' , notify = 'received',
             price = 'نداریم' 
             WHERE id = '$id'";

        $conn->query($sql);
    }

    if ($customer) {
        $created_at = date("Y-m-d H:i:s");
        $pattern_sql = "INSERT INTO prices (partnumber, price, user_id, customer_id, created_at, updated_at)
            VALUES ('" . $code . "', 'نداریم', '" . $_SESSION['id'] . "','" .  $customer . "','" . $created_at . "','" . $created_at . "')";

        if ($conn->query($pattern_sql) === TRUE) {
            return 'true';
        }
    }
}

if (isset($_POST['markUsRead'])) {
    $id = $_POST['id'];
    echo readNotification($conn, $id);
}

function readNotification($conn, $id)
{
    $sql = "UPDATE ask_price SET status= 'done' , notify = 'done',
             status = 'done' 
             WHERE id = '$id'";

    $conn->query($sql);
}
