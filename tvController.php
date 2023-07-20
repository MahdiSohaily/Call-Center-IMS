<?php
require_once './php/function.php';
require_once './php/jdf.php';
require_once './config/database.php';
if (filter_has_var(INPUT_POST, 'toggle')) {
    echo 'Hello';
    $sql = "UPDATE shop.tv SET status= 'off' WHERE id = '1'";
    mysqli_query($con, $sql);
}
