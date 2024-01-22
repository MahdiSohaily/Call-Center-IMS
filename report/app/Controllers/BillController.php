<?php
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');

// START ------------------ THE FIRST STEP OF FACTOR ( CREATE INCOMPLETE BILL ) -----------------------------
if (isset($_POST['create_incomplete_bill'])) {

    $incompleteBillId = createBill([
        'customer_id' => 0,
        'bill_number' => 0,
        'quantity' => 0,
        'discount' => 0,
        'tax' => 0,
        'withdraw' => 0,
        'total' => 0,
        'date' => $_POST['date'],
        'totalInWords' => null
    ]);

    $incompleteBillDetails = createBillItemsTable(
        $incompleteBillId,
        '[{
        "id": 5892295,
        "partName": "اسم قطعه",
        "price_per": 0,
        "quantity": 1,
        "max": "undefined",
        "partNumber": "NOTPART"}]'
    );

    echo $incompleteBillId;
}

function createBill($billInfo)
{
    $sql = "INSERT INTO callcenter.bill 
                        (customer_id, bill_number, quantity, discount, tax, withdraw, total, bill_date, user_id, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param(
        "dddddddsi",
        $billInfo['customer_id'],
        $billInfo['bill_number'],
        $billInfo['quantity'],
        $billInfo['discount'],
        $billInfo['tax'],
        $billInfo['withdraw'],
        $billInfo['total'],
        $billInfo['date'],
        $_SESSION['user_id']
    );

    $stmt->execute();

    if ($stmt->errno) {
        return false;
    }
    $lastInsertedId = $stmt->insert_id;
    $stmt->close();

    return $lastInsertedId;
}

function createBillItemsTable($billId, $billItems)
{
    $sql = "INSERT INTO callcenter.bill_details (bill_id, billDetails) VALUES (?, ?)";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param("is", $billId, $billItems);
    $stmt->execute();
    $stmt->close();
}
// END ------------------ THE FIRST STEP OF FACTOR ( CREATE INCOMPLETE BILL ) -----------------------------









// START ------------------ THE SEARCHING FOR EXISTING CUSTOMER IN CUSTOMER LIST -----------------------------
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
// END ------------------ THE SEARCHING FOR EXISTING CUSTOMER IN CUSTOMER LIST -----------------------------













// START ------------------ SEARCH FOR GOODS BASE ON THE REGISTERED NISHA PART NUMBERS IN DATABASE -----------------------------
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
// END ------------------ SEARCH FOR GOODS BASE ON THE REGISTERED NISHA PART NUMBERS IN DATABASE -----------------------------









//START ------------------ SEARCH FOR GOODS BASE ON OUR EXISTING GOODS IN STOCK -----------------------------
if (isset($_POST['searchInStock'])) {
    $pattern = $_POST['searchInStock'];
    echo json_encode(searchPartNumberInStock($pattern));
}

function searchPartNumberInStock($pattern)
{
    $sql = "SELECT
            qtybank.id AS id,
            nisha.id AS nisha_id,
            nisha.partnumber,
            stock.id AS stock_id,
            stock.name AS stock_name,
            seller.name AS seller_name,
            brand.name AS brand_name,
            qtybank.qty AS existing,
            qtybank.des AS description
        FROM
            yadakshop1402.qtybank
        LEFT JOIN
            yadakshop1402.nisha ON qtybank.codeid = nisha.id
        LEFT JOIN
            yadakshop1402.seller ON qtybank.seller = seller.id
        LEFT JOIN
            yadakshop1402.stock ON qtybank.stock_id = stock.id
        LEFT JOIN
            yadakshop1402.brand ON qtybank.brand = brand.id
        WHERE
            partnumber LIKE '$pattern%'
        ORDER BY
            nisha.partnumber DESC";


    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    $sanitized = [];

    foreach ($data as $item) {
        $finalQuantity = $item["existing"];

        $sql2 = "SELECT qty FROM yadakshop1402.exitrecord WHERE qtyid = '" . $item["id"] . "'";
        $fetchedData = CONN->query($sql2);


        if (mysqli_num_rows($fetchedData) > 0) {
            while ($row2 = mysqli_fetch_assoc($fetchedData)) {
                $finalQuantity -= $row2["qty"];
            }
        }

        $item['existing'] = $finalQuantity;
        if ($finalQuantity > 0) {
            array_push($sanitized, $item);
        }
    }
    return $sanitized;
}
//END ------------------ SEARCH FOR GOODS BASE ON OUR EXISTING GOODS IN STOCK -----------------------------










//START ------------------ GETTING THE LAST ISSUED FACTOR NUMBER -----------------------------
if (isset($_POST['getFactorNumber'])) {
    echo getFactorNumber();
}

function getFactorNumber()
{
    $sql = "SELECT shomare FROM callcenter.shomarefaktor ORDER BY id DESC LIMIT 1";
    $stmt = CONN->prepare($sql);

    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['shomare'] + 1;
    } else {
        return 1;
    }
}
//END ------------------ GETTING THE LAST ISSUED FACTOR NUMBER -----------------------------




