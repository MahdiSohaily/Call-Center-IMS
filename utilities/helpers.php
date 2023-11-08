<?php
function getFirstLetters($string)
{
    // Trim the string and remove special characters
    $string = trim(preg_replace('/[^a-zA-Z0-9\sآ-ی]/u', '', $string));

    $words = preg_split('/\s+/u', $string);
    $firstLetters = '';

    if (count($words) === 1) {
        $firstLetters = mb_substr($words[0], 0, 2);
    } else {
        foreach ($words as $word) {
            $firstLetters .= mb_substr($word, 0, 1) . ' ';
        }
    }

    return trim($firstLetters);
}

function givenPrice($con)
{
    $sql = "SELECT 
prices.price, prices.partnumber, users.username,customer.id AS customerID, users.id as userID, prices.created_at, customer.name, customer.family
FROM ((shop.prices 
INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
ORDER BY prices.created_at DESC LIMIT 40";
    $result = mysqli_query($con, $sql);


    $givenPrices = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($givenPrices, $item);
        }
    }
    return  $givenPrices;
}

$datetime101 = new DateTime('2019-09-30 00:00:00');
$datetime102 = new DateTime('2019-09-30 00:00:00');
$datetime103 = new DateTime('2019-09-30 00:00:00');
$datetime104 = new DateTime('2019-09-30 00:00:00');
$datetime106 = new DateTime('2019-09-30 00:00:00');
$datetime107 = new DateTime('2019-09-30 00:00:00');
$datetimeMarjae = new DateTime('2019-09-30 00:00:00');

$sql = "SELECT * FROM incoming WHERE starttime IS NOT NULL AND time >= CURDATE() ";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user = $row['user'];
        $phone = $row['phone'];
        $starttime = $row['starttime'];
        $endtime = $row['endtime'];
        $xxx =   nishatimedef($starttime, $endtime);

        if ($user == 101) {
            $datetime101->add($xxx);
        }
        if ($user == 102) {
            $datetime102->add($xxx);
        }
        if ($user == 103) {
            $datetime103->add($xxx);
        }
        if ($user == 104) {
            $datetime104->add($xxx);
        }
        if ($user == 106) {
            $datetime106->add($xxx);
        }
        if ($user == 107) {
            $datetime107->add($xxx);
        }
    }
}
function compareDateIntervals($a, $b)
{
    if ($a->s == $b->s) {
        return 0;
    }
    return ($a->s < $b->s) ? -1 : 1;
}

$total101 = $datetimeMarjae->diff($datetime101);
$total102 = $datetimeMarjae->diff($datetime102);
$total103 = $datetimeMarjae->diff($datetime103);
$total104 = $datetimeMarjae->diff($datetime104);
$total106 = $datetimeMarjae->diff($datetime106);
$total107 = $datetimeMarjae->diff($datetime107);

$sortedData = [
    '101' => $total101,
    '102' => $total102,
    '103' => $total103,
    '104' => $total104,
    '106' => $total106,
    '107' => $total107
];

uasort($sortedData, 'compareDateIntervals');
