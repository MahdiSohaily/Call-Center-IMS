<?php
require_once('../../database/connect.php');


if (isset($_POST['operation']) && $_POST['operation'] == 'update') {
    $user = $_POST['user'];
    $data = json_decode($_POST['data'], true);

    $honda = $data['honda'];
    $kia = $data['kia'];
    $chines = $data['chaines'];

    $sql = "UPDATE shop.telegram_partner SET honda = '$honda', kia = '$kia', chines = '$chines' WHERE chat_id = '$user'";

    CONN->query($sql);
}
