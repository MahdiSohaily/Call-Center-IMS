<?php
$isValidCustomer = false;
$customer_info = null;
$finalResult = null;


if (filter_has_var(INPUT_POST, 'givenPrice') && filter_has_var(INPUT_POST, 'user')) {
    // check if a customer is already specified or not !!!! 1 is the ID of the ordered customer!!!
    $customer = empty($_POST['customer']) ? 1 : $_POST['customer'];

    // remove all the special characters from the user input
    $code = htmlspecialchars($_POST['code']);

    // Setting the user ID who have submitted the form
    $_SESSION["user_id"] = $_POST['user'];

    // Check if the requested is coming from the notification page
    $notification_id = filter_has_var(INPUT_POST, 'notification') ? $_POST['notification'] : null;

    $customer_sql = "SELECT * FROM callcenter.customer WHERE id = '" . $customer . "'";
    $result = mysqli_query($conn, $customer_sql);
    if (mysqli_num_rows($result) > 0) {
        $isValidCustomer = true;
        $customer_info = $result->fetch_assoc();
        $completeCode = $code;
        $finalResult = (setup_loading($conn, $customer, $completeCode, $notification_id));
    }
}

function setup_loading($conn, $customer, $completeCode, $notification = null)
{
    $explodedCodes = explode("\n", $completeCode);

    $results_array = [
        'not_exist' => [],
        'existing' => [],
    ];

    $explodedCodes = array_map(function ($code) {
        if (strlen($code) > 0) {
            return  preg_replace('/[^a-z0-9]/i', '', $code);
        }
    }, $explodedCodes);

    $explodedCodes = array_filter($explodedCodes, function ($code) {
        if (strlen($code) > 5) {
            return  $code;
        }
    });

    // Remove duplicate codes from results array
    $explodedCodes = array_unique($explodedCodes);

    $existing_code = []; // this array will hold the id and partNumber of the existing codes in DB
    foreach ($explodedCodes as $code) {
        $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $code . "%'";
        $result = mysqli_query($conn, $sql);

        $all_matched = [];
        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($all_matched, $item);
            }

            $existing_code[$code] = $all_matched;
        } else {
            array_push($results_array['not_exist'], $code); //Adding nonexisting codes to the final result array's not_exist index Line NO: 34
        }
    }

    $itemDetails = [];
    $relation_id = [];
    foreach ($explodedCodes as $code) {
        if (!in_array($code, $results_array['not_exist'])) {
            $itemDetails[$code] = [];
            foreach ($existing_code[$code] as $item) {

                // Check every matched good's Id If they have relationship and,
                // avoid operation for items in the same relationship
                $relation_exist = isInRelation($conn, $item['id']);

                if ($relation_exist) {

                    if (!in_array($relation_exist, $relation_id)) {

                        array_push($relation_id, $relation_exist); // if a new relation exists -> put it in the result array

                        $itemDetails[$code][$item['partnumber']]['information'] = info($conn, $relation_exist);
                        $itemDetails[$code][$item['partnumber']]['relation'] = relations($conn, $relation_exist, true);
                        $itemDetails[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($itemDetails[$code][$item['partnumber']]['relation']['goods']), $relation_exist);
                        $itemDetails[$code][$item['partnumber']]['estelam'] =  estelam($conn, $item['partnumber']);
                    }
                } else {
                    $itemDetails[$code][$item['partnumber']]['information'] = info($conn);
                    $itemDetails[$code][$item['partnumber']]['relation'] = relations($conn, $item['id'], false);
                    $itemDetails[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($itemDetails[$code][$item['partnumber']]['relation']['goods']));
                    $itemDetails[$code][$item['partnumber']]['estelam'] = estelam($conn, $item['partnumber']);
                }
            }
        }
    }


    // Custom comparison function to sort inner arrays by values in descending order
    function customSort($a, $b)
    {
        $sumA = $a['relation']['amount']; // Calculate the sum of values in $a
        $sumB = $b['relation']['amount']; // Calculate the sum of values in $b

        // Compare the sums in descending order
        if ($sumA == $sumB) {
            return 0;
        }
        return ($sumA > $sumB) ? -1 : 1;
    }


    foreach ($itemDetails as &$record) {

        uasort($record, 'customSort'); // Sort the inner array by values
    }

    return ([
        'explodedCodes' => $explodedCodes,
        'not_exist' => $results_array['not_exist'],
        'existing' => $itemDetails,
        'customer' => $customer,
        'completeCode' => $completeCode,
        'notification' => $notification,
        'rates' => getSelectedRates($conn)
    ]);
}

