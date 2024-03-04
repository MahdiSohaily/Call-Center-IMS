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

        insertNewRate($rate, $data);
    } catch (\Throwable $th) {
        echo $th;
    }
}

$dollarRate  = getDollarRateInfo($conn);
$status = null;

function getDollarRateInfo($conn)
{
    $statement = "SELECT * FROM shop.dollarrate ORDER BY created_at DESC LIMIT 2";
    $result = $conn->query($statement);

    $rate = [];
    while ($row = $result->fetch_assoc()) {
        $rate[] = $row;
    }
    return $rate;
}

function insertNewRate($rate, $date)
{
    $sql = "INSERT INTO shop.dollarrate (rate, created_at) VALUES ('$rate', '$date')";
    $result = CONN->query($sql);
    return $result !== false; // Returns true if insertion was successful, false otherwise
}
