<?php
require_once('../../database/connect.php');


if (isset($_POST['operation']) && $_POST['operation'] == 'update') {
    $user = $_POST['user'];
    $data = json_decode($_POST['data'], true);

    print_r($data['honda']) . '&&&&&&&';
}
