<?php
require_once './layout/heroHeader.php';
global  $repeatkeeper;
$statuskeeper = 0;
$n = 0;
$img = '';


$sql = "SELECT * FROM incoming ORDER BY  time DESC LIMIT 200";
$result = mysqli_query(dbconnect(), $sql);

?>
<div style="z-index: 100;" class="manual-add-customer">
    <a href="#">کارتابل</a>
    <div contenteditable="true"></div>

</div>

<div class="grid lg:grid-cols-5 md:grid-cols-3  gap-6 px-4">
    <style>
        .overlay {
            position: absolute;
            background-color: rgba(85, 85, 85, 0.826);
            color: white;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 100%;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
        }

        .parent:hover .overlay {
            top: 0;
        }
    </style>
    <?php

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

                    $file = ".././userimg/" . getidbyinternal($internal) . ".jpg";

                    if (!file_exists($file)) {
                        $file = ".././userimg/31.jpg";
                    }

                    $img = $img . "<img $answer   src='$file' />";
                    $taglabel = '';
                    $userlabel = '';
                    $jalali_time = '';

                    continue;
                }

                $sql2 = "SELECT * FROM customer WHERE phone LIKE '" . $phone . "%'";
                $result2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $name = $row2['name'];
                        $family = $row2['family'];
                        $userlabel = $row2['user'];
                        $taglabel = $row2['label'];


    ?>

                        <a href="main.php?phone=<?= $phone ?>" class="parent bg-gray-200 p-2 rounded-lg relative <?php if ($statuskeeper == 0) {
                                                                                                                        echo 'this-capsol-answer';
                                                                                                                    } ?> <?php if ($internal > 150) {
                                                                                                                                echo 'capsol-bazar';
                                                                                                                            } ?>">
                            <div class="call-capsol-phone"><?= $phone ?></div>
                            <div class="call-capsol-name"><?= $name ?> <?= $family ?></div>
                            <div class="call-capsol-extra-info"><?php mahakcontact($phone); ?></div>
                            <div class="call-capsol-extra-info"><?php googlecontact($phone); ?></div>
                            <div class="call-capsol-user-img"><?= $img ?></div>
                            <div class="call-capsol-taglabel"> <?php taglabelshow($taglabel)  ?></div>
                            <div class="call-capsol-userlabel"> <?php userlabelshow($userlabel)  ?></div>
                            <div class="call-capsol-if-reconnect"><?php ifreconnect($phone) ?></div>
                            <div class="call-capsol-time-info"><?= $jalali_time ?></div>
                            <div class="call-capsol-time-ago"><?= $jalali_time_ago ?></div>
                            <div class="overlay rounded-lg text-sm">آخرین استعلام نمایش داده می شود</div>
                        </a>


                    <?php



                    }
                } else {
                    ?>


                    <a href="main.php?phone=<?= $phone ?>" class="parent bg-gray-200 p-2 rounded-lg relative <?php if ($statuskeeper == 0) {
                                                                                                                    echo 'this-capsol-answer';
                                                                                                                } ?>  <?php if ($internal > 150) {
                                                                                                                            echo 'capsol-bazar';
                                                                                                                        } ?>">
                        <div class="call-capsol-phone"><?= $phone ?></div>
                        <div class="call-capsol-name no-save">این شماره ذخیره نشده است</div>
                        <div class="call-capsol-extra-info"><?php mahakcontact($phone); ?></div>
                        <div class="call-capsol-extra-info"><?php googlecontact($phone); ?></div>
                        <div class="call-capsol-user-img"><?= $img ?></div>
                        <div class="call-capsol-taglabel"> <?php taglabelshow($taglabel)  ?></div>
                        <div class="call-capsol-userlabel"> <?php userlabelshow($userlabel)  ?></div>
                        <div class="call-capsol-if-reconnect"><?php ifreconnect($phone) ?></div>
                        <div class="call-capsol-time-info"><?= $jalali_time ?></div>
                        <div class="call-capsol-time-ago"><?= $jalali_time_ago ?></div>
                        <div class="overlay rounded-lg text-sm">آخرین استعلام نمایش داده می شود</div>
                    </a>



                <?php

                }
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

                $file = ".././userimg/" . getidbyinternal($internal) . ".jpg";

                if (!file_exists($file)) {
                    $file = ".././userimg/31.jpg";
                }

                $img = $img . "<div><img $answer   src='$file' /> $answertime </div>";

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

                $file = ".././userimg/" . getidbyinternal($internal) . ".jpg";

                if (!file_exists($file)) {
                    $file = ".././userimg/31.jpg";
                }

                $img = $img . "<div><img $answer   src='$file' /> $answertime </div>";

                $jalali_time = jalalitime($row["time"]);
                $jalali_time_ago =  format_interval(nishatimedef(date('Y/m/d H:i:s'), $row["time"]));
            }
        }
    }
    ?>
</div>
<?php
require_once './layout/heroFooter.php';
