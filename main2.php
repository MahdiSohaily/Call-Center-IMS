 <?php
    require_once './layout/heroHeader.php';

    $phone = $_GET['phone'];

    require_once("php/db.php");
    $sql = "SELECT * FROM customer WHERE phone LIKE '" . $phone . "%'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $family = $row['family'];
            $phone = $row['phone'];
            $vin = $row['vin'];
            $des = $row['des'];
            $address = $row['address'];
            $car = $row['car'];
            $kind = $row['kind'];

            echo '<div class="phone-status">شماره <span>' . $phone . '</span> با نام <span>' . $name . '</span> <span>' . $family . '</span> در سیستم ثبت می باشد .</div>';
            $isold = 1;
        }
    } // end while

    else {
        echo '<div class="phone-status no-save">شماره <span>' . $phone . '</span> در سیستم ثبت نمی باشد .</div>';
        $isold = 0;
    }

    ?>








 <div class="box">
     <h2 class="title">مشخصات مشتری</h2>



     <form class="save-contact form" action="php/save.php" method="get" autocomplete="off">

         <div class="form-keeper">
             <div>
                 <p>

                     شماره تماس



                 </p>

                 <input name="phone" type="text" value="<?php echo $phone ?>" readonly>
             </div>


             <div>
                 <p>نام</p> <input name="name" type="text" value="<?php if (!empty($name)) {
                                                                        echo $name;
                                                                    } ?>">
             </div>

             <div>

                 <p>نام خانوادگی</p>
                 <input name="family" type="text" value="<?php if (!empty($family)) {
                                                                echo $family;
                                                            } ?>">
             </div>
             <div>
                 <p>شماره شاسی</p>


                 <input name="vin" type="text" value="<?php if (!empty($vin)) {
                                                            echo $vin;
                                                        } ?>">
             </div>

             <div>
                 <p>ماشین</p>


                 <input name="car" type="text" value="<?php if (!empty($car)) {
                                                            echo $car;
                                                        } ?>">
             </div>

             <div>
                 <p>نوع</p>


                 <input name="kind" type="text" value="<?php if (!empty($kind)) {
                                                            echo $kind;
                                                        } ?>">
             </div>
             <div>
                 <p>آدرس</p>


                 <textarea name="address"><?php if (!empty($address)) {
                                                echo $address;
                                            } ?></textarea>
             </div>

             <div>
                 <p>توضیحات مشتری</p>



                 <textarea name="des"><?php if (!empty($des)) {
                                            echo $des;
                                        } ?></textarea>
             </div>
             <input name="isold" id="isold" type="hidden" value="<?php echo ($isold) ?>">



             <div class="callinfobox">

                 <p> درج اطلاعات استعلام</p>
                 <textarea class="callinfo" name="callinfo"></textarea>

                 <div class="callinfobox-option">
                     <div>درخواست بارنامه</div>
                     <div>درخواست شماره کارت</div>
                     <div>پیگیری پیک</div>
                     <div>پیگیری روند فاکتور</div>
                     <div>درخواست ثبت فاکتور</div>
                     <div>ارجاع به واتساپ</div>
                     <div>درخواست شماره واتساپ</div>
                     <div>اطلاعات واریز وجه</div>

                 </div>
             </div>

         </div>





         <div class="bottom-bar">
             <input type="submit" value="ذخیره" id="sabt">
             <div class="error">
             </div>
         </div>










     </form>

 </div>
 <div class="box">
     <a class="click-to-call" href="#">تماس با مشتری</a>
     <a class="click-to-cancell" href="#">قطع تماس جاری</a>

 </div>



 <div class="box-right">
     <h2 class="title">استعلام های قبلی</h2>

     <div class="box-keeper">



         <table class="customer-list">
             <tr>
                 <th>اطلاعات</th>
                 <th>کاربر ثبت کننده</th>
                 <th>زمان</th>
                 <th>تاریخ</th>

             </tr>


             <?php

                $sql2 = "SELECT * FROM record WHERE phone LIKE '" . $phone . "%' ORDER BY  time DESC ";
                $result2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $time = $row2['time'];
                        $callinfo = $row2['callinfo'];
                        $user = $row2['user'];
                ?>
                     <tr>


                         <td class="record-info"><?php echo nl2br($callinfo) ?></td>
                         <td class="record-user"><?php





                                                    $con2 = mysqli_connect('localhost', 'root', '', 'yadakshop1401');

                                                    if (!$con2) {
                                                        die('Could not connect: ' . mysqli_error($con2));
                                                    }
                                                    mysqli_set_charset($con2, "utf8");
                                                    $sql3 = "SELECT * FROM users WHERE id=$user";
                                                    $result3 = mysqli_query($con2, $sql3);
                                                    if (mysqli_num_rows($result3) > 0) {
                                                        while ($row3 = mysqli_fetch_assoc($result3)) {
                                                            $name = $row3['name'];
                                                            $family = $row3['family'];
                                                            echo ($name . " " . $family);
                                                        }
                                                    }


                                                    date_default_timezone_set('Asia/Tehran');

                                                    $datetime1 = new DateTime();
                                                    $datetime2 = new DateTime($time);
                                                    $interval = $datetime1->diff($datetime2);





                                                    ?></td>

                         <td class="record-time"><?php echo format_interval($interval); ?></td>
                         <td class="record-date"><?php echo $time ?></td>


                     </tr>






             <?php

                    }
                } // end while


                else {
                    echo '<td colspan="4">هیچ اطلاعاتی موجود نیست</td>';
                }

                ?>



         </table>


     </div>
 </div>










 <div class="box-left">
     <h2 class="title">تماس های قبلی</h2>

     <div class="box-keeper">



         <table class="customer-list">
             <tr>

                 <th>پاسخ دهنده</th>
                 <th>زمان</th>
                 <th>تاریخ</th>
                 <th>ایدی</th>

             </tr>


             <?php


                $pretime = "";

                $sql30 = "

