<?php
require_once './php/function.php';
require_once './config/database.php';

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
    $condition = true;
    if (count($results)) {
        foreach ($results as $row2) {

            $code = $row2['codename'];
            $seller = $row2['seller_name'];
            $price = $row2['price'];
            $user_id = $row2['user_id'];
            $date_time = explode(' ', $row2['time']);
            $fullDate = $date_time[0];
            $time = $date_time[1];

            $date = explode('-', $fullDate);

            $conditionValidator = $date[2];
            if ($conditionValidator !== $currentGroup) {
                // Update the current group
                $currentGroup = $conditionValidator;
                $condition = !$condition;
            }
?>
            <tr style="background-color:<?php echo $condition ? 'rgb(255 237 213)' : 'rgb(226 232 240)' ?>">
                <td class="hover:cursor-pointer text-teal-400" onclick="searchByCustomer(this)" data-customer='<?php echo $code ?>'><?php echo $code ?></td>
                <td class="hover:cursor-pointer text-teal-400" onclick="searchByCustomer(this)" data-customer='<?php echo $seller ?>'><?php echo $seller ?></td>
                <td><?php echo $price ?></td>
                <td>
                    <img class="w-8 rounded-full" src='<?php echo "../userimg/$user_id.jpg" ?>' alt="" srcset="">
                </td>
                <td>
                    <?php
                    $create = date($fullDate);


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

                    echo "$text قبل";
                    ?>
                </td>
                <td><?php echo $time ?></td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">مورد مشابهی در پایگاه داده پیدا نشد</td>
        </tr>
<?php
    }
}
