<?php
require_once "./config/db_connection.php";
require_once "./app/partials/factors/helpers.php";
// Set the date to today and adjust the tome period to get todays factors
$date = date('Y-m-d H:i:s');
$startDate = date_create(date('Y-m-d H:i:s'));
$endDate = date_create(date('Y-m-d H:i:s'));

$endDate = $endDate->setTime(23, 59, 59);
$startDate = $startDate->setTime(1, 1, 0);

$end = date_format($endDate, "Y-m-d H:i:s");
$start = date_format($startDate, "Y-m-d H:i:s");

$factors = getFactors($start, $end);
