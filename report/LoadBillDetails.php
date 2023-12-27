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
    $newBillNumber = getLastBillNumber() + 1;
    // $newCustomer =
    // $lastBillId = 
}

function getBillInfo($billId)
{
    $sql = "SELECT * FROM callcenter.bill WHERE bill_number = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $billId);

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
    $sql = "SELECT id, name, family, phone, car, address FROM callcenter.customer WHERE id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $customerId);

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }

    $stmt->close();
    $data['mode'] = 'update';
    return $data;
}

function getBillItems($bill_id)
{
    $sql = "SELECT * FROM callcenter.bill_details WHERE bill_id = ?";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("s", $bill_id);

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

function getLastBillNumber()
{
    $sql = "SELECT bill_number FROM callcenter.bill ORDER BY id DESC LIMIT 1";
    $stmt = CONN->prepare($sql);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['bill_number'];
    } else {
        return 0;
    }
}

function createCustomer($customerInfo)
{
    $nameParts = explode(' ', $customerInfo->name);
    $name = $nameParts[0] ?? '';
    $family = $nameParts[1] ?? '';

    $sql = "INSERT INTO callcenter.customer (name, family, phone, address, car) VALUES 
        ('$name', '$family', '$customerInfo->phone', '$customerInfo->address', '$customerInfo->car')";
    CONN->query($sql);
    $lastInsertedId = CONN->insert_id;
    return $lastInsertedId;
}

function createBill($billInfo, $customerId)
{
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO callcenter.bill (customer_id, bill_number, quantity, discount, tax, withdraw, total, bill_date, user_id, status) VALUES 
            ('$customerId','$billInfo->billNO', '$billInfo->quantity', '$billInfo->discount', '$billInfo->tax', '$billInfo->withdraw',
            '$billInfo->totalPrice', '$billInfo->date', '$user_id', 1)";
    CONN->query($sql);

    // Retrieve the last inserted ID
    $lastInsertedId = CONN->insert_id;

    // Return the last inserted ID
    return $lastInsertedId;
}