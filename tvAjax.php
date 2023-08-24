<?php
require_once './php/function.php';
require_once './php/jdf.php';
require_once './config/database.php';
if (isset($_POST['user'])) {
    $user = $_POST['user'];
} else {
    $user  = getinternal($_SESSION["id"]);
}

function givenPrice($con)
{
    $sql = "SELECT 
 prices.price, prices.partnumber, users.username,customer.id AS customerID, users.id as userID, prices.created_at, customer.name, customer.family
 FROM ((shop.prices 
 INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
 INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
 ORDER BY prices.created_at DESC LIMIT 40";
    $result = mysqli_query($con, $sql);


    $givenPrices = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($givenPrices, $item);
        }
    }
    return  $givenPrices;
}

$sql = "SELECT * FROM shop.tv WHERE id='1'";
$factor_result = mysqli_query($con, $sql);
$tv = mysqli_fetch_assoc($factor_result);
$status = $tv['status'];
if ($status == 'on') :
    function getFirstLetters($string)
    {
        // Trim the string and remove special characters
        $string = trim(preg_replace('/[^a-zA-Z0-9\sآ-ی]/u', '', $string));

        $words = preg_split('/\s+/u', $string);
        $firstLetters = '';

        if (count($words) === 1) {
            $firstLetters = mb_substr($words[0], 0, 2);
        } else {
            foreach ($words as $word) {
                $firstLetters .= mb_substr($word, 0, 1) . ' ';
            }
        }

        return trim($firstLetters);
    }
