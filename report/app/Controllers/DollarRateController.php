<?php
if (isset($_POST['status'])) {

    $status = $_POST['status'];
    $sql = "UPDATE shop.dollarrate SET status = '$status' WHERE id = 1";
    $conn->query($sql);
    $status = true;
}

if (isset($_POST['rate'])) {
    try {
        $rate = $_POST['rate'];
        $data = $_POST['date'];


        $sql = "UPDATE shop.dollarrate SET rate = '$rate', created_at = '$data' WHERE id = 1";
        $conn->query($sql);
        $status = true;
    } catch (\Throwable $th) {
        echo $th;
    }
}

$dollarRate  = getDollarRateInfo($conn);
$status = null;
function getDollarRateInfo($conn)
{
    $statement = "SELECT * FROM shop.dollarrate";
    $result = $conn->query($statement);

    $rate = [];
    while ($row = $result->fetch_assoc()) {
        $rate[] = $row;
    }
    return $rate;
}

