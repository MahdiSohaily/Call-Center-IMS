<?php
$billInfo = null;
$customerInfo = null;
$billItems = [];
$isCompleteFactor = false;

if (isset($_POST['BillId'])) {
    $bill_id = $_POST['BillId'];

    $details = getBillInfo($bill_id);
    if (!$details) {
        die('فاکتور شما در سیتستم موجود نیست');
    }

    if ($details['status']) {
        $isCompleteFactor = true;
    }

    $billInfo = [
        'id' => $bill_id,
        'billNO' => $details['bill_number'],
        'customer_id' => $details['customer_id'],
        'date' => $details['bill_date'],
        'total' => $details['total'],
        'quantity' => $details['quantity'],
        'tax' => $details['tax'],
        'discount' => $details['discount'],
        'description' => $details['description'],
        'withdraw' => $details['withdraw'],
    ];

    if ($billInfo['customer_id']) {
        $customerInfo = getCustomerInfo($billInfo['customer_id']);
    } else {
        $customerInfo = [
            'id' => null,
            'name' => null,
            'displayName' => 'null',
            'family' => null,
            'car' => null,
            'phone' => null,
            'address' => null,
            'mode' => 'create'
        ];
    }
    $billItems = getBillItems($billInfo['id'])[0]['billDetails'];
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
    $sql = "SELECT id, name, name AS displayName, family, phone, car, address FROM callcenter.customer WHERE id = ?";
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
