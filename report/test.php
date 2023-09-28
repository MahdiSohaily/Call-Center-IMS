<?php
session_start();
require_once './database/connect.php';
if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    // Create a new Limit Alert object
    if ($operation == 'create') {
        $type = $_POST['type'];
        $id = $_POST['id'];

        $original = $_POST['original'];
        $fake = $_POST['fake'];

        $original_all = $_POST['original_all'];
        $fake_all = $_POST['fake_all'];
        switch ($type) {
            case 's':
                createStockLimitSingle($id, $original, $fake);
                createOverallLimitSingle($id, $original_all, $fake_all);
                break;
            case 'r':
                createStockLimitRelation($id, $original, $fake,);
                createOverallLimitRelation($id, $original_all, $fake_all);
                break;
        }

        echo true;
    }


    // Updating the existing Limit Alerts
    if ($operation == 'update') {
        $type = $_POST['type'];
        $id = $_POST['id'];

        $original = $_POST['original'];
        $fake = $_POST['fake'];

        $original_all = $_POST['original_all'];
        $fake_all = $_POST['fake_all'];

        switch ($type) {
            case 's':
                updateStockLimitSingle($id, $original, $fake,);
                updateOverallLimitSingle($id, $original_all, $fake_all);
                break;
            case 'r':
                updateStockLimitRelation($id, $original, $fake,);
                updateOverallLimitRelation($id, $original_all, $fake_all);
                break;
        }
        echo true;
    }
}


function createStockLimitSingle($id, $original, $fake)
{
    // INSERT INVENTORY ALERT FOR SPECIFIC INVENTORY
    $stock_id = 9;
    $limit_sql = CONN->prepare("INSERT INTO good_limit_inventory (nisha_id, original, fake, user_id, stock_id) VALUES (?, ?, ?, ?, ?)");
    $limit_sql->bind_param('iiiii', $id, $original, $fake, $_SESSION['user_id'], $stock_id);
    $limit_sql->execute();
}

function createOverallLimitSingle($id, $original_all, $fake_all)
{
    // INSERT GOODS ALERT WITHIN ALL THE AVAILABLE STOCKS (GENERAL GOODS AMOUNT ALERT)
    $limit_sql = CONN->prepare("INSERT INTO good_limit_all (nisha_id, original, fake, user_id) VALUES (?, ?, ?, ?)");
    $limit_sql->bind_param('iiii', $id, $original_all, $fake_all, $_SESSION['user_id']);
    $limit_sql->execute();
}

function createStockLimitRelation($id, $original, $fake)
{
    // INSERT INVENTORY ALERT FOR SPECIFIC INVENTORY
    $stock_id = 9;
    $limit_sql = CONN->prepare("INSERT INTO good_limit_inventory (pattern_id, original, fake, user_id, stock_id) VALUES (?, ?, ?, ?, ?)");
    $limit_sql->bind_param('iiiii', $id, $original, $fake, $_SESSION['user_id'], $stock_id);
    $limit_sql->execute();
}
function createOverallLimitRelation($id, $original_all, $fake_all)
{
    // INSERT GOODS ALERT WITHIN ALL THE AVAILABLE STOCKS (GENERAL GOODS AMOUNT ALERT)
    $limit_sql = CONN->prepare("INSERT INTO good_limit_all (pattern_id, original, fake, user_id) VALUES (?, ?, ?, ?)");
    $limit_sql->bind_param('iiii', $id, $original_all, $fake_all, $_SESSION['user_id']);
    $limit_sql->execute();
}


function updateStockLimitSingle($id, $original, $fake)
{
    // Update the Inventories limit for goods alert for specific pattern
    $updateInventoryLimit = CONN->prepare("UPDATE good_limit_inventory SET original= ?, fake = ? WHERE nisha_id = ?");
    $updateInventoryLimit->bind_param('iii', $original, $fake, $id);
    $updateInventoryLimit->execute();
}
function updateOverallLimitSingle($id, $original_all, $fake_all)
{
    // Update the over all alert for goods in specific relation
    $updateAllLimit = CONN->prepare("UPDATE good_limit_all SET original= ?, fake = ? WHERE nisha_id = ?");
    $updateAllLimit->bind_param('iii', $original_all, $fake_all, $id);
    $updateAllLimit->execute();
}

function updateStockLimitRelation($id, $original, $fake)
{
    // Update the Inventories limit for goods alert for specific pattern
    $updateInventoryLimit = CONN->prepare("UPDATE good_limit_inventory SET original= ?, fake = ? WHERE pattern_id = ?");
    $updateInventoryLimit->bind_param('iii', $original, $fake, $id);
    $updateInventoryLimit->execute();
}
function updateOverallLimitRelation($id, $original_all, $fake_all)
{
    // Update the over all alert for goods in specific relation
    $updateAllLimit = CONN->prepare("UPDATE good_limit_all SET original= ?, fake = ? WHERE pattern_id = ?");
    $updateAllLimit->bind_param('iii', $original_all, $fake_all, $id);
    $updateAllLimit->execute();
}
