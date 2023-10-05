<?php

$users_sql = "SELECT id, name, family, username FROM yadakshop1402.users";
$result = $conn->query($users_sql);

$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        array_push($users, $row);
    }
}
