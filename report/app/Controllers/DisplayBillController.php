<?php
require_once './app/Models/Bill.php';
if (isset($_GET['billNumber'])) {
    $billNumber = $_GET['billNumber'];
    $bill = new Bill();
    $BillInfo = $bill->getBill($billNumber);
    $billItems = [];
    $customerInfo = null;



    if ($BillInfo) {
        $billItems = $bill->getBillItems($BillInfo['id'])['billDetails'];
        $customerInfo = $bill->getCustomer($BillInfo['customer_id']);
    } else {
        echo "Bill not found";
        die();
    }
} else {
    echo "Invalid Request";
    die();
}
