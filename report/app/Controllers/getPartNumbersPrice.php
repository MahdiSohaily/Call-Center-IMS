<?php
require_once('../../database/connect.php');

if (filter_has_var(INPUT_POST, 'codes')) {
    $codes = $_POST['codes'];
    sanitizeData($codes);
    echo json_encode(getPrice($codes));
} else {
    echo 'Here';
}


function sanitizeData(&$codes)
{

    $explodedCodes = explode("\n", $codes);

    $explodedCodes = array_map(function ($code) {
        if (strlen($code) > 0) {
            return  preg_replace('/[^a-z0-9]/i', '', $code);
        }
    }, $explodedCodes);

    $explodedCodes = array_filter($explodedCodes, function ($code) {
        if (strlen($code) > 6) {
            return  $code;
        }
    });

    // Remove duplicate codes from results array
    $codes = array_unique($explodedCodes);
}

function getPrice($codes)
{
    $prices = [];
    foreach ($codes as $code) {

        $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber = '$code'";
        $result = CONN->query($sql);
        $item = mysqli_fetch_assoc($result);

        if (!empty($item['id'])) {
            $gen = $item['price'];
            $mobis = $item['mobis'];
            if (empty($gen)) {
                $desiredValue = $mobis;
            } elseif (empty($mobis)) {
                $desiredValue = $gen;
            } else {
                $desiredValue = $gen > $mobis ? $mobis : $gen;
            }
            array_push($prices, $desiredValue . "\n");
        } else {
            array_push($prices, "\n");
        }
    }
    return $prices;
}
