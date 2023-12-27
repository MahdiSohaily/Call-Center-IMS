<?php

$billInfo = null;
$customerInfo = null;
$billItems = [];

if (isset($_POST['BillId'])) {
    $bill_id = $_POST['BillId'];

    $billInfo = getBillInfo($bill_id);
    $customerInfo = getCustomerInfo($billInfo['CustomerId']);
    $billItems = getBillItems($bill_id);
} else {
}


function getBillInfo($billId)
{
    $sql = "SELECT * FROM callcenter.bill WHEREbill_id = '$billId'";
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}


function getCustomerInfo($customerId)
{
}

function getBillItems($bill_id)
{
}
