<?php
require_once './layout/heroHeader.php';
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
        <table class="customer-list">
            <tr>
                <th>کد فنی</th>
                <th>فروشنده</th>

                <th>قیمت</th>
                <th>کاربر ثبت کننده</th>
                <th>زمان</th>
            </tr>
            <tbody id="results">
                <?php
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
                $currentGroup = null;
                $condition = true;
                if (count($results)) {
                    foreach ($results as $row) {
                        $time = $row['time'];
                        $price = $row['price'];

                        // Check if the group has changed
                        if ($time !== $currentGroup) {
                            // Display a row for the new group
                            echo '<div class="date-group">' . $time . '</div>';

                            // Update the current group
                            $currentGroup = $time;
                        }

                        // Check if the price section contains a slash
                        if (strpos($price, '/') !== false) {
                            // Display the row with slash at the end
                            echo '<div class="row">';
                            echo '<p>' . $row['user_id'] . ' ' . $row['user_i'] . ' - ' . $row['seller_name'] . '</p>';
                            echo '<p>' . $price . '</p>';
                            // Add more data if needed
                            echo '</div>';
                        } else {
                            // Display the row without slash at the beginning
                            echo '<div class="row">';
                            echo '<p>' . $price . '</p>';
                            echo '<p>' . $row['user_name'] . ' ' . $row['user_family'] . ' - ' . $row['seller_name'] . '</p>';
                            // Add more data if needed
                            echo '</div>';
                        }
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="5">مورد مشابهی در پایگاه داده پیدا نشد</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
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
