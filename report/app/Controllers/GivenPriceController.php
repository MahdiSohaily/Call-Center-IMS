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

    $results_arry = [
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

    $explodedCodes = array_unique($explodedCodes);

    foreach ($explodedCodes as $code) {
        $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $code . "%'";
        $result = mysqli_query($conn, $sql);

        $all_matched = [];
        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($all_matched, $item);
            }
        }

        if (count($all_matched)) {
            $existing_code[$code] = $all_matched;
        } else {
            array_push($results_arry['not_exist'], $code);
        }
    }

    $existing_code = [];
    foreach ($explodedCodes as $code) {
        $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $code . "%'";
        $result = mysqli_query($conn, $sql);

        $all_matched = [];
        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($all_matched, $item);
            }
        }

        if (count($all_matched)) {
            $existing_code[$code] = $all_matched;
        } else {
            array_push($results_arry['not_exist'], $code);
        }
    }

    $data = [];
    $relation_id = [];

    foreach ($explodedCodes as $code) {
        if (!in_array($code, $results_arry['not_exist'])) {
            $data[$code] = [];
            foreach ($existing_code[$code] as $item) {
                $relation_exist = isInRelation($conn, $item['id']);
                if ($relation_exist) {
                    if (!in_array($relation_exist, $relation_id)) {
                        array_push($relation_id, $relation_exist);
                        $data[$code][$item['partnumber']]['information'] = info($conn, $item['id']);
                        $data[$code][$item['partnumber']]['relation'] = relations($conn, $item['id']);
                        $data[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($data[$code][$item['partnumber']]['relation']['goods']), $relation_exist);
                        $data[$code][$item['partnumber']]['estelam'] =  estelam($conn, $item['partnumber']);
                    }
                } else {
                    $data[$code][$item['partnumber']]['information'] = info($conn, $item['id']);
                    $data[$code][$item['partnumber']]['relation'] = relations($conn, $item['id']);
                    $data[$code][$item['partnumber']]['givenPrice'] = givenPrice($conn, array_keys($data[$code][$item['partnumber']]['relation']['goods']));
                    $data[$code][$item['partnumber']]['estelam'] = estelam($conn, $item['partnumber']);
                }
            }
        }
    }

    return ([
        'explodedCodes' => $explodedCodes,
        'not_exist' => $results_arry['not_exist'],
        'existing' => $data,
        'customer' => $customer,
        'completeCode' => $completeCode,
        'notification' => $notification,
        'rates' => getSelectedRates($conn)
    ]);
}

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

function info($conn, $id)
{
    $sql = "SELECT pattern_id FROM similars WHERE nisha_id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);

    $isInRelation = null;
    if (mysqli_num_rows($result) > 0) {
        $isInRelation = mysqli_fetch_assoc($result);
    }


    $info = false;
    $cars = [];
    if ($isInRelation) {

        $sql = "SELECT * FROM patterns WHERE id = '" . $isInRelation['pattern_id'] . "'";
        $result = mysqli_query($conn, $sql);

        $info = null;
        if (mysqli_num_rows($result) > 0) {
            $info = mysqli_fetch_assoc($result);
        }

        if ($info['status_id'] !== 0) {
            $sql = "SELECT patterns.*, status.name AS  status_name FROM patterns INNER JOIN status ON status.id = patterns.status_id WHERE patterns.id = '" . $isInRelation['pattern_id'] . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $info = mysqli_fetch_assoc($result);
            }
        }

        $sql = "SELECT cars.name FROM patterncars INNER JOIN cars ON cars.id = patterncars.car_id WHERE patterncars.pattern_id = '" . $isInRelation['pattern_id'] . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($cars, $item['name']);
            }
        }
    }

    return $info ? ['relationInfo' => $info, 'cars' => $cars] : false;
}

