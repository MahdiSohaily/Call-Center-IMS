<?php

$billInfo = null;
$customerInfo = null;
$billItems = [];

if (isset($_POST['BillId'])) {
    $bill_id = $_POST['BillId'];

    $billInfo = getBillInfo($bill_id);
    if (isset($billInfo['customer_id'])) {
        $customerInfo = getCustomerInfo($billInfo['customer_id']);
    } else {
        $customerInfo = [
            'name' => null,
            'family' => null,
            'car' => null,
            'phone' => null,
            'address' => null,
        ];
    }
    $billItems = getBillItems($billInfo['id']);
} else {
    $billInfo = [
        'billNO' => null,
        'date' => null,
        'totalPrice' => 0,
        'quantity' => 0,
        'tax' => 0,
        'discount' => 0,
        'withdraw' => 0,
        'date' => '-'
    ];
    $incompleteBill = createBill($billInfo);
    $incompleteBillDetails = null;
}

function getBillInfo($billId)
{
    $sql = "SELECT * FROM callcenter.bill WHERE id = ?";
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

function createBill($billInfo)
{
    $sql = "INSERT INTO callcenter.bill (quantity, discount, tax, withdraw, total, bill_date, user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("dddddsi", $billInfo['quantity'], $billInfo['discount'], $billInfo['tax'], $billInfo['withdraw'], $billInfo['totalPrice'], $billInfo['date'], $_SESSION['user_id']);

    $stmt->execute();

    if ($stmt->errno) {
        return false;
    }
    $lastInsertedId = $stmt->insert_id;
    $stmt->close();

    return $lastInsertedId;
}
