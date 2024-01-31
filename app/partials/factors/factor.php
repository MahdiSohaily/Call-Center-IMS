<?php
session_name("MyAppSession");
session_start();
require_once '../../../config/db_connection.php';
require_once '../../partials/factors/helpers.php';

if (isset($_POST['getNewFactor'])) {
    $startDate = date_create($_POST['date']);
    $endDate = date_create($_POST['date']);

    $endDate = $endDate->setTime(23, 59, 59);
    $startDate = $startDate->setTime(1, 1, 0);

    $end = date_format($endDate, "Y-m-d H:i:s");
    $start = date_format($startDate, "Y-m-d H:i:s");

    $factors = getFactors($start, $end);
    $countFactorByUser = getCountFactorByUser($start, $end);


?>
    <div class="today-faktor-statistics">
        <div class="">
            <?php
            if (count($factors)) : ?>
                <div class="ranking mb-2">
                    <p class="text-white px-2">تعداد کل</p>
                    <span class="counter">
                        <?= count($factors) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="">
            <p class="today-faktor-plus">+</p>
            <?php
            if (count($countFactorByUser)) :
                $n = 1;
                foreach ($countFactorByUser as $row) :
                    $profile = '../userimg/default.png';
                    if (file_exists("../userimg/" . $row['user'] . ".jpg")) {
                        $profile = "../userimg/" . $row['user'] . ".jpg";
                    }
            ?>
                    <div class="ranking mb-2">
                        <img class="hover:cursor-pointer" data-id="<?= $row['user']; ?>" onclick="userReport(this)" src="<?= $profile ?>" />
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
                        <span class="counter"><?= $row['count_shomare']; ?></span>
                    </div>

            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div>
        <table class="customer-list jadval-shomare">
            <thead>
                <tr class="table-heading">
                    <th>شماره فاکتور</th>
                    <th></th>
                    <th>خریدار</th>
                    <th>کاربر</th>
                    <?php
                    $isAdmin = $_SESSION['username'] === 'niyayesh' || $_SESSION['username'] === 'babak' ? true : false;
                    if ($isAdmin) : ?>
                        <th class="edit">ویرایش</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php

                if (count($factors)) :
                    foreach ($factors as $factor) :
                        $profile = '../userimg/' . $factor['user'] . '.jpg';
                        if (!file_exists($profile)) :
                            $profile = '../userimg/default.jpg"';
                        endif;
                ?>
                        <tr>
                            <td>
                                <div title="کپی کردن شماره فاکتور" style="cursor: pointer;" data-billNumber="<?= $factor['shomare'] ?>" class="jadval-shomare-blue" onClick='copyBillNumberSingle(this)'>
                                    <i class="fas fa-paste"></i>
                                    <?= $factor['shomare'] ?>
                                </div>
                            </td>
                            <td class="flex justify-center items-center gap-2">
                                <?php if ($factor['exists_in_bill']) : ?>
                                    <img class="w-6 mt-5 cursor-pointer" title="مشاهده فاکتور" src="./public/img/bill.svg" onclick="displayBill('<?= $factor['bill_id'] ?>')" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="jadval-shomare-kharidar"><?= $factor['kharidar'] ?></div>
                            </td>
                            <td class="flex justify-center"><img onclick="userReport(this)" class="user-img hover:cursor-pointer" data-id="<?= $factor['user']; ?>" src="<?= $profile ?>" /></td>

                            <?php
                            if ($isAdmin) : ?>
                                <td class="edit"><a id="<?= $factor["id"] ?>" class="edit-shomare-faktor-btn">ویرایش<i class="fas fa-edit"></i></a></td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                endif;

                ?>
            </tbody>
        </table>
    </div>
<?php
}


if (isset($_POST['getFactor'])) :
    $startDate = date_create($_POST['date']);
    $endDate = date_create($_POST['date']);

    $endDate = $endDate->setTime(23, 59, 59);
    $startDate = $startDate->setTime(1, 1, 0);

    $end = date_format($endDate, "Y-m-d H:i:s");
    $start = date_format($startDate, "Y-m-d H:i:s");

    $factors = getFactors($start, $end);
    $countFactorByUser = getCountFactorByUser($start, $end);
