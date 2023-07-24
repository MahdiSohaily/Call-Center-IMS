<?php
require_once './php/function.php';
require_once './config/database.php';
require_once './php/jdf.php';


function displayTimePassed($timePassed)
{
    $create = date($timePassed);
    $now = new DateTime(); // current date time
    $date_time = new DateTime($create); // date time from string

    $current_day = date_format($now, 'd');
    $data_day = date_format($date_time, 'd');

    $diff = $current_day - $data_day;

    if ($diff == 0) {
        $text = "امروز";
    } else {
        $text = "  $diff روز قبل";
    }

    return  $text;
}

if (filter_has_var(INPUT_POST, 'pattern')) :
    $pattern = $_POST['pattern'];

    $sql = "SELECT e.*, u.id As user_id, s.name AS seller_name
    FROM estelam AS e
    JOIN yadakshop1402.users AS u ON e.user = u.id
    JOIN yadakshop1402.seller AS s ON e.seller = s.id
    WHERE LOWER(REPLACE(e.codename, ' ', '')) LIKE CONCAT('', LOWER(REPLACE(:pattern, ' ', '')), '%')
        OR REPLACE(s.name, ' ', '') LIKE CONCAT('%', LOWER(REPLACE(:pattern, ' ', '')), '%')
    ORDER BY e.time DESC
    LIMIT 600";

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

    foreach ($results as $row) :
        $id = $row['id'];
        $time = $row['time'];
        $partNumber = $row['codename'];
        $sellerName = $row['seller_name'];
        $price = $row['price'];
        $userId = $row['user_id'];

        // Explode the time value to separate date and time
        $dateTime = explode(' ', $time);
        $date = $dateTime[0];

        // Check if the group has changed
        if ($date !== $currentGroup) :
            // Update the current group
            $currentGroup = $date;

            // Get the background color for the current group
            $bgColor = $bgColors[$bgColorIndex % count($bgColors)];
            $bgColorIndex++;

            // Display a row for the new group with the background color
?>
            <tr class="bg-rose-400">
                <td class="p-3" colspan="6"><?php echo displayTimePassed($date) . ' - ' . jdate('Y/m/d', strtotime($date)) ?></td>
            </tr>
        <?php
        endif;
        // Display the row for current entry with the same background color as the group
        ?>
        <tr id="row-<?php echo $id ?>" style="background-color:<?php echo $bgColor ?>">
            <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $partNumber ?>'><?php echo $partNumber ?></td>
            <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $sellerName ?>'><?php echo $sellerName ?></td>
            <td><?php echo $price ?></td>
            <td>
                <img class="w-8 mt-1 rounded-full" src='<?php echo "../userimg/$userId.jpg" ?>' alt="" srcset="">
            </td>
            <td><?php $timeString = $dateTime[1]; // Example time string
                $adjustment = "-1 hour"; // Adjustment to subtract one hour

                // Create a DateTime object from the time string
                $time = DateTime::createFromFormat("H:i:s", $timeString);

                // Adjust the time by subtracting one hour
                $time->modify($adjustment);

                // Format the adjusted time to display only the hour and minute
                $formattedTime = $time->format("H:i");

                echo $formattedTime; // Output: 14:30
                ?>
            </td>
            <td>
                <i onclick="editItem(this)" data-item='<?php echo $id ?>' class="material-icons hover:cursor-pointer text-indigo-600">edit</i>
                <i onclick="deleteItem(this)" data-item='<?php echo $id ?>' class="material-icons hover:cursor-pointer text-red-600">delete</i>
            </td>
        </tr>
<?php
    endforeach;
endif;
