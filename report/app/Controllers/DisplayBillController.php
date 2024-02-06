<?php
require_once './app/Models/Bill.php';
if (isset($_GET['billNumber'])) {
    $billNumber = $_GET['billNumber'];
    $bill = new Bill();
    $BillInfo = $bill->getBill($billNumber);
    $billItems = [];



    if ($BillInfo) {
        $billItems = $bill->getBillItems($BillInfo['id'])['billDetails'];
    } else {
        echo "Bill not found";
        die();
    }
} else {
    echo "Invalid Request";
    die();
}
