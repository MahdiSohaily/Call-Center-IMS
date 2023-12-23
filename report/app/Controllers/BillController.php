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

if (isset($_POST['saveInvoice'])) {
    $billItems = json_decode($_POST['billItems']);
    $BillInfo = json_decode($_POST['BillInfo']);
    $customerInfo = json_decode($_POST['customerInfo']);

    $customerId = $customerInfo->id ?? null;
    try {

        CONN->begin_transaction();
        if ($customerId == null) {
            $customerId = createCustomer($customerInfo);
        } else {
            updateCustomer($customerInfo);
        }

        if ($customerId == null) {
            return false;
            die("Invalid customer");
        }

        $billId = createBill($BillInfo, $customerId);

        if ($billId == null) {
            return false;
            die("Invalid bill");
        }

        createBillItems($billId, $billItems);
        CONN->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        CONN->rollback();

        echo "error: " . $e;
    }

    // echo json_encode([$BillInfo, $customerInfo, $billItems]);
}

function createCustomer($customerInfo)
{
    $nameParts = explode(' ', $customerInfo->name);
    $name = $nameParts[0] ?? '';
    $family = $nameParts[1] ?? '';

    $sql = "INSERT INTO callcenter.customer (name, family, phone, address, car) VALUES 
        ('$name', '$family', '$customerInfo->phone', '$customerInfo->address', '$customerInfo->car')";
    CONN->query($sql);

    // Retrieve the last inserted ID
    $lastInsertedId = CONN->insert_id;

    // Return the last inserted ID
    return $lastInsertedId;
}

function updateCustomer($customerInfo)
{
    $nameParts = explode(' ', $customerInfo->name);
    $name = $nameParts[0] ?? '';
    $family = $nameParts[1] ?? '';

    $sql = "UPDATE callcenter.customer SET name = '$name', family = '$family', 
            phone = '$customerInfo->phone', address = '$customerInfo->address',
            car = '$customerInfo->car' WHERE id = '$customerInfo->id'";
    CONN->query($sql);
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

function createBillItems($billId, $billItems)
{
    // Prepared statement
    $sql = "INSERT INTO callcenter.bill_details (bill_id, nisha_id, partName, quantity, price_per) VALUES (?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = CONN->prepare($sql);

    foreach ($billItems as $item) :
        $id = getPartNumberId($item->partNumber);
        $stmt->bind_param("iisid", $billId, $id, $item->name, $item->quantity, $item->price);
        $stmt->execute();
    endforeach;

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
