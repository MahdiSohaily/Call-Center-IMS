<?php
require_once './layout/heroHeader.php';
?>

<div class="box">
    <div class="flex justify-">
        <h2 class="title">آخرین قیمت های گرفته شده از بازار</h2>
        <div class="px-24">
            <label for="search">جستجو</label>
            <input class="border" type="text" name="search" id="search-bazar" onkeyup="searchBazar(this.value)">
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
                $sql2 = "SELECT * FROM estelam ORDER BY  time DESC LIMIT 250  ";
                $result2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $code = $row2['codename'];
                        $seller = $row2['seller'];
                        $price = $row2['price'];
                        $user = $row2['user'];
                        $time = $row2['time'];

                        $sql = "SELECT * FROM users WHERE id=$user";
                        $result = mysqli_query(dbconnect2(), $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $name = $row['name'];
                                $family = $row['family'];



                                $sql3 = "SELECT * FROM seller WHERE id=$seller";
                                $result3 = mysqli_query(dbconnect2(), $sql3);
                                if (mysqli_num_rows($result3) > 0) {
                                    while ($row3 = mysqli_fetch_assoc($result3)) {
                                        $sellername = $row3['name'];
                ?>
                                        <tr>
                                            <td><?php echo $code ?></td>
                                            <td><?php echo $sellername ?></td>
                                            <td><?php echo $price ?></td>
                                            <td><?php echo $name ?> <?php echo $family ?></td>
                                            <td><?php echo $time ?></td>
                                        </tr>
                <?php
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function searchBazar(pattern) {
        alert(pattern);
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
