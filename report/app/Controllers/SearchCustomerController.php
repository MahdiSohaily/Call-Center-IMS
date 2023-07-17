<?php
require_once('../../database/connect.php');
if (filter_var(INPUT_POST,'pattern')) {
    $pattern = $_POST['pattern'];
    $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $pattern . "%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
            $rates = mysqli_query($conn, $rates_sql);

            $partNumber = $item['partnumber'];
            $price = $item['price'];
            $avgPrice = round(($price * 110) / 243.5);
            $weight = round($item['weight'], 2);
            $mobis = $item['mobis'];
            $korea = $item['korea'];
            $status = null;

            if ($mobis == "0.00") {
                $status = "NO-Price";
            } elseif ($mobis == "-") {
                $status = "NO-Mobis";
            } elseif ($mobis == NULL) {
                $status = "Request";
            } else {
                $status = "YES-Mobis";
            }
?>