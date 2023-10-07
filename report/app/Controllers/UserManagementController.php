<?php

$users_sql = "SELECT users.id, name, family, username, authorities.user_authorities AS auth FROM yadakshop1402.users AS users
INNER JOIN yadakshop1402.authorities AS authorities ON yadakshop1402.authorities.user_id = yadakshop1402.users.id";
$result = $conn->query($users_sql);

$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        array_push($users, $row);
    }
}

// $data = '{"usersManagement":true,"khorojkala-index":true,"vorodkala-index":true,"khorojkala-report":true,"vorodkala-report":true}';
// foreach ($users as $user) {
//     $users_sql = "INSERT INTO yadakshop1402.authorities (user_id, user_authorities) VALUES ('" . $user['id'] . "', '" . $data . "')";
//     $result = $conn->query($users_sql);
// }
