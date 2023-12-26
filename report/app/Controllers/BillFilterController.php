<?php
$users = getUsers();
define('MONTHS', ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند']);
define('DAYS', [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29]);




function getUsers()
{
    $sql = "SELECT DISTINCT(user_id) AS id, name, family FROM callcenter.bill
            INNER JOIN yadakshop1402.users ON user_id = yadakshop1402.users.id ORDER BY bill.created_at DESC";
    $result = CONN->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    return $data;
}
