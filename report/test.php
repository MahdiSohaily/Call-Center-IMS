<?php
require './database/connect.php';


$users_sql = "SELECT * FROM yadakshop1402.users";
$result = $conn->query($users_sql);

$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        array_push($users, $row);
    }
}

$data = '{
    "usersManagement":true,
    "khorojkala-index":true,
    "vorodkala-index":true,
    "khorojkala-report":true,
    "vorodkala-report":true,
    "transfer_index":true,
    "transfer_report":true,
    "goodLimitReport":true,
    "goodLimitReportAll":true,
    "shomaresh-index":true,
    "telegramProcess":true,
    "givePrice":true,
    "showRates":true,
    "relationships":true,
    "defineExchangeRate":true,
    "createUserProfile":true
}';
try {
    foreach ($users as $user) {
        print_r($user);
        $users_sql = "INSERT INTO yadakshop1402.authorities (user_id, user_authorities) VALUES ('" . $user['id'] . "', '" . $data . "')";
        $result = $conn->query($users_sql);
    }
} catch (\Throwable $th) {
    throw $th;
}
