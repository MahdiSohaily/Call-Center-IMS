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
