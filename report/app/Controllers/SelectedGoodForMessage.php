<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
require_once('../../utilities/helper.php');

if (isset($_POST['selectedGoodForMessage'])) {
    $partNumber  = $_POST['partNumber'];

    $goodID = getGoodID($partNumber, $connection);
    addSelectedGoodForMessage($goodID, $partNumber);
}

function getGoodID($partNumber, $connection)
{
    // Prepare the SQL statement
    $sql = "SELECT id FROM shop.goods WHERE part_number = ? LIMIT 1";

    // Prepare the statement
    $statement = $connection->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("s", $partNumber);
    $statement->execute();

    // Store result
    $result = $statement->get_result();

    // Fetch the row
    $row = $result->fetch_assoc();

    // Return the value of 'id' column or null if not found
    return $row ? $row['id'] : null;
}




function addSelectedGoodForMessage($goodID, $partNumber)
{
    // Prepare the SQL statement
    $sql = "INSERT INTO shop.goods_for_sell (good_id, partNumber) VALUES (?, ?)";

    // Prepare the statement
    $statement = CONN->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("ss", $goodID, $partNumber);
    $result = $statement->execute();

    // Return true if the execution was successful, false otherwise
    return $result ? true : false;
}
