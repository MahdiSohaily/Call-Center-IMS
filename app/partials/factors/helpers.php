<?php

/**
 * Get the factors from database for specific time period
 * @param string $start is the starting time period
 * @param string $end is the ending time period
 */
function getFactors($start, $end, $user = null)
{
    $query = "SELECT
        shomarefaktor.*,
        bill.id as bill_id,
        CASE WHEN bill.bill_number IS NOT NULL THEN TRUE ELSE FALSE END AS exists_in_bill
        FROM
        shomarefaktor
        LEFT JOIN
        bill ON shomarefaktor.shomare = bill.bill_number
        WHERE
        shomarefaktor.time < '$end' AND shomarefaktor.time >= '$start'";

    if ($user !== null) {
        $query .= " AND shomarefaktor.user = '$user'";
    }

    $query .= " ORDER BY shomarefaktor.time DESC";
    $statement = DB_CONNECTION->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getCountFactorByUser($start, $end = null, $user = null)
{
    // Base query
    $sql = "SELECT COUNT(shomare) as count_shomare, user FROM shomarefaktor 
            WHERE time < '$end' AND time >= '$start'";
    if($user !== null) {
        $sql .= " AND user = '$user'";
    }

    $sql .= " GROUP BY user ORDER BY count_shomare DESC";

    // Append the WHERE clause based on the condition
    if ($end !== null) {
        $sql .= " ";
    } else {
        $sql .= " WHERE time >= CURDATE()";
    }

    // Prepare and execute the query
    $statement = DB_CONNECTION->prepare($sql);
    $statement->execute();

    // Fetch the result
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the result
    return $result;
}
