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
    $sql = "SELECT nisha.partnumber , stock.name AS stn,stock.id AS sti, nisha.id , seller.name , brand.name AS brn , qtybank.qty ,qtybank.des,qtybank.id AS qtyid,  qtybank.qty AS entqty 
            FROM qtybank 
            LEFT JOIN nisha ON qtybank.codeid=nisha.id
            LEFT JOIN seller ON qtybank.seller=seller.id
            LEFT JOIN stock ON qtybank.stock_id=stock.id
            LEFT JOIN brand ON qtybank.brand=brand.id
            WHERE partnumber LIKE '$pattern%'";

    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    array_filter($data, function ($item) {
        $finalQuantity = $item["entqty"];
        $sql2 = "SELECT qty FROM exitrecord WHERE qtyid = '" . $item["qtyid"] . "'";
        $fetchedData = CONN->query($sql2);


        if (mysqli_num_rows($fetchedData) > 0) {
            while ($row2 = mysqli_fetch_assoc($fetchedData)) {

                $finalQuantity =  $finalQuantity - $row2["qty"];
            }
        }

        if ($finalQuantity > 0) {
            return $item;
        }
    });


    return [...$data];
}