function relations($conn, $id)
{
    $sql = "SELECT pattern_id FROM similars WHERE nisha_id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);

    $isInRelation = null;
    if (mysqli_num_rows($result) > 0) {
        $isInRelation = mysqli_fetch_assoc($result);
    }

    $relations = [];

    if ($isInRelation) {

        $sql = "SELECT yadakshop1402.nisha.* FROM yadakshop1402.nisha INNER JOIN similars ON similars.nisha_id = nisha.id WHERE similars.pattern_id = '" . $isInRelation['pattern_id'] . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($info = mysqli_fetch_assoc($result)) {
                array_push($relations, $info);
            }
        }
    } else {
        $sql = "SELECT * FROM yadakshop1402.nisha WHERE id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $relations[0] = mysqli_fetch_assoc($result);
        }
    }


    $existing = [];
    $stockinfo = [];
    $sortedGoods = [];
    foreach ($relations as $relation) {
        $existing[$relation['partnumber']] =  exist($conn, $relation['id'])['final'];
        $stockinfo[$relation['partnumber']] =  exist($conn, $relation['id'])['stockInfo'];
        $sortedGoods[$relation['partnumber']] = $relation;
    }

    arsort($existing);
    $sorted = [];
    foreach ($existing as $key => $value) {
        $sorted[$key] = getMax($value);
    }

    arsort($sorted);

    return ['goods' => $sortedGoods, 'existing' => $existing, 'sorted' => $sorted, 'stockInfo' => $stockinfo];
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
    $sql = "SELECT prices.price, prices.partnumber, customer.name, customer.id AS customerID, customer.family, users.id AS userID, prices.created_at
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

    return  $final_data;
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

function out($conn, $id)
{
    $out_sql = "SELECT qty FROM yadakshop1402.exitrecord WHERE qtyid = '" . $id . "'";
    $out_result = mysqli_query($conn, $out_sql);

    $result = null;
    if (mysqli_num_rows($out_result) > 0) {
        while ($row = mysqli_fetch_assoc($out_result)) {
            $result += $row['qty'];
        }
    }
    return $result;
}

function stockInfo($conn, $id, $brand, $date)
{

    $stockInfo_sql = "SELECT id FROM yadakshop1402.brand WHERE brand.name = '" . $brand . "'";
    $out_result = mysqli_query($conn, $stockInfo_sql);

    $brand_id = null;
    if (mysqli_num_rows($out_result) > 0) {
        $brand_id = mysqli_fetch_assoc($out_result);
    }

    $qtybank_sql = "SELECT qtybank.id, qtybank.qty, seller.name FROM yadakshop1402.qtybank INNER JOIN yadakshop1402.seller ON qtybank.seller = seller.id WHERE codeid = '" . $id . "' AND brand= '" . $brand_id['id'] . "'";
    $qtybank_data = mysqli_query($conn, $qtybank_sql);

    $result = [];

    if (mysqli_num_rows($qtybank_data) > 0) {
        while ($item = mysqli_fetch_assoc($qtybank_data)) {
            array_push($result, $item);
        }
    }

    $existing_record = [];
    $customers = [];
    foreach ($result as $key => $item) {

        $out_data = out($conn, $item['id']);
        $out =  $out_data ? (int) $out_data : 0;

        $item['qty'] = (int)($item['qty']) - $out;

        array_push($existing_record, $item);
        array_push($customers, $item['name']);
    }

    $customers = array_unique($customers);

    $final_result = [];

    foreach ($customers as $customer) {
        $total = 0;
        foreach ($existing_record as $record) {
            if ($customer === $record['name']) {
                $total += $record['qty'];
            }
        }
        $final_result[$customer] = [$total, $date];
    }


    return $final_result;
}

function exist($conn, $id)
{

    $data_sql = "SELECT yadakshop1402.qtybank.id, codeid, brand.name, qty, create_time FROM yadakshop1402.qtybank INNER JOIN yadakshop1402.brand ON brand.id = qtybank.brand WHERE codeid = '" . $id . "' ";
    $data_result = mysqli_query($conn, $data_sql);

    $result = [];
    if (mysqli_num_rows($data_result) > 0) {
        while ($item = mysqli_fetch_assoc($data_result)) {
            array_push($result, $item);
        }
    };

    $brands = [];
    $amount = [];
    $stockInfo = [];

    $modifiedResult = [];

    foreach ($result as $value) {
        $clone = $value;

        $out_data = out($conn, $clone['id']);
        $out =  $out_data;

        $clone['qty'] = (int)($clone['qty']) - $out;
        array_push($modifiedResult, $clone);

        array_push($brands, $value['name']);
    }

    $brands = array_unique($brands);

    foreach ($brands as $key => $value) {
        $item = $value;
        $total = 0;
        foreach ($modifiedResult as $key => $value) {
            if ($item == $value['name']) {
                $total += $value['qty'];
            }
            $stockInfo[$value['name']] =  stockInfo($conn, $id, $value['name'], $value['create_time']);
        }

        array_push($amount, $total);
    }
    $final = array_combine($brands, $amount);
    arsort($final);
    return ['stockInfo' => $stockInfo, 'final' => $final];
}

function getMax($array)
{
    $max = 0;
    foreach ($array as $k => $v) {
        $max = $max < $v ? $v : $max;
    }
    return $max;
}
