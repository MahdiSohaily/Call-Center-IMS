<div class="manual-add-customer">
    <a href="#">کارتابل</a>
    <div contenteditable="true"></div>

</div>

<?php
require_once './layout/heroHeader.php';
global  $repeatkeeper;
$statuskeeper = 0;
$n = 0;
$img = '';


$sql = "SELECT * FROM incoming ORDER BY  time DESC LIMIT 200";
$result = mysqli_query(dbconnect(), $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $n = $n + 1;
        $interval = nishatimedef($repeatkeeper, $row["time"]);
        $capsoltimesecond =   $interval->s;
        $capsoltimeminute =   $interval->i;

        if ($capsoltimesecond > 1 or $capsoltimeminute > 0) {
            $repeatkeeper =  $row["time"];

            if ($n == 1) {
                $phone = $row['phone'];
                $status = $row['status'];
                $statuskeeper = $statuskeeper . $status;
                $callid = $row["callid"];
                $internal = $row["user"];

                if ($status == 1) {
                    $answer = 'class="this-user-answer"';
                } else {
                    $answer = '';
                }

                $img = $img . '<img ' . $answer . ' src=".././userimg/' . getidbyinternal($internal) . '.jpg" />';
                $taglabel = '';
                $userlabel = '';
                $jalali_time = '';

                continue;
            }
?>
<div class="grid grid-cols-7">
<?php
            $sql2 = "SELECT * FROM customer WHERE phone LIKE '" . $phone . "%'";
            $result2 = mysqli_query($con, $sql2);
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $name = $row2['name'];
                    $family = $row2['family'];
                    $userlabel = $row2['user'];
                    $taglabel = $row2['label'];


?>

                    <a href="main.php?phone=<?php echo $phone ?>" class="call- <?php if ($statuskeeper == 0) {
                                                                                            echo 'this-capsol-answer';
                                                                                        } ?> <?php if ($internal > 150) {echo 'capsol-bazar';                                                                                                                                              } ?>">
                        <div class="call-capsol-phone"><?php echo $phone ?></div>
                        <div class="call-capsol-name"><?php echo $name ?> <?php echo $family ?></div>
                        <div class="call-capsol-extra-info"><?php mahakcontact($phone); ?></div>
                        <div class="call-capsol-extra-info"><?php googlecontact($phone); ?></div>
                        <div class="call-capsol-user-img"><?php echo $img ?></div>
                        <div class="call-capsol-taglabel"> <?php taglabelshow($taglabel)  ?></div>
                        <div class="call-capsol-userlabel"> <?php userlabelshow($userlabel)  ?></div>
                        <div class="call-capsol-if-reconnect"><?php ifreconnect($phone) ?></div>
                        <div class="call-capsol-time-info"><?php echo $jalali_time ?></div>
                        <div class="call-capsol-time-ago"><?php echo $jalali_time_ago ?></div>
                        <div class="capsol-behind min-w-full">آخرین استعلام نمایش داده می شود</div>
                    </a>


                <?php



                }
            } else {
                ?>


                <a href="main.php?phone=<?php echo $phone ?>" class="call- <?php if ($statuskeeper == 0) {
                                                                                        echo 'this-capsol-answer';
                                                                                    } ?>  <?php if ($internal > 150) {
                                                                                                                                                    echo 'capsol-bazar';
                                                                                                                                                } ?>">
                    <div class="call-capsol-phone"><?php echo $phone ?></div>
                    <div class="call-capsol-name no-save">این شماره ذخیره نشده است</div>
                    <div class="call-capsol-extra-info"><?php mahakcontact($phone); ?></div>
                    <div class="call-capsol-extra-info"><?php googlecontact($phone); ?></div>
                    <div class="call-capsol-user-img"><?php echo $img ?></div>
                    <div class="call-capsol-taglabel"> <?php taglabelshow($taglabel)  ?></div>
                    <div class="call-capsol-userlabel"> <?php userlabelshow($userlabel)  ?></div>
                    <div class="call-capsol-if-reconnect"><?php ifreconnect($phone) ?></div>
                    <div class="call-capsol-time-info"><?php echo $jalali_time ?></div>
                    <div class="call-capsol-time-ago"><?php echo $jalali_time_ago ?></div>
                    <div class="capsol-behind min-w-full">آخرین استعلام نمایش داده می شود</div>
                </a>



            <?php

            }
            ?>
            </div>
            <?php
            $img = '';
            $taglabel = '';
            $userlabel = '';
            $statuskeeper = '';


            // get value 

            $phone = $row['phone'];
            $status = $row['status'];
            $statuskeeper = $statuskeeper . $status;
            $callid = $row["callid"];
            $internal = $row["user"];
            $start = $row['starttime'];
            $end = $row['endtime'];
            $answertime = nishatimedef($start, $end);
            $answertime = '<div class="capsol-answer-time">' . format_calling_time($answertime) . '</div>';


            if ($status == 1) {
                $answer = 'class="this-user-answer"';
            } else {
                $answer = '';
                $answertime = '';
            }

            $img = $img . '<div><img ' . $answer . ' src=".././userimg/' . getidbyinternal($internal) . '.jpg" />' . $answertime . '</div>';

            $jalali_time = jalalitime($row["time"]);
            $jalali_time_ago =  format_interval(nishatimedef(date('Y/m/d H:i:s'), $row["time"]));
        } else {


            ?>




<?php

            // get value 

            $phone = $row['phone'];
            $status = $row['status'];
            $statuskeeper = $statuskeeper . $status;

            $callid = $row["callid"];
            $internal = $row["user"];

            $start = $row['starttime'];
            $end = $row['endtime'];
            $answertime = nishatimedef($start, $end);
            $answertime = '<div class="capsol-answer-time">' . format_calling_time($answertime) . '</div>';

            if ($status == 1) {
                $answer = 'class="this-user-answer"';
            } else {
                $answer = '';
                $answertime = '';
            }

            $img = $img . '<div><img ' . $answer . ' src=".././userimg/' . getidbyinternal($internal) . '.jpg" />' . $answertime . '</div>';

            $jalali_time = jalalitime($row["time"]);
            $jalali_time_ago =  format_interval(nishatimedef(date('Y/m/d H:i:s'), $row["time"]));
        }
    }
}
require_once './layout/heroFooter.php';
