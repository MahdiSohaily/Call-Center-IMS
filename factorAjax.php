<?php
require_once './config/database.php';


if (isset($_POST['getFactor'])) {
    $startDate = date_create($_POST['date']);
    $endDate = date_create($_POST['date']);

    $endDate = $endDate->setTime(23, 59, 59);
    $startDate = $startDate->setTime(1, 1, 0);

    $end = date_format($endDate, "Y-m-d H:i:s");
    $start = date_format($startDate, "Y-m-d H:i:s");

    $sql = "SELECT * FROM shomarefaktor WHERE time < '$end' AND time >= '$start' ORDER BY shomare DESC";

    $factor_result = mysqli_query($con, $sql);


?>
    <div class="today-faktor-statistics">
        <div class="">
            <?php
            if (mysqli_num_rows($factor_result) > 0) :
            ?>
                <div class="ranking mb-2">
                    <p class="text-white px-2">تعداد کل</p>
                    <span class="counter">
                        <?php
                        echo mysqli_num_rows($factor_result);
                        ?>
                    </span>
                </div>
            <?php
            endif;
            ?>
        </div>

        <div class="">
            <p class="today-faktor-plus">+</p>
            <?php
            $sql = "SELECT COUNT(shomare) as count_shomare,user FROM shomarefaktor WHERE time < '$end' AND time >= '$start' GROUP BY user ORDER BY count_shomare DESC ";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                $n = 1;
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="ranking mb-2">
                        <img src="../userimg/<?php echo $row['user']; ?>.jpg" />
                        <?php if ($n == 1) {
                            echo '<i class="fas ranking-icon fa-star golden"></i>';
                        }

                        if ($n == 2) {
                            echo '<i class="fas ranking-icon fa-star silver"></i>';
                        }

                        if ($n == 3) {
                            echo '<i class="fas ranking-icon fa-thumbs-up lucky"></i>';
                        }
                        $n = $n + 1; ?>
                        <span class="counter"><?php echo $row['count_shomare']; ?></span>
                    </div>

            <?php
                }
            }





            ?>
        </div>
    </div>
    <div>
        <table class="customer-list jadval-shomare">
            <tr class="table-heading">
                <th>شماره فاکتور</th>
                <th>خریدار</th>
                <th>کاربر</th>
            </tr>
            <tbody>
                <?php
                if (mysqli_num_rows($factor_result) > 0) {
                    while ($row = mysqli_fetch_assoc($factor_result)) {
                        $shomare = $row['shomare'];
                        $kharidar = $row['kharidar'];
                        $user = $row['user'];
                ?>
                        <tr>
                            <td>
                                <div class="jadval-shomare-blue"><?php echo $shomare ?></div>
                            </td>
                            <td>
                                <div class="jadval-shomare-kharidar"><?php echo $kharidar ?></div>
                            </td>
                            <td><img class="user-img" src="../userimg/<?php echo $user ?>.jpg" /></td>
                        </tr>
                <?php

                    }
                }

                ?>
            </tbody>
        </table>
    </div>
<?php
}