?>
    <script>
        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                /* IE11 */
                document.msExitFullscreen();
            }
        }
    </script>
    <div class="bg-white">
        <i style="cursor: pointer;" onclick="openFullscreen()" class="material-icons">aspect_ratio</i>
        <i style="cursor: pointer;" onclick="closeFullscreen()" class="material-icons">border_clear</i>
        <div class="d-grid">
            <div class="div1">
                <h2 class="title">تماس های ورودی</h2>
                <table class="border text-sm bg-white custom-table mb-2 p-3">
                    <thead>
                        <tr>
                            <th class="bg-violet-800 text-white tiny-text px-2 py-2">مشخصات</th>
                            <th class="bg-violet-800 text-white tiny-text px-2 py-2">نیایش</th>
                            <th class="bg-violet-800 text-white tiny-text px-2 py-2">محک</th>
                            <th class="bg-violet-800 text-white tiny-text px-2 py-2">زمان</th>
                            <!-- <th>تاریخ</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global  $repeat;
                        $sql = "SELECT * FROM incoming WHERE user = $user ORDER BY  time DESC LIMIT 40";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $phone = $row['phone'];
                                $user = $row['user'];
                                $status = $row['status'];
                                $date = $row["time"];
                                $array = explode(' ', $date);
                                list($year, $month, $day) = explode('-', $array[0]);
                                list($hour, $minute, $second) = explode(':', $array[1]);
                                $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
                                $jalali_time = jdate("H:i", $timestamp, "", "Asia/Tehran", "en");
                                $jalali_date = jdate("Y/m/d", $timestamp, "", "Asia/Tehran", "en");

                                $sql2 = "SELECT * FROM customer WHERE phone LIKE '" . $phone . "%'";
                                $result2 = mysqli_query($con, $sql2);
                                if (mysqli_num_rows($result2) > 0) {
                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                        $name = $row2['name'];
                                        $family = $row2['family'];
                        ?>

                                        <tr class="">
                                            <td class=" tiny-text p-2"><?php echo $name ?> <?php echo $family ?></td>
                                            <td class=" tiny-text p-2">
                                                <?php
                                                $gphone = substr($phone, 1);
                                                $sql3 = "SELECT * FROM google WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%' OR mob3 LIKE '%" . $gphone . "%'  ";
                                                $result3 = mysqli_query($con, $sql3);
                                                if (mysqli_num_rows($result3) > 0) {
                                                    $n = 1;
                                                    while ($row3 = mysqli_fetch_assoc($result3)) {
                                                        $gname1 = $row3['name1'];
                                                        $gname2 = $row3['name2'];
                                                        $gname3 = $row3['name3'];
                                                        if (strlen($phone) < 5) {
                                                            break;
                                                        }
                                                        if ($n > 1) {
                                                            echo ("<br>");
                                                        }
                                                        echo $gname1 . " " . $gname2 . " " . $gname3;
                                                        $n++;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td class=" tiny-text p-2">
                                                <?php
                                                $gphone = substr($phone, 1);
                                                $sql4 = "SELECT * FROM mahak WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%'   ";
                                                $result4 = mysqli_query($con, $sql4);
                                                if (mysqli_num_rows($result4) > 0) {
                                                    $n = 1;
                                                    while ($row4 = mysqli_fetch_assoc($result4)) {
                                                        $mname1 = $row4['name1'];
                                                        $mname2 = $row4['name2'];

                                                        if (strlen($phone) < 5) {
                                                            break;
                                                        }

                                                        if ($n > 1) {
                                                            echo ("<br>");
                                                        }
                                                        echo $mname1 . " " . $mname2;
                                                        $n++;
                                                    }
                                                }
                                                ?></td>
                                            <td class=" tiny-text p-2"><?php echo $jalali_time ?></td>
                                            <!-- <td class="date-info"><?php echo $jalali_date ?></td> -->
                                        </tr>
                                    <?php

                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td>
                                            <i style="color: red" class="material-icons">cancel</i>
                                        </td>
                                        <td class="p-2">
                                            <?php
                                            $gphone = substr($phone, 1);
                                            $sql3 = "SELECT * FROM google WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%' OR mob3 LIKE '%" . $gphone . "%'  ";
                                            $result3 = mysqli_query($con, $sql3);
                                            if (mysqli_num_rows($result3) > 0) {
                                                $n = 1;
                                                while ($row3 = mysqli_fetch_assoc($result3)) {
                                                    $gname1 = $row3['name1'];
                                                    $gname2 = $row3['name2'];
                                                    $gname3 = $row3['name3'];
                                                    if (strlen($phone) < 5) {
                                                        break;
                                                    }
                                                    if ($n > 1) {
                                                        echo ("<br>");
                                                    }
                                                    echo $gname1 . " " . $gname2 . " " . $gname3;
                                                    $n++;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="p-2">
                                            <?php
                                            $gphone = substr($phone, 1);
                                            $sql4 = "SELECT * FROM mahak WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%'   ";
                                            $result4 = mysqli_query($con, $sql4);
                                            if (mysqli_num_rows($result4) > 0) {
                                                $n = 1;
                                                while ($row4 = mysqli_fetch_assoc($result4)) {
                                                    $mname1 = $row4['name1'];
                                                    $mname2 = $row4['name2'];

                                                    if (strlen($phone) < 5) {
                                                        break;
                                                    }

                                                    if ($n > 1) {
                                                        echo ("<br>");
                                                    }
                                                    echo $mname1 . " " . $mname2;
                                                    $n++;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="p-2"><?php echo $jalali_time ?></td>
                                        <!-- <td class="date-info"><?php echo $jalali_date ?></td> -->
                                    </tr>
                        <?php
                                }
                            }
                        } // end while
                        else {
                            echo 'هیچ اطلاعاتی موجود نیست';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="div2">
                <h2 class="title">آخرین قیمت های داده شده</h2>
                <table class="border text-sm bg-white custom-table mb-2 p-3">
                    <thead class="font-medium bg-green-600">
                        <tr>
                            <th scope="col" class=" py-2 tiny-text text-white text-right">
                                کد فنی
                            </th>
                            <th scope="col" class=" py-2 tiny-text text-white text-right">
                                قیمت
                            </th>

                            <th scope="col" class=" py-2 tiny-text text-white text-right">
                                مشتری
                            </th>
                            <th scope="col" class=" py-2 tiny-text text-white text-center">
                                کاربر
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $givenPrice = givenPrice($con);


                        if (count($givenPrice) > 0) {
                        ?>
                            <?php foreach ($givenPrice as $price) { ?>
                                <?php if ($price['price'] !== null) {
                                ?>
                                    <tr class="mb-1 ?> odd:bg-gray-200">
                                    <?php  } ?>
                                    <td class="tiny-text bold px-1">
                                        <p class="text-right bold text-gray-700 px-2 py-1">
                                            <?php echo $price['partnumber']; ?>
                                        </p>
                                    </td>


                                    <td class="tiny-text bold px-1">
                                        <p class="text-right bold text-gray-700 px-2 py-1">
                                            <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                        </p>
                                    </td>
                                    <td class="tiny-text bold px-1">
                                        <p class="text-right bold text-gray-700 px-2 py-1">
                                            <?php echo $price['name'] . ' ' . $price['family'] ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-center bold text-gray-700 px-2 py-1">
                                            <img title="<?php echo $price['username'] ?>" class="user-img mx-auto" src="../userimg/<?php echo $price['userID'] ?>.jpg" alt="user-img">
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
            <div class="div3">
                <h2 class="title">آخرین استعلام ها</h2>
                <div class="">

                    <table class="border text-sm bg-white custom-table mb-2 p-3 ">
                        <thead>
                            <tr class="tiny-text bg-violet-800 text-white">
                                <th class="p-2">مشتری</th>
                                <th class="p-2">اطلاعات استعلام</th>
                                <th class="p-2">کاربر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT  customer.name, customer.family, customer.phone, record.id as recordID, record.time, record.callinfo, record.pin, users.id AS userID
                            FROM ((callcenter.record
                            INNER JOIN callcenter.customer ON record.phone = customer.phone)
                            INNER JOIN yadakshop1402.users ON record.user = users.id)
                            WHERE record.pin = 'pin'
                            ORDER BY record.time DESC
                            LIMIT 40";
                            $result2 = mysqli_query($con, $sql2);
                            if (mysqli_num_rows($result2) > 0) {
                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    $recordID = $row2['recordID'];
                                    $time = $row2['time'];
                                    $callinfo = $row2['callinfo'];
                                    $user = $row2['userID'];
                                    $phone = $row2['phone'];
                                    $name = $row2['name'];
                                    $family = $row2['family'];
                            ?>

                                    <tr class="pin">
                                        <td class="tiny-text p-2"><?php echo ($name . " " . $family) ?></td>
                                        <td class="p-2"><?php echo nl2br($callinfo) ?></td>
                                        <td class="tiny-text p-2"><img class="user-img mx-auto" src="../userimg/<?php echo $user ?>.jpg" />
                                            <?php

                                            date_default_timezone_set('Asia/Tehran');

                                            $datetime1 = new DateTime();
                                            $datetime2 = new DateTime($time);
                                            $interval = $datetime1->diff($datetime2);
                                            ?>
                                        </td>
                                        <!-- <td class="record-date"><?php echo $time ?></td> -->
                                    </tr>

                            <?php
                                }
                            }
                            ?>
                            <?php
                            $sql2 = "SELECT  customer.name, customer.family, customer.phone, record.id as recordID, record.time, record.callinfo, record.pin, users.id AS userID
                                        FROM ((callcenter.record
                                        INNER JOIN callcenter.customer ON record.phone = customer.phone)
                                        INNER JOIN yadakshop1402.users ON record.user = users.id)
                                        WHERE record.pin = 'unpin'
                                        ORDER BY record.time DESC
                                        LIMIT 40";
                            $result2 = mysqli_query($con, $sql2);
                            if (mysqli_num_rows($result2) > 0) {
                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    $recordID = $row2['recordID'];
                                    $time = $row2['time'];
                                    $callinfo = $row2['callinfo'];
                                    $user = $row2['userID'];
                                    $phone = $row2['phone'];
                                    $name = $row2['name'];
                                    $family = $row2['family'];
                            ?>

                                    <tr>
                                        <td class="tiny-text p-2"><?php echo ($name . " " . $family) ?></td>
                                        <td class="p-2"><?php echo nl2br($callinfo) ?></td>
                                        <td class="tiny-text p-2"><img class="user-img mx-auto" src="../userimg/<?php echo $user ?>.jpg" />
                                            <?php

                                            date_default_timezone_set('Asia/Tehran');

                                            $datetime1 = new DateTime();
                                            $datetime2 = new DateTime($time);
                                            $interval = $datetime1->diff($datetime2);
                                            ?>
                                        </td>
                                    </tr>

                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        /* Get the element you want displayed in fullscreen mode (a video in this example): */
        var elem = document.getElementById("fullpage");

        /* When the openFullscreen() function is executed, open the video in fullscreen.
        Note that we must include prefixes for different browsers, as they don't support the requestFullscreen method yet */
        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }
        }
    </script>
<?php
else :
?>
    <style>
        body,
        html {
            height: 100vh !important;
            padding: 0 !important;
            margin: 0 !important;
            background: url(https://www.topgear.com/sites/default/files/cars-car/carousel/2021/06/1_cupra_formentor.jpg?w=892&h=502)no-repeat center;
            background-size: cover !important;
        }
    </style>
<?php
endif;
