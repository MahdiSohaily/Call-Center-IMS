<?php

$billInfo = null;
$customerInfo = null;
$billItems = [];

if (isset($_POST['BillId'])) {
    $bill_id = $_POST['BillId'];

    $billInfo = getBillInfo($bill_id);
    $customerInfo = getCustomerInfo($billInfo['customer_id']);
    $billItems = getBillItems($billInfo['id']);
} else {
    print_r($_REQUEST);
    echo "Please select";
}


function getBillInfo($billId)
{
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM callcenter.bill WHERE bill_number = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $billId); // Assuming $billId is a string, adjust accordingly if it's an integer

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        // Fetch the first row if there are results
        $data = $result->fetch_assoc();
    }

    $stmt->close();
    return $data;
}



function getCustomerInfo($customerId)
{
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM callcenter.customer WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $customerId); // Assuming $billId is a string, adjust accordingly if it's an integer

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        // Fetch the first row if there are results
        $data = $result->fetch_assoc();
    }

    $stmt->close();
    return $data;
}

function getBillItems($bill_id)
{
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM callcenter.bill_details WHERE bill_id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $bill_id); // Assuming $billId is a string, adjust accordingly if it's an integer

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $stmt->close();
    return $data;
}
