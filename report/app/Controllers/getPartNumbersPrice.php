<?php
require_once('../../database/connect.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
if (filter_has_var(INPUT_POST, 'codes')) {
    $codes = $_POST['codes'];
    sanitizeData($codes);
    echo json_encode(getPrice($codes));
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
        if (strlen($code) > 4) {
            return  $code;
        }
    });

    // Remove duplicate codes from results array
    $codes = ($explodedCodes);
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
                $avgPrice = round((intval($mobis) * 110) / 243.5);
                $desiredValue =  round($avgPrice * 1.1);
            } elseif (empty($mobis)) {
                $avgPrice = round((intval($gen) * 110) / 243.5);
                $desiredValue = round($avgPrice * 1.1);
            } else {
                if ($gen > $mobis) {
                    $avgPrice = round((intval($mobis) * 110) / 243.5);
                    $desiredValue =  round($avgPrice * 1.1);
                } else {
                    $avgPrice = round((intval($gen) * 110) / 243.5);
                    $desiredValue = round($avgPrice * 1.1);
                }
            }
            array_push($prices, $desiredValue . "\n");
        } else {
            array_push($prices, "\n");
        }
    }
    return $prices;
}
