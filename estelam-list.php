<?php
require_once './layout/heroHeader.php';
$sql = "SELECT e.*, u.id As user_id, s.name AS seller_name
FROM estelam AS e
JOIN yadakshop1402.users AS u ON e.user = u.id
JOIN yadakshop1402.seller AS s ON e.seller = s.id
GROUP BY e.time
ORDER BY e.time DESC
LIMIT 250";

// Prepare the statement
$stmt = $pdo->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="">
    <div class="flex">
        <h2 class="title">آخرین قیمت های گرفته شده از بازار</h2>
        <div class="px-24">
            <label for="search">جستجو</label>
            <input class="border py-2 px-3" type="text" name="search" id="search-bazar" onkeyup="searchBazar(this.value)">
        </div>
    </div>
    <div class="box-keeper">
        <?php
        $currentGroup = null;
        $bgColors = ['rgb(241 245 249)', 'rgb(165 243 252)']; // Array of background colors for date groups

        // Function to compare prices
        function comparePrices($a, $b)
        {
            // Extract the date and price from the input
            $dateA = substr($a['price'], 0, 10);
            $dateB = substr($b['price'], 0, 10);
            $priceA = substr($a['price'], 11);
            $priceB = substr($b['price'], 11);

            // Compare the dates
            $dateComparison = strcmp($dateA, $dateB);

            // If the dates are not the same, sort by date
            if ($dateComparison != 0) {
                return $dateComparison;
            }

            // If either price contains "/", move it to the end
            if (strpos($priceA, '/') !== false) {
                return 1; // Move $a to the end
            } elseif (strpos($priceB, '/') !== false) {
                return -1; // Move $b to the end
            }

            // If both prices don't contain "/", compare them numerically
            return strcmp($priceA, $priceB);
        }

        // Sort the result array based on prices
        usort($results, 'comparePrices');

        echo '<table class="min-w-full">';
        echo '<tr>';
        echo '<th>Part Number</th>';
        echo '<th>Seller Name</th>';
        echo '<th>Price</th>';
        echo '<th>User ID</th>';
        echo '</tr>';

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
                echo '<tr class="bg-red-400">';
                echo '<td colspan="5">' . $date . '</td>';
                echo '</tr>';
            }

            // Display the row for current entry with the same background color as the group
            echo '<tr style="background-color: ' . $bgColor . ';">';
            echo '<td>' . $partNumber . '</td>';
            echo '<td>' . $sellerName . '</td>';
            echo '<td>' . $price . '</td>';
            echo '<td>' . $userId . '</td>';
            echo '</tr>';
        }

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
