<?php
// Initialize the session
session_name("MyAppSession");
session_start();
require_once('../../database/connect.php');
require_once('../../utilities/helper.php');

if (isset($_POST['selectedGoodForMessage'])) {
    $partNumber  = $_POST['partNumber'];

    $goodID = getGoodID($partNumber);
    echo addSelectedGoodForMessage($goodID, $partNumber);
}

function getGoodID($partNumber)
{
    // Prepare the SQL statement
    $sql = "SELECT id FROM yadakshop1402.nisha WHERE partnumber = ? LIMIT 1";

    // Prepare the statement
    $statement = CONN->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("s", $partNumber);
    $statement->execute();

    $result = $statement->get_result();

    $row = $result->fetch_assoc();

    return $row ? $row['id'] : null;
}

function addSelectedGoodForMessage($goodID, $partNumber)
{
    if (checkIfAlreadyExist($partNumber)) {
        return false;
    }
    // Prepare the SQL statement
    $sql = "INSERT INTO telegram.goods_for_sell (good_id, partNumber) VALUES (?, ?)";

    // Prepare the statement
    $statement = CONN->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("ss", $goodID, $partNumber);
    $result = $statement->execute();

    // Return true if the execution was successful, false otherwise
    return $result ? true : false;
}

function checkIfAlreadyExist($partNumber)
{
    // Prepare the SQL statement
    $sql = "SELECT * FROM telegram.goods_for_sell WHERE partNumber = ?";

    // Prepare the statement
    $statement = CONN->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("s", $partNumber);
    $statement->execute();

    // Store result
    $result = $statement->get_result();

    // Check if any rows were returned
    return $result->num_rows > 0;
}


if (isset($_POST['deleteGood'])) {
    $partNumber  = $_POST['partNumber'];
    echo deleteGood($partNumber);
}

function deleteGood($partNumber)
{
    // Prepare the SQL statement
    $sql = "DELETE FROM telegram.goods_for_sell WHERE partNumber = ?";

    // Prepare the statement
    $statement = CONN->prepare($sql);

    // Bind parameters and execute the statement
    $statement->bind_param("s", $partNumber);
    $result = $statement->execute();

    // Return true if the execution was successful, false otherwise
    return $result ? true : false;
}