/**
 * @param Connection to the database
 * @return array of rates selected to be used in the goods report table
 */
function getSelectedRates($conn)
{

    $sql = "SELECT amount, status FROM rates WHERE selected = '1' ORDER BY amount ASC";
    $result = mysqli_query($conn, $sql);

    $rates = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($rates, $item);
        }
    }

    return $rates;
}

/**
 * @param Connection to the database
 * @param int $id is the id of the good to check if it has a relationship
 * @return int if the good has a relationship return the id of the relationship
 */
function isInRelation($conn, $id)
{
    $sql = "SELECT pattern_id FROM similars WHERE nisha_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            return $item['pattern_id'];
        }
    }
    return false;
}

/**
 * @param Connection to the database
 * @param int $id is the id of specified good
 * @return int $relation_exist
 * @return array of information about the good
 */
function info($conn, $relation_exist = null)
{
    $info = false;
    $cars = [];
    if ($relation_exist) {

        $sql = "SELECT * FROM patterns WHERE id = '" . $relation_exist . "'";
        $result = mysqli_query($conn, $sql);

        $info = null;
        if (mysqli_num_rows($result) > 0) {
            $info = mysqli_fetch_assoc($result);
        }

        if ($info['status_id'] !== 0) {
            $sql = "SELECT patterns.*, status.name AS  status_name FROM patterns INNER JOIN status ON status.id = patterns.status_id WHERE patterns.id = '" . $relation_exist . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $info = mysqli_fetch_assoc($result);
            }
        }

        $sql = "SELECT cars.name FROM patterncars INNER JOIN cars ON cars.id = patterncars.car_id WHERE patterncars.pattern_id = '" . $relation_exist . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($cars, $item['name']);
            }
        }
    }

    return $info ? ['relationInfo' => $info, 'cars' => $cars] : false;
}

function relations($conn, $id, $condition)
{
    $relations = [];

    if ($condition) {
        $sql = "SELECT yadakshop1402.nisha.partnumber FROM yadakshop1402.nisha INNER JOIN similars ON similars.nisha_id = nisha.id WHERE similars.pattern_id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($info = mysqli_fetch_assoc($result)) {
                array_push($relations, $info);
            }
        }
    } else {
        $sql = "SELECT partnumber FROM yadakshop1402.nisha WHERE id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $relations[0] = mysqli_fetch_assoc($result);
        }
    }

    $goods = array_column($relations, 'partnumber');
    $existing = getStockInfo($goods);

    $amount = 0;
    foreach ($existing as $record) {
        $amount += $record['details']['allOver'];
    }
    return ['goods' => $existing, 'amount' => $amount];
}

