<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');


if (isset($_POST['toggleActivation'])) {
    toggleActivation($_POST['rate_id'], $_POST['type']);
}



function toggleActivation($rate_id, $type)
{
    $sql = "UPDATE dollarrate SET status = ? WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->execute([$type, $rate_id]);
    echo true;
    exit;
}
