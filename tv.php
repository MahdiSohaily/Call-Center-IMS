 <?php
    // require_once './header.php';
    require_once './php/function.php';
    require_once './php/jdf.php';
    require_once './config/database.php';
    require_once './utilities/helpers.php';
    if (isset($_GET['user'])) {
        $user = $_GET['user'];
    } else {
        $user  = getinternal($_SESSION["id"]);
    }
    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>TV</title>
     <link rel="icon" type="image/x-icon" href="img/favicon.ico">
     <link rel='stylesheet' href='./css/tv.css?v=$rand' type='text/css' media='all' />
 </head>

 <body>
     <div id="fullPage" data-user='<?php echo $user ?>'>
         <i onclick="openFullscreen()" class="large material-icons">aspect_ratio</i>
         <div class="d-grid">
             <div class="div1">
                 <h2>تماس های ورودی</h2>
                 <table>
                     <thead>
                         <tr>
                             <th class="bg-violet-800 text-white tiny-text px-2 py-2">مشخصات</th>
                             <th class="bg-violet-800 text-white tiny-text px-2 py-2">شماره تماس</th>
                             <th class="bg-violet-800 text-white tiny-text px-2 py-2">نیایش</th>
                             <th class="bg-violet-800 text-white tiny-text px-2 py-2">محک</th>
                             <th class="bg-violet-800 text-white tiny-text px-2 py-2">زمان</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
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

                                         <tr>
                                             <td><?php echo $name ?> <?php echo $family ?></td>
                                             <td><?php echo $name ?> <?= $phone ?></td>
                                             <td>
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
                                             <td>
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
                                             <td><?php echo $jalali_time ?></td>
                                         </tr>
                                     <?php

                                        }
                                    } else {
                                        ?>
                                     <tr>
                                         <td>
                                             <i style="color: red" class="material-icons">cancel</i>
                                         </td>
                                         <td><?= $phone ?></td>
                                         <td>
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
                                         <td>
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
                                         <td><?php echo $jalali_time ?></td>
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
                 <h2>آخرین قیمت های داده شده</h2>
                 <table>
                     <thead>
                         <tr>
                             <th>
                                 کد فنی
                             </th>
                             <th>
                                 قیمت
                             </th>

                             <th>
                                 مشتری
                             </th>
                             <th>
                                 کاربر
                             </th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $givenPrice = givenPrice($con);
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

                            if (count($givenPrice) > 0) {
                            ?>
                             <?php foreach ($givenPrice as $price) { ?>
                                 <?php if ($price['price'] !== null) {
                                    ?>
                                     <tr>
                                     <?php  } ?>
                                     <td>
                                         <p>
                                             <?php echo $price['partnumber']; ?>
                                         </p>
                                     </td>


                                     <td>
                                         <p>
                                             <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                         </p>
                                     </td>
                                     <td>
                                         <p>
                                             <?php echo $price['name'] . ' ' . $price['family'] ?>
                                         </p>
                                     </td>
                                     <td>
                                         <p>
                                             <img title="<?php echo $price['username'] ?>" class="user-img mx-auto" src="../userimg/<?php echo $price['userID'] ?>.jpg" alt="user-img">
                                         </p>
                                     </td>
                                     </tr>
                                 <?php
                                } ?>
                             <?php } else { ?>
                                 <tr>
                                     <td colspan="4" scope="col">
                                         موردی برای نمایش وجود ندارد !!
                                     </td>
                                 </tr>
                             <?php } ?>
                     </tbody>
                 </table>
             </div>
             <div class="div3">
                 <h2>آخرین استعلام ها</h2>
                 <div>

                     <table>
                         <thead>
                             <tr>
                                 <th>مشتری</th>
                                 <th>اطلاعات استعلام</th>
                                 <th>کاربر</th>
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

                                     <tr>
                                         <td><?php echo ($name . " " . $family) ?></td>
                                         <td><?php echo nl2br($callinfo) ?></td>
                                         <td><img class="user-img mx-auto" src="../userimg/<?php echo $user ?>.jpg" />
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
                                         <td><?php echo ($name . " " . $family) ?></td>
                                         <td><?php echo nl2br($callinfo) ?></td>
                                         <td><img class="user-img mx-auto" src="../userimg/<?php echo $user ?>.jpg" />
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
     <script src="./js/tv.js"></script>
 </body>

 </html>