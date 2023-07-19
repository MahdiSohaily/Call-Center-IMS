<?php
require_once './php/function.php';
require_once './config/database.php';

function displayTimePassed($timePassed)
{
    $create = date($timePassed);
    $now = new DateTime(); // current date time
    $date_time = new DateTime($create); // date time from string
    $interval = $now->diff($date_time); // difference between two date times
    $days = $interval->format('%a'); // difference in days
    $hours = $interval->format('%h'); // difference in hours
    $minutes = $interval->format('%i'); // difference in minutes
    $seconds = $interval->format('%s'); // difference in seconds

    $text = '';

    if ($days) {
        $text .= " $days روز و ";
    }

    if ($hours) {
        $text .= "$hours ساعت ";
    }

    if (!$days && $minutes) {
        $text .= "$minutes دقیقه ";
    }

    if (!$days && !$hours && $seconds) {
        $text .= "$seconds ثانیه ";
    }

    return "$text قبل";
}

if (filter_has_var(INPUT_POST, 'pattern')) {
    $pattern = $_POST['pattern'];

    $sql = "SELECT e.*, u.id As user_id, s.name AS seller_name
    FROM estelam AS e
    JOIN yadakshop1402.users AS u ON e.user = u.id
    JOIN yadakshop1402.seller AS s ON e.seller = s.id
    WHERE LOWER(REPLACE(e.codename, ' ', '')) LIKE CONCAT('', LOWER(REPLACE(:pattern, ' ', '')), '%')
        OR REPLACE(s.name, ' ', '') LIKE CONCAT('%', LOWER(REPLACE(:pattern, ' ', '')), '%')
    ORDER BY e.time DESC
    LIMIT 250";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the value to the :pattern placeholder
    $stmt->bindValue(':pattern', $pattern);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $currentGroup = null;
    $bgColors = ['rgb(224 231 255)', 'rgb(236 254 255)']; // Array of background colors for date groups
    $bgColorIndex = 0;

    foreach ($results as $row) {
        $time = $row['time'];
        $partNumber = $row['codename'];
        $sellerName = $row['seller_name'];
        $price = $row['price'];
        $userId = $row['user_id'];

        // Explode the time value to separate date and time
        $dateTime = explode(' ', $time);
        $date = $dateTime[0];

        // Check if the group has changed
        if ($date !== $currentGroup) {
            // Update the current group
            $currentGroup = $date;

            // Get the background color for the current group
            $bgColor = $bgColors[$bgColorIndex % count($bgColors)];
            $bgColorIndex++;

            // Display a row for the new group with the background color
            echo '<tr class="bg-rose-400">';
            echo '<td class="p-3" colspan="5">' . displayTimePassed($time) . ' - ' . "<span class='direction:ltr' >$date </span>" . '</td>';
            echo '</tr>';
        }

        // Display the row for current entry with the same background color as the group
?>
        <tr style="background-color:<?php echo $bgColor ?>">
            <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $partNumber ?>'><?php echo $partNumber ?></td>
            <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $sellerName ?>'><?php echo $sellerName ?></td>
            <td><?php echo $price ?></td>
            <td>
                <img class="w-8 mt-1 rounded-full" src='<?php echo "../userimg/$userId.jpg" ?>' alt="" srcset="">
            </td>
            <td><?php echo $dateTime[1] ?></td>
        </tr>
<?php
    }
}
