<?php
if (isset($_POST['operation'])) {
    $operation = $_POST['operation'];

    // Create a new Limit Alert object
    if ($operation == 'create') {
        $type = $_POST['type'];

        switch ($type) {
            case 's':
                
                break;
            case 'r':
                break;
        }
        // INSERT INVENTORY ALERT FOR SPECIFIC INVENTORY
        $stock_id = 9;
        $limit_sql = $conn->prepare("INSERT INTO good_limit_inventory (pattern_id, original, fake, user_id, stock_id) VALUES (?, ?, ?, ?, ?)");
        $limit_sql->bind_param('iiiii', $last_id, $original, $fake, $_SESSION['user_id'], $stock_id);
        $limit_sql->execute();


        // INSERT GOODS ALERT WITHIN ALL THE AVAILABLE STOCKS (GENERAL GOODS AMOUNT ALERT)
        $limit_sql = $conn->prepare("INSERT INTO good_limit_all (pattern_id, original, fake, user_id) VALUES (?, ?, ?, ?)");
        $limit_sql->bind_param('iiii', $last_id, $original_all, $fake_all, $_SESSION['user_id']);
        $limit_sql->execute();
    }


    // Updating the existing Limit Alerts
    if ($operation == 'update') {
        $type = $_POST['type'];

        switch ($type) {
            case 's':
                break;
            case 'r':
                break;
        }
        // Update the Inventories limit for goods alert for specific pattern
        $updateInventoryLimit = $conn->prepare("UPDATE good_limit_inventory SET original= ?, fake = ? WHERE pattern_id = ?");
        $updateInventoryLimit->bind_param('iii', $original, $fake, $pattern_id);
        $updateInventoryLimit->execute();


        // Update the over all alert for goods in specific relation
        $updateAllLimit = $conn->prepare("UPDATE good_limit_all SET original= ?, fake = ? WHERE pattern_id = ?");
        $updateAllLimit->bind_param('iii', $original_all, $fake_all, $pattern_id);
        $updateAllLimit->execute();
    }
}