function givenPrice($conn, $codes, $relation_exist = null)
{
    $codes = array_filter($codes, function ($item) {
        return strtolower($item);
    });
    $ordared_price = [];


    if ($relation_exist) {
        $out_sql = "SELECT patterns.price, patterns.created_at FROM patterns WHERE id = '" . $relation_exist . "'";
        $out_result = mysqli_query($conn, $out_sql);

        if (mysqli_num_rows($out_result) > 0) {
            $ordared_price = mysqli_fetch_assoc($out_result);
        }
        $ordared_price['ordered'] = true;
    }

    $givenPrices = [];
    $sql = "SELECT  prices.id, prices.price, prices.partnumber, customer.name, customer.id AS customerID, customer.family, users.id AS userID, prices.created_at
    FROM ((prices 
    INNER JOIN callcenter.customer ON customer.id = prices.customer_id)
    INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
    WHERE partnumber IN ('" . implode("','", $codes) . "')
    ORDER BY created_at DESC LIMIT 7";

    $result = mysqli_query($conn, $sql);
    while ($item = mysqli_fetch_assoc($result))
        array_push($givenPrices, $item);

    $givenPrices = array_filter($givenPrices, function ($item) {

        if ($item !== null && count($item) > 0) {
            return $item;
        }
    });

    $unsortedData = [];
    foreach ($givenPrices as $item) {
        array_push($unsortedData, $item);
    }

    array_push($unsortedData, $ordared_price);

    if ($relation_exist) {
        usort($unsortedData, function ($a, $b) {
            return $a['created_at'] < $b['created_at'];
        });
    }
    $final_data = $relation_exist ? $unsortedData : $givenPrices;

    $filtered_data = array_filter($final_data, function ($item) {
        return is_array($item) && isset($item['price']) && $item['price'] !== '';
    });

    return  $filtered_data;
}

function estelam($conn, $code)
{
    $code = strtolower($code);
    $sql = "SELECT * FROM callcenter.estelam INNER JOIN yadakshop1402.seller ON seller.id = estelam.seller WHERE codename LIKE '" . $code . "%' ORDER BY time ASC LIMIT 7;";
    $result = mysqli_query($conn, $sql);


    $estelam = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($estelam, $item);
        }
    }

    return $estelam;
}

function sortGoods($a, $b)
{
    return  $b['details']["allOver"] - $a['details']["allOver"];
}


function getStockInfo($codes)
{
    $statement = CONN->prepare("SELECT * FROM yadakshop1402.nisha WHERE partnumber = ?");
    $goods = array();
    foreach ($codes as $code) {
        $statement->bind_param('s', $code);
        $statement->execute();
        $record = $statement->get_result();
        $ids = array();
        $item = null;
        while ($result = $record->fetch_assoc()) {
            array_push($ids, $result['id']);
            $item = $result;
        }
        $goods[$code] = ['information' => $item, 'details' => getEntranceRecord($ids)];
    }
    uasort($goods, "sortGoods");

    return $goods;
}

function getEntranceRecord($partNumbers)
{

    $statement = CONN->prepare("SELECT yadakshop1402.qtybank.id, codeid, brand.name AS brand_name, qty, invoice_date, seller.name As seller_name
    FROM (( yadakshop1402.qtybank 
    INNER JOIN yadakshop1402.brand ON brand.id = qtybank.brand )
    INNER JOIN yadakshop1402.seller ON seller.id = qtybank.seller)
    WHERE codeid = ?");

    $data = array();
    foreach ($partNumbers as $partNumber) {
        $statement->bind_param('i', $partNumber);
        $statement->execute();
        $records = $statement->get_result();
        while ($result = $records->fetch_assoc()) {
            array_push($data, $result);
        }
    }

    return getExitRecords($data);
}


function getExitRecords($entrance)
{
    $statement = CONN->prepare("SELECT qty FROM yadakshop1402.exitrecord WHERE qtyid = ?");

    $data = array();
    foreach ($entrance as $record) {
        $statement->bind_param('i', $record['id']);
        $statement->execute();
        $records = $statement->get_result();
        $quantity = 0;
        while ($result = $records->fetch_assoc()) {
            $quantity += $result['qty'];
        }
        $record['qty'] -= $quantity;
        if ($record['qty'] > 0) {
            array_push($data, $record);
        }
        // getFinalAmount($result, $record['qty']);
    }
    $derived = getFinalAmount($data);
    return ['ExistDetails' => $data, 'finalAmount' => $derived, 'allOver' => array_sum($derived)];
}

function getFinalAmount($data)
{
    // Create an associative array to store the sum of qty for each brand_name
    $brandQtySum = array();

    // Iterate through the data and sum the qty for each brand_name
    foreach ($data as $record) {
        $brandName = $record["brand_name"];
        $qty = $record["qty"];
        if (array_key_exists($brandName, $brandQtySum)) {
            $brandQtySum[$brandName] += $qty;
        } else {
            $brandQtySum[$brandName] = $qty;
        }
    }

    uasort($brandQtySum, "sortByBrandNameQTY");

    return $brandQtySum;
}

function sortByBrandNameQTY($a, $b)
{
    return $b - $a;
}
