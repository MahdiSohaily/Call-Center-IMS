<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');


if (isset($_POST['deactivate'])) {
    deactivate($_POST['rate_id']);
}



function deactivate($rate_id)
{
    $sql = "UPDATE dollar_rates SET active = 0 WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->execute([$rate_id]);
    echo true;
    exit;
}