SELECT * FROM incoming WHERE phone LIKE '" . $phone . "%'
UNION ALL
SELECT * FROM outgoing WHERE phone LIKE '" . $phone . "%'
ORDER BY  time DESC ";
                $result30 = mysqli_query($con, $sql30);
                if (mysqli_num_rows($result30) > 0) {
                    while ($row30 = mysqli_fetch_assoc($result30)) {
                        $time = $row30['time'];
                        $user = $row30['user'];
                        $id = $row30['callid'];
                        $status = $row30['status'];
                        $start = $row30['starttime'];
                        $end = $row30['endtime'];




                        $interval1 =    nishatimedef(date('Y/m/d H:i:s'), $time);
                        $interval2 =    nishatimedef($start, $end);




                        if ($status == 0 and $pretime == $time) {
                            $pretime = $time;

                            continue;
                        }

                        $pretime = $time;


                ?>
                     <tr>


                         <td class="record-info"><?php

                                                    if ($status == 0) {
                                                        echo ("<div class='answer-x'>X</div>");
                                                    } else {
                                                        echo (getnamebyinternal($user) . "<div class='answer-tik'>&#10004;</div><div class='call-duration'>" . format_calling_time($interval2) . "</div>");
                                                    }


                                                    ?></td>


                         <td class="record-time"><?php echo format_interval($interval1); ?></td>
                         <td class="record-date"><?php echo $time ?></td>
                         <td class="record-date"><?php echo $id ?></td>


                     </tr>






             <?php

                    }
                } // end while


                else {
                    echo '<td colspan="4">هیچ اطلاعاتی موجود نیست</td>';
                }

                ?>



         </table>


     </div>
 </div>










 <div class="space"></div>

 <script>
     $(document).ready(function() {
         $(".click-to-call").click(function() {



             window.open('http://admin:admin@<?php echo getip($_SESSION["id"]) ?>/servlet?key=number=<?php echo $phone ?>&outgoing_uri=@192.168.9.10', 'برقراری تماس', 'width=400,height=400')


         });

         $(".click-to-cancell").click(function() {



             window.open('http://admin:admin@<?php echo getip($_SESSION["id"]) ?>/servlet?key=CALLEND', 'برقراری تماس', 'width=400,height=400')


         });
     });
 </script>
 <?php
    require_once './layout/heroFooter.php';
