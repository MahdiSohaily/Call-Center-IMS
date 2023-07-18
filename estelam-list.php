<?php
require_once './layout/heroHeader.php';
?>

<div class="box">
    <div class="flex justify-">
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
                $sql = "SELECT e.*, u.name AS user_name, u.family AS user_family, s.name AS seller_name
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
                if (count($results)) {
                    foreach ($results as $row2) {

                        $code = $row2['codename'];
                        $seller = $row2['seller_name'];
                        $price = $row2['price'];
                        $name = $row2['user_name'];
                        $family = $row2['user_family'];
                        $time = $row2['time'];
                ?>
                        <tr>
                            <td><?php echo $code ?></td>
                            <td><?php echo $seller ?></td>
                            <td><?php echo $price ?></td>
                            <td><?php echo $name ?> <?php echo $family ?></td>
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
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
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
