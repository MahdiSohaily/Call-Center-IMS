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

// Fetch distinct users from the database
$sqlDistinctUsers = "SELECT DISTINCT user FROM incoming";
$resultDistinctUsers = mysqli_query($con, $sqlDistinctUsers);

$users = [];
if ($resultDistinctUsers) {
    while ($row = mysqli_fetch_assoc($resultDistinctUsers)) {
        $users[] = $row['user'];
    }
}

$datetimeData = [];

// Initialize datetime data array for each user
foreach ($users as $user) {
    $datetimeData[$user] = [
        'total' => 0,
        'currentHour' => 0,
        'receivedCall' => 0,
        'answeredCall' => 0
    ];
}

$sqlTotal = "SELECT * FROM incoming 
             WHERE starttime IS NOT NULL 
             AND time >= '2023-12-20 13:16:18'";
// $sqlTotal = "SELECT * FROM incoming 
//              WHERE starttime IS NOT NULL 
//              AND time >= CURDATE()";
$resultTotal = mysqli_query($con, $sqlTotal);

if ($resultTotal) {
    while ($row = mysqli_fetch_assoc($resultTotal)) {
        $user = $row['user'];
        $starttime = strtotime($row['starttime']);
        $endtime = strtotime($row['endtime']);

        // Ensure valid start and end times
        if ($starttime !== false && $endtime !== false) {
            // Check if the array key exists before accessing it
            if (array_key_exists($user, $datetimeData) && array_key_exists('total', $datetimeData[$user])) {
                $datetimeData[$user]['total'] += ($endtime - $starttime);
                $datetimeData[$user]['answeredCall'] += 1;
            }
        }
    }
}



$sqlReceived = "SELECT * FROM incoming 
             WHERE time >= '2023-12-20 13:16:18'";
$resultReceived = mysqli_query($con, $sqlReceived);

if ($resultReceived) {
    while ($row = mysqli_fetch_assoc($resultReceived)) {
        $user = $row['user'];

        if (array_key_exists($user, $datetimeData)) {
            $datetimeData[$user]['receivedCall'] += 1;
        }
    }
}

print_r(json_encode($datetimeData));

$sqlCurrentHour = "SELECT * FROM incoming 
                   WHERE starttime IS NOT NULL 
                   AND time >= CURDATE() 
                   AND HOUR(starttime) = " . (int)date('G');
$resultCurrentHour = mysqli_query($con, $sqlCurrentHour);

if ($resultCurrentHour) {
    while ($row = mysqli_fetch_assoc($resultCurrentHour)) {
        $user = $row['user'];
        $starttime = strtotime($row['starttime']);
        $endtime = strtotime($row['endtime']);

        // Ensure valid start and end times
        if ($starttime !== false && $endtime !== false) {
            // Check if the array key exists before accessing it
            if (array_key_exists($user, $datetimeData) && array_key_exists('currentHour', $datetimeData[$user])) {
                $datetimeData[$user]['currentHour'] += ($endtime - $starttime);
            }
        }
    }
}

// Sort the users based on total call times
uasort($datetimeData, 'compareTotalCallTimes');
uasort($datetimeData, 'compareTotalCallTimes2');

function compareTotalCallTimes($a, $b)
{
    if ($a['total'] == $b['total']) {
        return 0;
    }

    return ($a['total'] > $b['total']) ? -1 : 1;
}

function compareTotalCallTimes2($a, $b)
{
    if ($a['currentHour'] == $b['currentHour']) {
        return 0;
    }

    return ($a['currentHour'] > $b['currentHour']) ? -1 : 1;
}

function formatTimeWithUnits($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;

    $formattedTime = '';
    if ($hours > 0) {
        $formattedTime .= $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ';
    }
    if ($minutes > 0) {
        $formattedTime .= $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ';
    }
    if ($seconds > 0) {
        $formattedTime .= $seconds . ' second' . ($seconds > 1 ? 's' : '');
    }

    return trim($formattedTime);
}
