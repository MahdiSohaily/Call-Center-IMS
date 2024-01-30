<?php
require_once './config/db_connection.php';

$totalUsers = getUsers();
$totalFactors = getFactors();
$totalGoods = getPurchasedGoods();
$totalSold = getSoldGoods();

function getUsers()
{
    $stmt = DB_CONNECTION->prepare("SELECT COUNT(id) as total FROM yadakshop1402.users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
}

function getFactors()
{
    $stmt = DB_CONNECTION->prepare("SELECT COUNT(id) as total FROM callcenter.bill");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
}

function getPurchasedGoods()
{
    $stmt = DB_CONNECTION->prepare("SELECT COUNT(id) as total FROM yadakshop1402.qtybank");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
}

function getSoldGoods()
{
    $stmt = DB_CONNECTION->prepare("SELECT COUNT(id) as total FROM yadakshop1402.exitrecord");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
}


function getCallCenterUsers()
{
    $stmt = DB_CONNECTION->prepare("SELECT * FROM yadakshop1402.users WHERE internal ORDER BY internal");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        'answeredCall' => 0,
        'successRate' => 0,
    ];
}


$sqlTotal = DB_CONNECTION->prepare("SELECT * FROM incoming WHERE starttime IS NOT NULL AND time >= CURDATE()");

$sqlTotal->execute();
$resultTotal = $sqlTotal->fetchAll(PDO::FETCH_ASSOC);

if ($resultTotal) {
    foreach ($resultTotal as $row) {
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

$sqlReceived = DB_CONNECTION->prepare("SELECT * FROM incoming WHERE time >= CURDATE()");

$sqlReceived->execute();

$resultReceived = $sqlReceived->fetchAll(PDO::FETCH_ASSOC);

if ($resultReceived) {
    foreach ($resultReceived as $row) {
        $user = $row['user'];
        if (array_key_exists($user, $datetimeData)) {
            $datetimeData[$user]['receivedCall'] += 1;
        }
    }
}

$sqlCurrentHour = DB_CONNECTION->prepare("SELECT * FROM incoming WHERE starttime IS NOT NULL AND time >= CURDATE() AND HOUR(starttime) = :time");

$sqlCurrentHour->bindParam(':time', (int)date('G'));
$sqlCurrentHour->execute();
$resultCurrentHour = $sqlCurrentHour->fetchAll(PDO::FETCH_ASSOC);

if ($resultCurrentHour) {
    foreach ($resultCurrentHour as $row) {
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

foreach ($datetimeData as &$data) {
    if ($data['receivedCall'] !== 0)
        $data['successRate'] = floor(($data['answeredCall'] * 100) / $data['receivedCall']);
}

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
