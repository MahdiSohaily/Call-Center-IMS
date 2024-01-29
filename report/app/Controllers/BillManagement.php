<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
if (isset($_POST['getUsers'])) {

    $pattern = $_POST['getUsers'];

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
    echo json_encode(getUsers($pattern));
}

function getUsers()
{
    $sql = "SELECT DISTINCT(user_id) AS id, name, family FROM callcenter.bill
            INNER JOIN yadakshop1402.users ON user_id = yadakshop1402.users.id ORDER BY bill.created_at DESC";
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}

if (isset($_POST['getUserCompleteBills'])) {
    $user = $_POST['user'];
    $date = $_POST['date'];

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
    echo json_encode(getUsersCompleteBills($user, $date));
}

function getUsersCompleteBills($user, $date)
{
    if ($user == 'all') {
        $sql = "SELECT customer.name, customer.family, bill.id, bill.bill_number, bill.bill_date, bill.total, bill.user_id
                FROM callcenter.bill
                INNER JOIN callcenter.customer ON customer_id = callcenter.customer.id
                WHERE DATE(bill.created_at) = '$date'
                AND status = 1
                ORDER BY bill.created_at DESC";
    } else {
        $sql = "SELECT customer.name, customer.family, bill.id, bill.bill_number, bill.bill_date, bill.total, bill.user_id
                FROM callcenter.bill
                INNER JOIN callcenter.customer ON customer_id = callcenter.customer.id
                WHERE bill.user_id = '$user'
                AND DATE(bill.created_at) = '$date'
                AND status = 1
                ORDER BY bill.created_at DESC";
    }

    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}

if (isset($_POST['getUserIncompleteBills'])) {
    $user = $_POST['user'];
    $date = $_POST['date'];

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
    echo json_encode(getUsersInCompleteBills($user, $date));
}

function getUsersInCompleteBills($user, $date)
{
    if ($user == 'all') {
        $sql = "SELECT customer.name, customer.family, bill.id, bill.bill_number, bill.bill_date, bill.total, bill.quantity, bill.user_id
                FROM callcenter.bill
                LEFT JOIN callcenter.customer ON customer_id = callcenter.customer.id
                WHERE DATE(bill.created_at) = '$date'
                AND status = 0
                ORDER BY bill.created_at DESC";
    } else {
        $sql = "SELECT customer.name, customer.family, bill.id, bill.bill_number, bill.bill_date, bill.total, bill.quantity, bill.user_id
                FROM callcenter.bill
                LEFT JOIN callcenter.customer ON customer_id = callcenter.customer.id
                WHERE bill.user_id = '$user'
                AND DATE(bill.created_at) = '$date'
                AND status = 0
                ORDER BY bill.created_at DESC";
    }
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}

if (isset($_POST['deleteFactor'])) {
    $factor_id = $_POST['factorId'];
    echo (json_encode(deleteFactor($factor_id)));
}

function deleteFactor($factor_id)
{
    try {
        CONN->begin_transaction();

        $sql = "DELETE FROM callcenter.bill WHERE id = '$factor_id'";
        CONN->query($sql);

        $billDetailsSQL = "DELETE FROM callcenter.bill_details WHERE bill_id = '$factor_id'";
        CONN->query($billDetailsSQL);

        CONN->commit();

        return true;
    } catch (Exception $e) {
        echo $e->getMessage();
        CONN->rollBack();

        return false;
    }
}

if (isset($_POST['searchForBill'])) {
    $pattern = $_POST['pattern'];
    $mode = $_POST['mode'];

    $customers = getMatchedCustomers($pattern);

    echo (json_encode(getMatchedBills($customers, $mode)));
}

function getMatchedCustomers($pattern)
{
    $sql = "SELECT id FROM callcenter.customer WHERE name LIKE '$pattern%' OR family LIKE '$pattern%'";

    $result = CONN->query($sql); // Assuming $conn is your database connection object

    $customersId = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($customersId, $row['id']);
        }
    }

    return $customersId;
}

function getMatchedBills($customers, $mode)
{
    try {
        // Assuming $conn is your mysqli database connection object
        $sql = "SELECT customer.name, customer.family, bill.id, bill.bill_number, bill.bill_date, bill.total, bill.quantity, bill.user_id
            FROM callcenter.bill
            LEFT JOIN callcenter.customer ON customer_id = callcenter.customer.id
            WHERE customer_id IN (" . implode(',', $customers) . ") 
            AND status = '$mode'
            ORDER BY bill.created_at DESC";

        $result = CONN->query($sql); // Use $conn instead of CONN

        $bills = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($bills, $row);
            }
        }

        return $bills;
    } catch (\Throwable $th) {
        return []; //
    }
}
