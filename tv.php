 <?php
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
     <link rel='stylesheet' href='./public/css/tv.css?v=<?= rand() ?>' type='text/css' media='all' />
     <script src="./public/js/axios.js"></script>
     <style>
         .circle-frame {
             display: flex;
             justify-content: center;
             align-items: center;
             border-radius: 50%;
             background-color: black;
             color: white;
             font-weight: bold;
             width: 30px;
             height: 30px;
             margin-inline: auto;
         }
     </style>
 </head>

 <body>
     <div id="fullPage" data-user='<?= $user ?>'>
         <table>
             <tr>
                 <td></td>
                 <?php
                    foreach ($datetimeData as $key => $value) :
                        $file = "../userimg/" . getidbyinternal($key) . ".jpg";
                        if (file_exists($file)) :
                    ?>

                         <td> <img class="user-imgs" src="../userimg/<?= getidbyinternal($key) ?>.jpg" /></td>
                     <?php else : ?>
                         <td>
                             <p class="circle-frame">
                                 <?= $key ?>
                             </p>
                         </td>
                 <?php
                        endif;
                    endforeach;
                    ?>
             </tr>
             <tr>
                 <td>
                     <b>
                         فعلا
                     </b>
                 </td>
                 <?php
                    foreach ($datetimeData as $key => $value) : ?>
                     <td style='text-align: center;'><?= format_calling_time_seconds($value['currentHour']) ?></td>
                 <?php
                    endforeach;
                    ?>
             </tr>
             <tr>
                 <td>
                     <b>
                         زمان کلی
                     </b>
                 </td>
                 <?php
                    foreach ($datetimeData as $key => $value) : ?>
                     <td style='text-align: center;'><?= format_calling_time_seconds($value['total']) ?></td>
                 <?php
                    endforeach;
                    ?>
             </tr>
             <tr>
                 <td>
                     <b>
                         <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                             <path d="M646.4 275.2L512 409.6l-177.066667-177.066667-59.733333 59.733334L512 529.066667l194.133333-194.133334z" fill="#F44336" />
                             <path d="M768 405.333333l-192-192h192z" fill="#F44336" />
                             <path d="M949.333333 657.066667l-51.2-51.2c-181.333333-177.066667-616.533333-151.466667-772.266666 0l-51.2 51.2c-14.933333 14.933333-14.933333 36.266667 0 51.2l102.4 100.266666c14.933333 14.933333 36.266667 14.933333 51.2 0l113.066666-108.8-8.533333-119.466666c36.266667-36.266667 322.133333-36.266667 358.4 0l-6.4 123.733333 108.8 104.533333c14.933333 14.933333 36.266667 14.933333 51.2 0l102.4-100.266666c17.066667-14.933333 17.066667-38.4 2.133333-51.2z" fill="#009688" />
                         </svg>
                     </b>
                 </td>
                 <?php
                    foreach ($datetimeData as $key => $value) : ?>
                     <td style='text-align: center;'><?= ($value['receivedCall']) ?></td>
                 <?php
                    endforeach;
                    ?>
             </tr>
             <tr>
                 <td>
                     <b>
                         <svg width="15px" height="15px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                             <g id="Home" />

                             <g id="Print" />

                             <g id="Mail" />

                             <g id="Camera" />

                             <g id="Video" />

                             <g id="Film" />

                             <g id="Message" />

                             <g id="Telephone">

                                 <path d="M30,22.3l-2.5-2.5c-1.3-1.3-3.5-1.3-4.8,0l-0.8,0.8c-0.5,0.5-1.4,0.5-2,0l-4.2-4.2l-4.2-4.2   c-0.3-0.3-0.4-0.6-0.4-1s0.1-0.7,0.4-1l0.9-0.8c1.3-1.3,1.3-3.5,0-4.8L9.7,2C8.5,0.7,6.2,0.7,4.9,2L4.1,2.8c-2,2-3.1,4.6-3.1,7.5   s1.1,5.5,3.1,7.5l5.1,5.1l5.1,5.1c2,2,4.7,3.1,7.5,3.1s5.5-1.1,7.5-3.1l0.9-0.8c0.6-0.6,1-1.5,1-2.4C31,23.8,30.7,22.9,30,22.3z" fill="#4DAF50" />

                                 <g>

                                     <path d="M16,5c-0.5,0-1,0.5-1,1s0.5,1,1,1c2.5,0,4.7,1,6.4,2.6C24,11.3,25,13.5,25,16c0,0.5,0.5,1,1,1s1-0.5,1-1    c0-3-1.2-5.8-3.2-7.8C21.8,6.2,19,5,16,5z" fill="#FE9803" />

                                     <path d="M27.8,4.2C25.8,2.2,23,1,20,1c-0.5,0-1,0.5-1,1s0.5,1,1,1c2.5,0,4.7,1,6.4,2.6C28,7.3,29,9.5,29,12    c0,0.5,0.5,1,1,1s1-0.5,1-1C31,9,29.8,6.2,27.8,4.2z" fill="#FE9803" />

                                 </g>

                             </g>

                             <g id="User" />

                             <g id="File" />

                             <g id="Folder" />

                             <g id="Map" />

                             <g id="Download" />

                             <g id="Upload" />

                             <g id="Video_Recorder" />

                             <g id="Schedule" />

                             <g id="Cart" />

                             <g id="Setting" />

                             <g id="Search" />

                             <g id="Pencils" />

                             <g id="Group" />

                             <g id="Record" />

                             <g id="Headphone" />

                             <g id="Music_Player" />

                             <g id="Sound_On" />

                             <g id="Sound_Off" />

                             <g id="Lock" />

                             <g id="Lock_open" />

                             <g id="Love" />

                             <g id="Favorite" />

                             <g id="Film_1_" />

                             <g id="Music" />

                             <g id="Puzzle" />

                             <g id="Turn_Off" />

                             <g id="Book" />

                             <g id="Save" />

                             <g id="Reload" />

                             <g id="Trash" />

                             <g id="Tag" />

                             <g id="Link" />

                             <g id="Like" />

                             <g id="Bad" />

                             <g id="Gallery" />

                             <g id="Add" />

                             <g id="Close" />

                             <g id="Forward" />

                             <g id="Back" />

                             <g id="Buy" />

                             <g id="Mac" />

                             <g id="Laptop" />

                         </svg>
                     </b>
                 </td>
                 <?php
                    foreach ($datetimeData as $key => $value) : ?>
                     <td style='text-align: center;'>
                         <?= ($value['answeredCall'] ) ?>
                     </td>
                 <?php
                    endforeach;
                    ?>
             </tr>
             <tr>
                 <td>
                     <b>

                         <svg height="15px" width="15px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 508.068 508.068" xml:space="preserve">
                             <path style="fill:#FFC52F;" d="M111.713,322.434c0.4,0.4,0.8,1.2,0.4,1.6l-20.4,120.8c-2.8,16.4-0.4,30,6.4,38.4
                                    c4.8,5.6,11.6,8.4,20,8.4c8,0,17.6-2.8,27.6-8l108.4-57.2c0.4-0.4,1.2-0.4,2,0l108.4,56.8c10,5.2,19.2,8,27.2,8l0,0
                                    c8.4,0,15.2-2.8,20-8.4c6.8-8,9.2-21.6,6-38.4l-21.2-120.8c0-0.8,0-1.2,0.4-1.6l87.6-85.6c14.4-14,20.4-28.4,16.4-40
                                    c-4-12-17.2-20-36.8-22.8l-121.2-17.2c-0.8,0-1.2-0.4-1.6-1.2l-54.4-109.6c-8.8-18-20.8-28-33.2-28s-24,10-33.2,28l-54,110
                                    c-0.4,0.4-0.8,0.8-1.6,1.2l-121.2,18c-20,2.8-32.8,11.2-36.8,22.8c-4,11.6,2,25.6,16,39.6h0.4c0.4,0,0.4,0.4,0.8,0.8
                                    L111.713,322.434z" />
                             <path d="M118.113,497.634c-7.2,0-14-2-19.2-6c-12-8.8-16.4-25.6-12.8-48l20-118.8l-86.4-83.6l0,0l0,0l-2.8-2.8
                                    c-0.4-0.4-0.4-0.4-0.8-0.8c-13.6-14.8-18.8-29.6-14.8-42.8c4.4-14,19.2-23.6,41.6-27.2l119.2-17.6l52.8-108
                                    c10-20.4,23.6-31.6,38.4-31.6l0,0c14.8,0,28.4,11.2,38.4,31.2l53.6,107.6l119.2,16.8c22.4,3.2,37.2,12.8,42,26.8s-1.6,30.4-18,46.4
                                    l-86,84.4l20.8,118.4c4,22.4-0.4,39.2-12.4,48s-29.6,8-49.6-2.8l-106.8-55.6l-106.4,56.4
                                    C137.313,494.834,127.313,497.634,118.113,497.634z M24.913,235.634l88,85.2c0.8,0.8,1.2,2.4,1.2,3.6l-20.4,120.8
                                    c-3.2,19.2,0,33.2,9.6,40s24,5.6,41.2-3.6l108.4-57.2c1.2-0.8,2.4-0.8,3.6,0l108.4,56.8c17.2,8.8,31.6,10,41.2,3.2
                                    c9.2-6.8,12.8-21.2,9.2-40.4l-21.2-120.8c-0.4-1.2,0-2.8,1.2-3.6l87.6-85.6c14-13.6,19.6-27.2,16-38c-3.6-11.2-16-18.8-35.2-21.2
                                    l-121.2-17.2c-1.2,0-2.4-1.2-3.2-2l-54.4-109.6c-8.8-17.2-19.6-26.8-31.2-26.8l0,0c-11.6,0-22.8,9.6-31.2,27.2l-54,110
                                    c-0.4,1.2-1.6,2-3.2,2l-30,4.4l0,0l-90.8,13.6c-19.2,2.8-31.6,10.4-35.2,21.6C5.713,208.834,11.313,222.434,24.913,235.634z" />
                             <path d="M22.513,242.834c-0.8,0-2-0.4-2.8-1.2l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l0,0l-2.8-2.8
                                        c-1.6-1.6-1.6-4,0-5.6s4-1.6,5.6,0l2.4,2.4l0,0l0.4,0.4l0,0l0,0l0,0l0,0l0,0l0,0l0,0c1.6,1.6,1.6,4,0,5.6
                                        C24.513,242.434,23.313,242.834,22.513,242.834z" />
                         </svg>
                     </b>
                 </td>
                 <?php
                    foreach ($datetimeData as $key => $value) : ?>
                     <td style='text-align: center;'>
                         <?= $value['successRate'] . "%" ?>
                     </td>
                 <?php
                    endforeach;
                    ?>
             </tr>
         </table>
         <i onclick="openFullscreen()" class="material-icons handler">aspect_ratio</i>
         <div class="d-grid">
             <div class="div1">
                 <h2 class="section_heading">تماس های ورودی</h2>
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
                                             <td><?= $name . " " . $family ?></td>
                                             <td> <?= $phone ?></td>
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
                                             <td><?= $jalali_time ?></td>
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
                                         <td><?= $jalali_time ?></td>
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
                 <h2 class="section_heading">آخرین قیمت های داده شده</h2>
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

                            if (count($givenPrice) > 0) {
                            ?>
                             <?php foreach ($givenPrice as $price) { ?>
                                 <?php if ($price['price'] !== null) {
                                    ?>
                                     <tr>
                                     <?php  } ?>
                                     <td>
                                         <p class="strong_content">
                                             <?= $price['partnumber']; ?>
                                         </p>
                                     </td>


                                     <td>
                                         <p style="direction: ltr;">
                                             <?= $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                         </p>
                                     </td>
                                     <td>
                                         <p>
                                             <?= $price['name'] . ' ' . $price['family'] ?>
                                         </p>
                                     </td>
                                     <td class="pic">
                                         <img title="<?= $price['username'] ?>" class="user-img" src="../userimg/<?= $price['userID'] ?>.jpg" alt="user-img">
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
                 <h2 class="section_heading">آخرین استعلام ها</h2>
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
                                     <tr class="pin">
                                         <td><?= ($name . " " . $family) ?></td>
                                         <td><?= nl2br($callinfo) ?></td>
                                         <td class="pic"><img class="user-img" src="../userimg/<?= $user ?>.jpg" /></td>
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
                                         <td><?= ($name . " " . $family) ?></td>
                                         <td><?= nl2br($callinfo) ?></td>
                                         <td class="pic">
                                             <img class="user-img" src="../userimg/<?= $user ?>.jpg" />
                                         </td>
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