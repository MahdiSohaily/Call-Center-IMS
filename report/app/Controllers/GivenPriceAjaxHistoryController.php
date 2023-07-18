<?php
require_once('../../database/connect.php');

if (isset($_POST['historyAjax'])) {
    $givenPrice = givenPrice($conn);
            ?>
                <div class="rtl mb-5">
                    <h2 class="text-xl py-2">آخرین قیمت های داده شده</h2>
                    <table class="min-w-full text-left text-sm bg-white custom-table mb-2 p-3">
                        <thead class="font-medium bg-green-600">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-white text-right">
                                    مشتری
                                </th>
                                <th scope="col" class="px-3 py-2 text-white text-right">
                                    قیمت
                                </th>
                                <th scope="col" class="px-3 py-2 text-white text-right">
                                    کد فنی
                                </th>
                                <th scope="col" class="px-3 py-2 text-white text-center">
                                    قیمت دهنده
                                </th>
                                <th scope="col" class="px-3 py-2 text-white text-right">
                                    زمان
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($givenPrice) > 0) {
                            ?>
                                <?php foreach ($givenPrice as $price) { ?>
                                    <?php if ($price['price'] !== null) {
                                    ?>
                                        <tr class=" min-w-full mb-1 ?> odd:bg-gray-200">
                                        <?php  } ?>
                                        <td class=" px-1">
                                            <p class="text-right text-indigo-600 px-1 py-1">
                                                <a class="flex items-center" href="../main.php?phone=<?php echo $price['phone'] ?>">
                                                    <i class="small material-icons px-2">attachment</i>
                                                    <?php echo $price['name'] . ' ' . $price['family'] ?>
                                                </a>
                                            </p>
                                        </td>
                                        <td class=" px-1">
                                            <p class="text-right text-gray-700 px-2 py-1">
                                                <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                            </p>
                                        </td>
                                        <td class=" px-1">
                                            <p class="text-right text-gray-700 px-2 py-1">
                                                <?php echo $price['partnumber']; ?>
                                            </p>
                                        </td>
                                        <td class=" record-user">
                                            <img title="<?php echo $price['username'] ?>" class="userImage mx-auto mt-1" src="../../userimg/<?php echo $price['userID'] ?>.jpg" alt="userimage" />
                                        </td>
                                        <td class=" time">
                                            <p class="text-right text-gray-700 px-2 py-1">
                                                <?php
                                                date_default_timezone_set("Asia/Tehran");
                                                $create = date($price['created_at']);

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
                                            </p>
                                        </td>
                                        </tr>
                                    <?php
                                } ?>
                                <?php } else { ?>
                                    <tr class="">
                                        <td colspan="4" scope="col" class="not-exist">
                                            موردی برای نمایش وجود ندارد !!
                                        </td>
                                    </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
    <?php }
    ?>
    <div class="rtl mb-5">
        <h2 class="text-xl py-2">آخرین استعلام ها</h2>
        <table class="min-w-full text-sm bg-white custom-table mb-2 p-3">
            <thead class=" bg-green-600">
                <tr>
                    <th scope="col" class="px-3 py-2 text-white text-right">
                        مشتری
                    </th>
                    <th scope="col" class="px-3 py-2 text-white text-right">
                        تلفن
                    </th>
                    <th scope="col" class="px-3 py-2 text-white text-right">
                        اطلاعات استعلام
                    </th>
                    <th scope="col" class="px-3 py-2 text-white text-center">
                        کاربر
                    </th>
                    <th scope="col" class="px-3 py-2 text-white text-right">
                        زمان
                    </th>
                </tr>
            </thead>

            <?php

            $sql2 = "SELECT * FROM callcenter.record ORDER BY  time DESC LIMIT 350";
            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $time = $row2['time'];
                    $callinfo = $row2['callinfo'];
                    $user = $row2['user'];
                    $phone = $row2['phone'];

                    $sql = "SELECT * FROM callcenter.customer WHERE phone LIKE '" . $phone . "%'";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $name = $row['name'];
                            $family = $row['family']; ?>
                            <tr class=" min-w-full mb-1 ?> odd:bg-gray-200">
                                <td class="px-2 py-2"><a target="_blank" href="../main.php?phone=<?php echo $phone ?>"><?php echo ($name . " " . $family) ?></a></td>
                                <td><a target="_blank" href="../main.php?phone=<?php echo $phone ?>"><?php echo $phone ?></a></td>
                                <td class="px-2 py-2"><?php echo nl2br($callinfo) ?></td>
                                <td class="px-2 py-2"><img class="userImage mt-1" src="../../userimg/<?php echo $user ?>.jpg" />
                            <?php
                        }
                    }

                    date_default_timezone_set('Asia/Tehran');
                    $now = new DateTime(); // current date time
                    $date_time = new DateTime($time); // date time from string
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

                    $text = "$text قبل";

                            ?>
                                </td>

                                <td style=" width: 150px;" class="px-2 py-2"><?php echo $text; ?></td>
                            </tr>
                    <?php

                }
            } else {
                echo '<td colspan="4">هیچ اطلاعاتی موجود نیست</td>';
            }
                    ?>
        </table>
    </div>
<?php 
function givenPrice($conn)
{
    $sql = "SELECT 
    prices.price, prices.partnumber, users.username,customer.id AS customerID, users.id as userID, prices.created_at, customer.name, customer.family, customer.phone
    FROM ((shop.prices 
    INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
    INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
    ORDER BY prices.created_at DESC LIMIT 600";
    $result = mysqli_query($conn, $sql);


    $givenPrices = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($givenPrices, $item);
        }
    }
    return  $givenPrices;
}
