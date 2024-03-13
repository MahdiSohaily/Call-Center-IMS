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
    $nishaId = findRelation($goodID);

    if (!$nishaId) {
        // If good_id does not exist, proceed with insertion
        $sql = "INSERT INTO telegram.goods_for_sell (good_id, partNumber) VALUES (?, ?)";
        $stmt = CONN->prepare($sql);
        $stmt->bind_param('is', $goodID, $partNumber);

        // Execute the prepared statement
        if ($stmt->execute()) {
            return 'true'; // Insertion successful
        } else {
            return 'false'; // Insertion failed
        }
    } else {
        $relatedItems = getInRelationItems($nishaId);
        if ($relatedItems) {
            foreach ($relatedItems as $item) {
                if (checkIfAlreadyExist($item['partnumber'])) {
                    continue; //
                }

                $sql = "INSERT INTO telegram.goods_for_sell (good_id, partNumber) VALUES (?, ?)";
                $stmt = CONN->prepare($sql);
                $stmt->bind_param('ss', $item['id'], $item['partnumber']);
                $stmt->execute();
            }
            return 'true'; // Insertion successful
        }
    }
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


function findRelation($id)
{
    // Prepare and execute the SQL query
    $sql = "SELECT pattern_id FROM shop.similars WHERE nisha_id = '$id' LIMIT 1";
    $result = CONN->query($sql);

    // Check if there are any rows returned
    if ($result && $result->num_rows > 0) {
        // Fetch the first row and return the pattern_id
        $row = $result->fetch_assoc();
        return (int) $row['pattern_id']; // Convert to integer and return
    } else {
        // No rows found, return false
        return false;
    }
}

function getInRelationItems($nisha_id)
{
    // Fetch similar items based on the provided nisha_id
    $sql = "SELECT nisha_id FROM shop.similars WHERE pattern_id = '$nisha_id'";
    $result = CONN->query($sql);
    $goods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $all_ids = array_column($goods, 'nisha_id');

    if (count($all_ids) == 0) {
        return false;
    }

    // Prepare the list of IDs to use in the IN clause of the next query
    $idList = implode(',', $all_ids);

    // Fetch part numbers of the related items
    $partNumberSQL = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE id IN ($idList)";
    $partNumberResult = CONN->query($partNumberSQL);
    $partNumbers = mysqli_fetch_all($partNumberResult, MYSQLI_ASSOC);

    return ($partNumbers);
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