if (isset($_POST['isPhoneExist'])) {
    $phone = $_POST['phone'];
    echo json_encode(checkPhoneNumber($phone));
}

function checkPhoneNumber($phone)
{
    $sql = "SELECT id , name, family FROM callcenter.customer WHERE phone = ? LIMIT 1";
    $stmt = CONN->prepare($sql);
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return 0;
    }
}




//START ------------------ SAVE THE INVOICE AND ITS DETAILS IN THE DATABASE -----------------------------
if (isset($_POST['saveInvoice'])) {
    $billItems = json_decode($_POST['billItems']);
    $BillInfo = json_decode($_POST['BillInfo']);
    $customerInfo = json_decode($_POST['customerInfo']);
    $customerPhone = $customerInfo->phone ?? null;

    try {
        CONN->begin_transaction();
        $customer_id = null;
        if ($customerInfo->mode == 'update') {
            $customer_id = $customerInfo->id;
            updateCustomer($customerInfo);
        } else {
            $customer_id = createCustomer($customerInfo);
        }
        if (!$customer_id) {
            echo 'false';
            die();
        }
        $bill_number = registerFactorNumber(getFactorNumber(), $customerInfo->name . ' ' . $customerInfo->family);
        makeBillCompleted($BillInfo, $customer_id, $bill_number);
        updateBillItems($BillInfo, $billItems);
        echo $bill_number;
        CONN->commit();
    } catch (Exception $e) {
        CONN->rollback();
    }
}
function getCustomerId($customer)
{
    $sql = "SELECT id FROM callcenter.customer WHERE 
                phone = '$customer->phone'
                ORDER BY id DESC LIMIT 1";
    $stmt = CONN->prepare($sql);

    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['id'];
    } else {
        return false;
    }
}
//END ------------------ SAVE THE INVOICE AND ITS DETAILS IN THE DATABASE -----------------------------

if (isset($_POST['saveIncompleteForm'])) {
    $customerInfo = json_decode($_POST['customer_info']);
    $bill_info = json_decode($_POST['bill_info']);
    $bill_items = json_decode($_POST['bill_items']);
    $customerPhone = $customerInfo->phone ?? null;
    try {
        CONN->begin_transaction();
        $customer_id = null;
        if ($customerInfo->mode == 'update') {
            $customer_id = $customerInfo->id;
            updateCustomer($customerInfo);
        } else {
            $customer_id = createCustomer($customerInfo);
        }

        UpdateIncompleteBill($bill_info, $customer_id);
        updateBillItems($bill_info, $bill_items);
        CONN->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        CONN->rollback();

        echo "error: " . $e;
    }
}

if (isset($_POST['saveCompleteForm'])) {
    $customerInfo = json_decode($_POST['customer_info']);
    $bill_info = json_decode($_POST['bill_info']);
    $bill_items = json_decode($_POST['bill_items']);
    $customerPhone = $customerInfo->phone ?? null;
    try {
        CONN->begin_transaction();
        $customer_id = getCustomerId($customerInfo);
        if ($customerInfo->name != null) {
            if (!$customer_id) {
                $customer_id = createCustomer($customerInfo);
            } else {
                updateCustomer($customerInfo, $customer_id);
            }
        }
        UpdateBill($bill_info, $customer_id);
        updateBillItems($bill_info, $bill_items);
        CONN->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        CONN->rollback();

        echo "error: " . $e;
    }
}

function registerFactorNumber($billNO, $customer)
{
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO callcenter.shomarefaktor (shomare, kharidar, user) VALUES ('$billNO', '$customer', '$user_id')";
    CONN->query($sql);

    // Retrieve the last inserted ID
    $lastInsertedId = CONN->insert_id;

    // Retrieve the shomare property of the last inserted item
    $result = CONN->query("SELECT shomare FROM callcenter.shomarefaktor WHERE id = '$lastInsertedId'");

    // Check if the query was successful
    if ($result) {
        // Fetch the shomare property
        $row = $result->fetch_assoc();
        $shomare = $row['shomare'];

        // Return the shomare property
        return $shomare;
    } else {
        // Return an error or handle the error as needed
        return false;
    }
}

