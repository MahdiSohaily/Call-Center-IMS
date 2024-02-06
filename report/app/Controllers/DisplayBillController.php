<?php
require_once './app/Models/Bill.php';
if (isset($_GET['billNumber'])) {
    $billNumber = $_GET['billNumber'];
    $bill = new Bill();
    $billData = $bill->getBill($billNumber);
    if ($billData) {
        $billItems = $bill->getBillItems($billNumber);
        $billData['items'] = $billItems;
        echo json_encode($billData);
    } else {
        echo "Bill not found";
    }
} else {
    echo "Invalid Request";
    die();
}
