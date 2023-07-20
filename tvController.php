<?php
require_once './php/function.php';
require_once './php/jdf.php';
require_once './config/database.php';
if (filter_has_var(INPUT_POST, 'toggle')) {

    $sql = "SELECT * FROM shop.tv WHERE id='1'";
    $factor_result = mysqli_query($con, $sql);
    $tv = mysqli_fetch_assoc($factor_result);
    $status = $tv['status'];
    if ($status == 'on') {
        $sql = "UPDATE shop.tv SET status= 'off' WHERE id = '1'";
    } else {
        $sql = "UPDATE shop.tv SET status= 'on' WHERE id = '1'";
    }
    mysqli_query($con, $sql);
}
