<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');


if (isset($_POST['toggleActivation'])) {
    toggleActivation($_POST['rate_id'], $_POST['type']);
}

if (isset($_POST['getItem'])) {
    $Item = getItem($_POST['rate_id']);

    echo json_encode($Item);
}

if (isset($_POST['updateItem'])) {

    $id = $_POST['id'];
    $rate = $_POST['rate'];
    $date = $_POST['date'];

    echo updateItem($id, $rate, $date);
}



function toggleActivation($rate_id, $type)
{
    $sql = "UPDATE dollarrate SET status = ? WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->execute([$type, $rate_id]);
    echo true;
    exit;
}

function getItem($rate_id)
{
    $sql = "SELECT * FROM shop.dollarrate WHERE id = '$rate_id'";
    $result = CONN->query($sql);
    return $result->fetch_assoc();
}

function updateItem($id, $rate, $date)
{
    $sql = "UPDATE shop.dollarrate SET rate = '$rate', created_at = '$date' WHERE id = '$id'";
    $result = CONN->query($sql);
    return $result !== false; // Returns true if insertion was successful, false otherwise
}