?>
    <div class="today-faktor-statistics">
        <div class="">
            <?php
            if (count($factors)) : ?>
                <div class="ranking mb-2">
                    <p class="text-white px-2">تعداد کل</p>
                    <span class="counter">
                        <?= count($factors) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="">
            <p class="today-faktor-plus">+</p>
            <?php
            if (count($countFactorByUser)) :
                $n = 1;
                foreach ($countFactorByUser as $row) :
                    $profile = '../userimg/default.png';
                    if (file_exists("../../../../userimg/" . $row['user'] . ".jpg")) {
                        $profile = "../userimg/" . $row['user'] . ".jpg";
                    }
            ?>
                    <div class="ranking mb-2">
                        <img class="hover:cursor-pointer" data-id="<?= $row['user']; ?>" onclick="userReport(this)" src="<?= $profile ?>" />
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
                        <span class="counter"><?= $row['count_shomare']; ?></span>
                    </div>

            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div>
        <table class="customer-list jadval-shomare">
            <thead>
                <tr class="table-heading">
                    <th>شماره فاکتور</th>
                    <th></th>
                    <th>خریدار</th>
                    <th>کاربر</th>
                    <?php
                    $isAdmin = $_SESSION['username'] === 'niyayesh' || $_SESSION['username'] === 'babak' ? true : false;
                    if ($isAdmin) : ?>
                        <th class="edit">ویرایش</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php

                if (count($factors)) :
                    foreach ($factors as $factor) :
                        $profile = '../userimg/default.png';
                        if (file_exists("../../../../userimg/" . $factor['user'] . ".jpg")) {
                            $profile = "../userimg/" . $factor['user'] . ".jpg";
                        }
                ?>
                        <tr>
                            <td>
                                <div title="کپی کردن شماره فاکتور" style="cursor: pointer;" data-billNumber="<?= $factor['shomare'] ?>" class="jadval-shomare-blue" onClick='copyBillNumberSingle(this)'>
                                    <i class="fas fa-paste"></i>
                                    <?= $factor['shomare'] ?>
                                </div>
                            </td>
                            <td class="flex justify-center items-center gap-2">
                                <?php if ($factor['exists_in_bill']) : ?>
                                    <img class="w-6 mt-5 cursor-pointer" title="مشاهده فاکتور" src="./public/img/bill.svg" onclick="displayBill('<?= $factor['bill_id'] ?>')" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="jadval-shomare-kharidar"><?= $factor['kharidar'] ?></div>
                            </td>
                            <td class="flex justify-center"><img onclick="userReport(this)" class="user-img hover:cursor-pointer" data-id="<?= $factor['user']; ?>" src="<?= $profile ?>" /></td>

                            <?php
                            if ($isAdmin) : ?>
                                <td class="edit"><a id="<?= $factor["id"] ?>" class="edit-shomare-faktor-btn">ویرایش<i class="fas fa-edit"></i></a></td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                endif;

                ?>
            </tbody>
        </table>
    </div>
<?php
endif;

if (filter_has_var(INPUT_POST, 'getReport')) {

    $user = $_POST['user'];
    $startDate = date_create($_POST['date']);
    $endDate = date_create($_POST['date']);

    $endDate = $endDate->setTime(23, 59, 59);
    $startDate = $startDate->setTime(1, 1, 0);

    $end = date_format($endDate, "Y-m-d H:i:s");
    $start = date_format($startDate, "Y-m-d H:i:s");

    $factors = getFactors($start, $end, $user);
    $countFactorByUser = getCountFactorByUser($start, $end, $user);
?>
    <div class="today-faktor-statistics">
        <div class="">
            <?php
            if (count($factors)) : ?>
                <div class="ranking mb-2">
                    <p class="text-white px-2">تعداد کل</p>
                    <span class="counter">
                        <?= count($factors) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="">
            <p class="today-faktor-plus">+</p>
            <?php
            if (count($countFactorByUser)) :
                $n = 1;
                foreach ($countFactorByUser as $row) :
                    $profile = '../userimg/default.png';
                    if (file_exists("../../../../userimg/" . $row['user'] . ".jpg")) {
                        $profile = "../userimg/" . $row['user'] . ".jpg";
                    }
            ?>
                    <div class="ranking mb-2">
                        <img class="hover:cursor-pointer" data-id="<?= $row['user']; ?>" onclick="userReport(this)" src="<?= $profile ?>" />
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
                        <span class="counter"><?= $row['count_shomare']; ?></span>
                    </div>

            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div>
        <table class="customer-list jadval-shomare">
            <thead>
                <tr class="table-heading">
                    <th>شماره فاکتور</th>
                    <th></th>
                    <th>خریدار</th>
                    <th>کاربر</th>
                    <?php
                    $isAdmin = $_SESSION['username'] === 'niyayesh' || $_SESSION['username'] === 'babak' ? true : false;
                    if ($isAdmin) : ?>
                        <th class="edit">ویرایش</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php

                if (count($factors)) :
                    foreach ($factors as $factor) :
                        $profile = '../../../../userimg/' . $factor['user'] . '.jpg';
                        if (!file_exists($profile)) :
                            $profile = '../userimg/default.jpg"';
                        endif;
                ?>
                        <tr>
                            <td>
                                <div title="کپی کردن شماره فاکتور" style="cursor: pointer;" data-billNumber="<?= $factor['shomare'] ?>" class="jadval-shomare-blue" onClick='copyBillNumberSingle(this)'>
                                    <i class="fas fa-paste"></i>
                                    <?= $factor['shomare'] ?>
                                </div>
                            </td>
                            <td class="flex justify-center items-center gap-2">
                                <?php if ($factor['exists_in_bill']) : ?>
                                    <img class="w-6 mt-5 cursor-pointer" title="مشاهده فاکتور" src="./public/img/bill.svg" onclick="displayBill('<?= $factor['bill_id'] ?>')" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="jadval-shomare-kharidar"><?= $factor['kharidar'] ?></div>
                            </td>
                            <td class="flex justify-center"><img onclick="userReport(this)" class="user-img hover:cursor-pointer" data-id="<?= $factor['user']; ?>" src="<?= $profile ?>" /></td>

                            <?php
                            if ($isAdmin) : ?>
                                <td class="edit"><a id="<?= $factor["id"] ?>" class="edit-shomare-faktor-btn">ویرایش<i class="fas fa-edit"></i></a></td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                endif;

                ?>
            </tbody>
        </table>
    </div>
<?php
}