function createCustomer($customerInfo)
{
    // Check if the phone number already exists
    $existingCustomerQuery = "SELECT id FROM callcenter.customer WHERE phone = '$customerInfo->phone'";
    $result = CONN->query($existingCustomerQuery);

    // If the phone number exists, return the customer ID
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return false;
    } else {
        // If the phone number doesn't exist, create a new customer
        $sql = "INSERT INTO callcenter.customer (name, family, phone, address, car) VALUES 
            ('$customerInfo->name', '$customerInfo->family', '$customerInfo->phone', '$customerInfo->address', '$customerInfo->car')";
        CONN->query($sql);

        // Retrieve the last inserted ID
        $lastInsertedId = CONN->insert_id;

        // Return the last inserted ID
        return $lastInsertedId;
    }
}

function updateCustomer($customerInfo)
{
    $sql = "UPDATE callcenter.customer SET name = '$customerInfo->name', family = '$customerInfo->family', 
            phone = '$customerInfo->phone', address = '$customerInfo->address',
            car = '$customerInfo->car' WHERE id = '$customerInfo->id'";
    CONN->query($sql);
}

function makeBillCompleted($billInfo, $customerId, $bill_number)
{
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE callcenter.bill SET 
                customer_id = '$customerId',
                bill_number = '$bill_number',
                quantity = '$billInfo->quantity',
                discount = '$billInfo->discount',
                tax = '$billInfo->tax',
                withdraw = '$billInfo->withdraw',
                total = '$billInfo->totalPrice',
                bill_date = '$billInfo->date',
                user_id = '$user_id',
                status = 1
                WHERE id = '$billInfo->id'";

    CONN->query($sql);

    // Check if the update was successful
    $success = CONN->affected_rows > 0;

    // Return success status
    return $success;
}

function UpdateBill($billInfo, $customerId)
{
    try {
        $user_id = $_SESSION['user_id'];

        $sql = "UPDATE callcenter.bill SET 
            customer_id = '$customerId',
            quantity = '$billInfo->quantity',
            discount = '$billInfo->discount',
            tax = '$billInfo->tax',
            withdraw = '$billInfo->withdraw',
            total = '$billInfo->totalPrice',
            bill_date = '$billInfo->date',
            user_id = '$user_id',
            status = 1
            WHERE id = '$billInfo->id'";

        CONN->query($sql);

        // Check if the update was successful
        $success = CONN->affected_rows > 0;

        // Return success status
        return $success;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function UpdateIncompleteBill($billInfo, $customerId)
{
    try {
        $user_id = $_SESSION['user_id'];

        $sql = "UPDATE callcenter.bill SET 
            customer_id = '$customerId',
            quantity = '$billInfo->quantity',
            discount = '$billInfo->discount',
            tax = '$billInfo->tax',
            withdraw = '$billInfo->withdraw',
            total = '$billInfo->totalPrice',
            bill_date = '$billInfo->date',
            user_id = '$user_id',
            status = 0
            WHERE id = '$billInfo->id'";

        CONN->query($sql);

        // Check if the update was successful
        $success = CONN->affected_rows > 0;

        // Return success status
        return $success;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function createBillItems($billId, $billItems)
{
    // Prepared statement
    $sql = "UPDATE callcenter.bill_details SET bill_id = ?, billDetails = ? WHERE bill_id = $billId";

    // Create a prepared statement
    $stmt = CONN->prepare($sql);


    $stmt->bind_param("is", $billId, $billItems);
    $stmt->execute();

    // Close the statement
    $stmt->close();
}

function updateBillItems($billInfo, $billItems)
{
    // Prepared statement
    $sql = "UPDATE callcenter.bill_details SET billDetails = ? WHERE bill_id = ?";

    // Create a prepared statement
    $stmt = CONN->prepare($sql);

    $billItems = json_encode($billItems);

    // Bind parameters
    $stmt->bind_param("si", $billItems, $billInfo->id);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();
}

function getPartNumberId($partNumber)
{
    $sql = "SELECT id FROM yadakshop1402.nisha WHERE partnumber = '$partNumber'";
    $result = CONN->query($sql);

    // Check if there is a result
    if ($result->num_rows > 0) {
        // Fetch the first row and return the 'id' column as a number
        $row = $result->fetch_assoc();
        return (int)$row['id'];
    } else {
        // No result found
        return null;
    }
}
