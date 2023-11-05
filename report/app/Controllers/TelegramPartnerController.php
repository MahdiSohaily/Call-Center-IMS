<?php
$current_partners = getExistingTelegramPartners();

$categories = getCategories();

$partners_json = json_encode($current_partners, true);
function getExistingTelegramPartners()
{
    $sql = "SELECT * FROM shop.telegram_partner";

    $result = CONN->query($sql);

    // Initialize an array to store all the rows
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->close();
    }

    return $data;
}


function getCategories()
{
    $sql = "SELECT * FROM shop.partner_categories";

    $result = CONN->query($sql);

    // Initialize an array to store all the rows
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->close();
    }

    return $data;
}
