<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
if (isset($_POST['customer_search'])) {

    $pattern = $_POST['pattern'];


    echo json_encode(search_customer($pattern));
}

function search_customer($pattern)
{
    $similar_sql = "SELECT id, name, family, phone, address, car FROM callcenter.customer WHERE name LIKE '%$pattern%' OR family LIKE '%$pattern%' OR phone LIKE '%$pattern%'";

    $similar = CONN->query($similar_sql);
    $data = [];
    if ($similar->num_rows > 0) {
        while ($row = $similar->fetch_assoc()) {
            array_push($data, $row);
        }
    }
    return $data;
}

if (isset($_POST['partNumber'])) {

    $pattern = $_POST['partNumber'];
    echo json_encode(searchPartNumber($pattern));
}


function searchPartNumber($pattern)
{
    $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $pattern . "%'";
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}
