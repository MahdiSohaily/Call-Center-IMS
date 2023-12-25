<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
if (isset($_POST['getUsers'])) {

    $pattern = $_POST['getUsers'];
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
    echo json_encode(getUsers($pattern));
}

function getUsers()
{
    $sql = "SELECT DISTINCT(user_id) FROM callcenter.bill ";
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}
