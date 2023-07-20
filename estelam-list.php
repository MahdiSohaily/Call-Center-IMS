<?php
require_once './layout/heroHeader.php';
$sql = "SELECT e.*, u.id As user_id, s.name AS seller_name
FROM estelam AS e
JOIN yadakshop1402.users AS u ON e.user = u.id
JOIN yadakshop1402.seller AS s ON e.seller = s.id
ORDER BY e.time DESC
LIMIT 600";

// Prepare the statement
$stmt = $pdo->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


function displayTimePassed($timePassed)
{
    $create = date($timePassed);
    $now = new DateTime(); // current date time
    $date_time = new DateTime($create); // date time from string

    $current_day = date_format($now, 'd');
    $data_day = date_format($date_time, 'd');

    $diff = $current_day - $data_day;

    $text = "  $diff روز قبل";
    return  $text;
}
?>

<div class="">
    <div class="flex">
        <h2 class="title">آخرین قیمت های گرفته شده از بازار</h2>
        <div class="px-24 flex items-center gap-2">
            <label for="search">جستجو</label>
            <input class="border py-2 px-3" type="text" name="search" id="search-bazar" onkeyup="searchBazar(this.value)">
            <i class="material-icons text-red-500 hover:cursor-pointer" onclick="searchByCustomer(this)" data-customer=''>close</i>
        </div>
    </div>
    <div class="box-keeper">
        <?php
        echo '<table class="min-w-full">';
        echo '<tr class="bg-violet-700">';
        echo '<th class="text-right px-3 text-white py-2">کد فنی</th>';
        echo '<th class="text-right px-3 text-white py-2">فروشنده</th>';
        echo '<th class="text-right px-3 text-white py-2">قیمت</th>';
        echo '<th class="text-right px-3 text-white py-2">کاربر ثبت کننده</th>';
        echo '<th class="text-right px-3 text-white py-2">زمان ثبت</th>';
        echo '</tr>';
        echo '<tbody id="results">';

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
                echo '<td class="p-3" colspan="5">' . displayTimePassed($date) . ' - ' . jdate('Y/m/d') . '</td>';
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
                <td><?php $timeString = $dateTime[1]; // Example time string
                    $adjustment = "-1 hour"; // Adjustment to subtract one hour

                    // Create a DateTime object from the time string
                    $time = DateTime::createFromFormat("H:i:s", $timeString);

                    // Adjust the time by subtracting one hour
                    $time->modify($adjustment);

                    // Format the adjusted time to display only the hour and minute
                    $formattedTime = $time->format("H:i");

                    echo $formattedTime; // Output: 14:30
                    ?></td>
            </tr>
        <?php
        }

        echo '</tbody>';
        echo '</table>';
        ?>
    </div>
</div>
<script>
    const input = document.getElementById('search-bazar');

    function searchByCustomer(element) {
        const customer_name = element.getAttribute('data-customer');
        searchBazar(customer_name);
        input.value = customer_name;
    }

    function searchBazar(pattern) {
        let superMode = 0;
        const resultBox = document.getElementById("results");

        pattern = pattern.replace(/\s/g, "");
        pattern = pattern.replace(/-/g, "");
        pattern = pattern.replace(/_/g, "");

        resultBox.innerHTML = `<tr class=''>
                <td colspan='14' class='py-10 text-center'> 
                    <img class=' block w-10 mx-auto h-auto' src='./report/public/img/loading.png' alt='loading'>
                    </td>
            </tr>`;
        var params = new URLSearchParams();
        params.append('pattern', pattern);

        axios.post("./estelam-list-ajax.php", params)
            .then(function(response) {
                resultBox.innerHTML = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>
<?php
require_once './layout/heroFooter.php';
