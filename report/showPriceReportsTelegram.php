<?php
require_once '../../telegram/index.php';
require_once './database/connect.php';
require_once('./app/Controllers/GivenPriceControllerTelegram.php');
require_once('./views/Layouts/header.php');

function displayTimePassed($datetimeString)
{
    if ($datetimeString) {
        $date_parts = explode('/', $datetimeString);
        $datetimeString = jalali_to_gregorian(abs($date_parts[0]), abs($date_parts[1]), abs($date_parts[2]));
        $month_days_num = [30, 29, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30];
        date_default_timezone_set('Asia/Tehran');
        $datetime = new DateTime(join('-', $datetimeString));
        $month = $datetime->format("m");
        $now = new DateTime();

        $interval = $now->diff($datetime);

        $totalDays = $interval->days;

        $passedYears = floor($totalDays / 365);
        $remainingDays = $totalDays % 365;

        $passedMonths = floor($remainingDays / $month_days_num[$month - 1]);
        $passedDays = $remainingDays % $month_days_num[$month - 1];

        $persianYears = convertToPersian($passedYears);
        $persianMonths = convertToPersian($passedMonths);
        $persianDays = convertToPersian($passedDays);

        $result = "";

        if ($passedYears > 0) {
            $result .= "$persianYears سال";
        }

        if ($passedMonths > 0) {
            if ($passedYears > 0) {
                $result .= " و ";
            }
            $result .= "$persianMonths ماه";
        }

        if ($passedDays > 0) {
            if ($passedYears > 0 || $passedMonths > 0) {
                $result .= " و ";
            }
            $result .= "$persianDays روز";
        }
        return $result;
    }

    return 'تاریخ ورود موجود نیست';
}


function convertToPersian($number)
{
    $persianDigits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $persianNumber = '';

    while ($number > 0) {
        $digit = $number % 10;
        $persianNumber = $persianDigits[$digit] . $persianNumber;
        $number = (int)($number / 10);
    }

    return $persianNumber;
}

?>
<style>
    #deleteGivenPrice {
        font-size: 14px;
        font-weight: bold;
    }

    #deleteGivenPrice:hover {
        color: black;
    }

    .toTop {
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        transition: all 0.2s ease-in-out;
    }

    .toTop i {
        font-size: 28px;
        font-weight: bold;
    }

    .toTop:hover {
        bottom: 15px;
    }

    .account_info {
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-items: start;
        gap: 5px;
    }

    .socialMedia {
        font-size: 12px;
        color: lightgray;
    }
</style>
<?php

if ($isValidCustomer) {
    foreach ($finalResult as $reportResult) :
        if ($reportResult) {
            $explodedCodes = $reportResult['explodedCodes'];
            $not_exist = $reportResult['not_exist'];
            $existing = $reportResult['existing'];
            $customer = $reportResult['customer'];
            $completeCode = $reportResult['completeCode'];
            $notification = $reportResult['notification'];
            $rates = $reportResult['rates'];
            $messages = $reportResult['messages'];
            $fullName = $reportResult['fullName'];
            $profile = $reportResult['profile'];
            $username = $reportResult['username'];
?>
            <div class="accordion mb-10">
                <?php
                foreach ($explodedCodes as $code_index => $code) {
                ?>
                    <input type="checkbox" checked="true" name="panel" id="<?= $code ?>" class="hidden">
                    <label for="<?= $code ?>" class="relative justify-between flex items-center bg-gray-700 text-white p-4 shadow border-b border-grey hover:cursor-pointer">
                        <span> <?= $code ?></span>
                        <div class="account_info">
                            <img class="userImage" src="./img/telegram/<?= $profile ?>" alt="user Profile">
                            <?= $fullName ?>
                            <a class="socialMedia" href="https://t.me/<?= $username ?>"> (<?= $username ?>)</a>
                        </div>
                    </label>
                    <div class="accordion__content overflow-hidden bg-grey-lighter">
                        <?php
                        if (array_key_exists($code, $existing)) {
                            foreach ($existing[$code] as $index => $item) {
                                $partNumber = $index;
                                $information = $item['information'];
                                $relation = $item['relation'];
                                $goods =  $relation['goods'];
                                $exist =  $relation['existing'];
                                $sorted =  $relation['sorted'];
                                $stockInfo =  $relation['stockInfo'];
                                $givenPrice =  $item['givenPrice'];
                                $estelam = $item['estelam'];
                                $customer = $customer;
                                $completeCode = $completeCode;
                        ?>
                                <div class="grid grid-cols-1 grid-cols-1 lg:grid-cols-10 gap-6 lg:gap-2 lg:p-2 overflow-auto">

                                    <!-- Start the code info section -->
                                    <div class="min-w-full bg-white rounded-lg overflow-auto shadow-md mt-2">
                                        <div class="rtl p-3">
                                            <p style="font-size: 0.8rem;" class="text-left bg-gray-600 text-white p-2 my-3 rounded-md">
                                                <?= $index; ?>
                                            </p>
                                            <?php if ($information) { ?>
                                                <div>
                                                    <p class="my-2">قطعه: <?= $information['relationInfo']['name'] ?></p>
                                                    <?php if (array_key_exists("status_name", $information['relationInfo'])) { ?>
                                                        <p class="my-2">وضعیت: <?= $information['relationInfo']['status_name'] ?></p>
                                                    <?php } ?>
                                                    <ul>
                                                        <?php foreach ($information['cars'] as $item) {
                                                        ?>
                                                            <li class="" v-for="elem in relationCars">
                                                                <?= $item ?>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                    <?php if ($information['relationInfo']['description'] !== '' && $information['relationInfo']['description'] !== null) { ?>
                                                        <p>توضیحات:</p>
                                                        <p class="bg-red-500 text-white rounded-md p-2 shake">
                                                            <?= $information['relationInfo']['description'] ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                            <?php } else {
                                            ?>
                                                <p v-else>
                                                    رابطه ای پیدا نشد
                                                </p>
                                            <?php } ?>

                                        </div>
                                    </div>

                                    <!-- ENd the code info section -->
                                    <div class="min-w-full bg-white rounded-lg col-span-5 overflow-auto shadow-md">
                                        <div class="p-3">
                                            <table class="min-w-full text-left text-sm font-light custom-table">
                                                <thead class="font-medium bg-green-600">
                                                    <tr>
                                                        <th scope="col" class="px-3 py-3 text-white text-center">
                                                            شماره فنی
                                                        </th>
                                                        <th scope="col" class="px-3 py-3 text-white text-center">
                                                            موجودی
                                                        </th>
                                                        <th scope="col" class="px-3 py-3 text-white text-center">
                                                            قیمت به اساس نرخ ارز
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($sorted as $index => $element) {
                                                    ?>
                                                        <tr>
                                                            <td class="relative px-1 hover:cursor-pointer" data-part="<?= $goods[$index]['partnumber'] ?>" onmouseleave="hideToolTip(this)" onmouseover="showToolTip(this)">
                                                                <p class="text-center bold bg-gray-600 text-white px-2 py-3">
                                                                    <?= $goods[$index]['partnumber'] ?>
                                                                </p>
                                                                <div class="custome-tooltip-2" id="<?= $goods[$index]['partnumber'] . '-google' ?>">
                                                                    <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?= $goods[$index]['partnumber'] ?>'>
                                                                        <img class="w-5 h-auto" src="./public/img/google.png" alt="google">
                                                                    </a>
                                                                    <a target='_blank' href='https://partsouq.com/en/search/all?q=<?= $goods[$index]['partnumber'] ?>'>
                                                                        <img class="w-5 h-auto" src="./public/img/part.png" alt="part">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td class="px-1 pt-2">
                                                                <table class="min-w-full text-sm font-light p-2">
                                                                    <thead class="font-medium">
                                                                        <tr>
                                                                            <?php
                                                                            if (array_sum($exist[$index]) > 0) {
                                                                                foreach ($exist[$index] as $brand => $amount) {
                                                                                    if ($amount > 0) { ?>
                                                                                        <th scope="col" class="<?= $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> text-white text-center py-2 relative hover:cursor-pointer" data-key="<?= $index ?>" data-brand="<?= $brand ?>" onmouseover="seekExist(this)" onmouseleave="closeSeekExist(this)">
                                                                                            <?= $brand ?>
                                                                                            <div class="custome-tooltip" id="<?= $index . '-' . $brand ?>">
                                                                                                <table class="rtl min-w-full text-sm font-light p-2">
                                                                                                    <thead class="font-medium bg-violet-800">
                                                                                                        <tr>
                                                                                                            <th class="text-right px-3 py-2 tiny-text">فروشنده</th>
                                                                                                            <th class="text-right px-3 py-2 tiny-text"> موجودی</th>
                                                                                                            <th class="text-right px-3 py-2 tiny-text">تاریخ</th>
                                                                                                            <th class="text-right px-3 py-2 tiny-text">زمان سپری شده</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php
                                                                                                        foreach ($stockInfo[$index] as $item) {
                                                                                                        ?>
                                                                                                            <?php if ($item !== 0 && $item['name'] === $brand) { ?>
                                                                                                                <tr class="odd:bg-gray-500 bg-gray-600">
                                                                                                                    <td class="px-3 py-2 tiny-text text-right"><?= $item['seller_name'] ?></td>
                                                                                                                    <td class="px-3 py-2 tiny-text text-right"><?= $item['qty'] ?></td>
                                                                                                                    <td class="px-3 py-2 tiny-text text-right"><?= $item['invoice_date'] ?></td>
                                                                                                                    <td class="px-3 py-2 tiny-text text-right"><?= displayTimePassed($item['invoice_date']) ?></td>
                                                                                                                </tr>
                                                                                                            <?php } ?>
                                                                                                        <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </th>
                                                                            <?php }
                                                                                }
                                                                            } else {
                                                                                echo '<p class="text-red-400 text-center bold"> در حال حاضر موجود نیست </p>';
                                                                            } ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="py-3">
                                                                            <?php foreach ($exist[$index] as $brand => $amount) {
                                                                                if ($amount > 0) { ?>
                                                                                    <td class="<?= $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> whitespace-nowrap text-white px-3 py-2 text-center">
                                                                                        <?= $amount;
                                                                                        ?>
                                                                                    </td>
                                                                            <?php }
                                                                            } ?>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td class="px-1 pt-2">
                                                                <table class="min-w-full text-left text-sm font-light">
                                                                    <thead class="font-medium">
                                                                        <tr>
                                                                            <?php
                                                                            foreach ($rates as $rate) {
                                                                            ?>
                                                                                <th v-for="rate in rates" scope="col" class="text-gray-800 text-center py-2 <?= $rate['status'] !== 'N' ? $rate['status'] : 'bg-green-700' ?>">
                                                                                    <?= $rate['amount'] ?>
                                                                                </th>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="py-3">
                                                                            <?php
                                                                            foreach ($rates as $rate) {
                                                                                $price = doubleval($goods[$index]['price']);
                                                                                $price = str_replace(",", "", $price);
                                                                                $avgPrice = round(($price * 110) / 243.5);
                                                                                $finalPrice = round($avgPrice * $rate['amount'] * 1.2 * 1.2 * 1.3);
                                                                            ?>
                                                                                <td class="text-bold whitespace-nowrap px-3 py-2 text-center hover:cursor-pointer <?= $rate['status'] !== 'N' ? $rate['status'] : 'bg-gray-100' ?>" onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">
                                                                                    <?= $finalPrice ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                        <?php if ($goods[$index]['mobis'] > 0 && $goods[$index]['mobis'] !== '-') { ?>
                                                                            <tr class="bg-neutral-400">
                                                                                <?php
                                                                                foreach ($rates as $rate) {
                                                                                    $price = doubleval($goods[$index]['mobis']);
                                                                                    $price = str_replace(",", "", $price);
                                                                                    $avgPrice = round(($price * 110) / 243.5);
                                                                                    $finalPrice = round($avgPrice * $rate['amount'] * 1.25 * 1.3)

                                                                                ?>
                                                                                    <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">

                                                                                        <?= $finalPrice ?>
                                                                                    </td>
                                                                                <?php } ?>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($goods[$index]['korea'] > 0 && $goods[$index]['mobis'] !== '-') { ?>
                                                                            <tr class="bg-amber-600" v-if="props.relation.goods[key].korea > 0">
                                                                                <?php
                                                                                foreach ($rates as $rate) {
                                                                                    $price = doubleval($goods[$index]['korea']);
                                                                                    $price = str_replace(",", "", $price);
                                                                                    $avgPrice = round(($price * 110) / 243.5);
                                                                                    $finalPrice = round($avgPrice * $rate['amount'] * 1.25 * 1.3)

                                                                                ?>
                                                                                    <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">

                                                                                        <?= $finalPrice ?>
                                                                                    </td>
                                                                                <?php } ?>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Given Price section -->
                                    <div class="min-w-full bg-white rounded-lg col-span-2 overflow-auto shadow-md">
                                        <div class="p-3">
                                            <table class=" min-w-full text-sm font-light">
                                                <thead>
                                                    <tr class="min-w-full bg-green-600">
                                                        <td class="text-white bold text-center py-2 px-2 "></td>
                                                        <td class="text-white bold text-center py-2 px-2 w-28">قیمت</td>
                                                        <td class="text-white bold text-center py-2 px-2 rtl">مشتری</td>
                                                        <td class="text-white bold text-center py-2 px-2 rtl">کد فنی</td>
                                                        <td class="text-white bold text-center py-2 px-2 rtl">کاربر</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="price-<?= $partNumber ?>">
                                                    <?php if ($givenPrice !== null) {
                                                        foreach ($givenPrice as $price) {
                                                            if ($price['price'] !== null && $price['price'] !== '') {
                                                                if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                                                                    <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer">
                                                                    <?php } elseif (array_key_exists("ordered", $price) || $price['customerID'] == 2) { ?>
                                                                    <tr class="min-w-full mb-1 bg-slate-300 hover:cursor-pointer">
                                                                    <?php  } else {
                                                                    ?>
                                                                    <tr class="min-w-full mb-1  bg-indigo-200 hover:cursor-pointer">
                                                                    <?php
                                                                }
                                                                if (array_key_exists("id", $price)) : ?>
                                                                        <td onclick="deleteGivenPrice(this)" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" data-del='<?= $price['id'] ?>' scope="col" class="text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                            <i id="deleteGivenPrice" class="material-icons" title="حذف قیمت">close</i>
                                                                        </td>
                                                                    <?php else : ?>
                                                                        <td></td>
                                                                    <?php endif; ?>
                                                                    <td onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                        <?= $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                                                    </td>
                                                                    <td onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                        <?php if (array_key_exists("ordered", $price)) {
                                                                            echo 'قیمت دستوری';
                                                                        } else {
                                                                            echo $price['name'] . ' ' . $price['family'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" class="bold <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?> ">
                                                                        <?= array_key_exists("partnumber", $price) ? $price['partnumber'] : '' ?>
                                                                    </td>
                                                                    <td onclick="setPrice(this)" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                        <?php if (!array_key_exists("ordered", $price)) {
                                                                        ?>
                                                                            <img class="userImage" src="../../userimg/<?= $price['userID'] ?>.jpg" alt="userimage">
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    </tr>
                                                                    <?php if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                                                                        <tr class="min-w-full mb-1 border-b-2 bg-red-500">
                                                                            <td></td>
                                                                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                                                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                                                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
                                                                                    <?php
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
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } elseif (array_key_exists("ordered", $price) || $price['customerID'] == 2) { ?>
                                                                        <tr class="min-w-full mb-1 bg-slate-400 hover:cursor-pointer">
                                                                            <td></td>
                                                                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                                                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                                                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
                                                                                    <?php
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
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php  } else {
                                                                    ?>
                                                                        <tr class="min-w-full mb-1  bg-indigo-300 hover:cursor-pointer">
                                                                            <td></td>
                                                                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                                                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                                                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
                                                                                    <?php
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
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            } ?>
                                                        <?php } else { ?>
                                                            <tr class="min-w-full mb-4 border-b-2 border-white">
                                                                <td colspan="3" scope="col" class="text-gray-800 py-2 text-center bg-indigo-300">
                                                                    !! موردی برای نمایش وجود ندارد
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                </tbody>
                                            </table>
                                            <br>
                                            <form action="" method="post" onsubmit="event.preventDefault()">
                                                <?php date_default_timezone_set("Asia/Tehran"); ?>
                                                <input type="text" hidden name="store_price" value="store_price">
                                                <input type="text" hidden name="partNumber" value="<?= $partNumber ?>">
                                                <input type="text" hidden id="customer_id" name="customer_id" value="<?= $customer ?>">
                                                <input type="text" hidden id="notification_id" name="notification_id" value="<?= $notification_id ?>">
                                                <div class="rtl col-span-6 sm:col-span-4">
                                                    <label class="block font-medium text-sm text-gray-700">
                                                        قیمت
                                                    </label>
                                                    <input onkeyup="update_price(this)" name="price" class="ltr price-input-custome mt-1 block w-full border-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="<?= $partNumber ?>-price" data-code="<?= $code ?>" type="text" />
                                                    <p class="mt-2"></p>
                                                </div>


                                                <div class="rtl">
                                                    <button onclick="registerPrice(this)" data-customer="<?= $customer ?>" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                        ثبت قیمت
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- END GIVEN PRICE SECTION -->
                                    <div class="min-w-full bg-white rounded-lg col-span-2 overflow-auto shadow-md">

                                        <div class="p-3">
                                            <table class=" min-w-full text-sm font-light">
                                                <thead>
                                                    <tr class="min-w-full bg-green-600">
                                                        <td class="text-white bold text-center py-2 px-2 ">پیام دریافتی</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($messages as $message) :
                                                        $pattern = $partNumber;
                                                        if (preg_match("/$pattern/i", $message)) { ?>
                                                            <tr class="min-w-full mb-4 border-b-2 border-white">
                                                                <td class="text-gray-800 py-2 text-center bg-indigo-300">
                                                                    <?= nl2br($message); ?>
                                                                </td>
                                                            </tr>
                                                    <?php  }
                                                    endforeach; ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="bg-white rounded-lg overflow-auto mb-3 py-4">
                                <p class="text-center">کد مد نظر در سیستم موجود نیست</p>
                            </div>
                        <?php } ?>
                    </div>
                <?php
                }
                ?>
                <p id="form_success" class="custome-alert success px-3 tiny-text">
                    ! موفقانه در پایگاه داده ثبت شد
                </p>
                <p id="form_error" class=" custome-alert error px-3 tiny-text">
                    ! ذخیره سازی اطلاعات ناموفق بود
                </p>
            </div>
            <a class="toTop" href="#">
                <i class="material-icons">arrow_drop_up</i>
            </a>

<?php
        }
    endforeach;
} else {
    echo "<p class='rtl col-6 mx-auto flex items-center justify-center pt-10'>کد جدیدی در گروه جهت گزارش ارائه نگردیده است</p>";
}

?>
<script>
    // Global controllers for operations messages
    const form_success = document.getElementById('form_success');
    const form_error = document.getElementById('form_error');
    // Global price variable
    let price = null;

    // A function to update the global price while typing in the input feild
    function update_price(element) {
        price = element.value;
        const partNumber = element.getAttribute('data-code').split('-')[0];
        const target = document.getElementById(partNumber + '-append');

        target.innerHTML = price;
    }

    // A function to create the relationship
    function registerPrice(e) {
        e.disabled = true;

        setTimeout(() => {
            e.disabled = false;
            e.innerHTML = `
                        ثبت قیمت
                        <i class="material-icons text-green-500 font-sm px-1">check_circle</i>`
        }, 5000);

        // Accessing the form fields to get thier value for an ajax store operation
        const partNumber = e.getAttribute('data-part');
        const customer_id = e.getAttribute('data-customer');
        const notification_id = document.getElementById('notification_id').value;
        const code = e.getAttribute('data-code');

        const goodPrice = document.getElementById(partNumber + '-price').value;
        const resultBox = document.getElementById('price-' + partNumber);

        // Defining a params instance to be attached to the axios request
        const params = new URLSearchParams();
        params.append('store_price', 'store_price');
        params.append('partNumber', partNumber);
        params.append('customer_id', 2);
        params.append('notification_id', notification_id);
        params.append('price', goodPrice);
        params.append('code', code);

        sendMessage(customer_id, code, goodPrice);

        axios.post("./app/Controllers/GivenPriceAjax.php", params)
            .then(function(response) {
                if (response.data) {
                    form_success.style.bottom = '10px';
                    goodPrice.value = null;
                    setTimeout(() => {
                        form_success.style.bottom = '-300px';
                        resultBox.innerHTML = (response.data);
                    }, 2000)
                } else {
                    form_error.style.bottom = '10px';
                    setTimeout(() => {
                        form_error.style.bottom = '-300px';
                        location.reload();
                    }, 2000)
                }
            })
            .catch(function(error) {

            });
    }

    function sendMessage(receiver, code, price) {
        // Defining a params instance to be attached to the axios request
        const params = new URLSearchParams();
        params.append('sendMessage', 'sendMessage');
        params.append('receiver', receiver);
        params.append('price', price);
        params.append('code', code);
        axios.post("../../telegram/index.php", params)
            .then(function(response) {
                console.log(response.data);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    // A function to set the price while clicking on the prices table
    function setPrice(element) {
        newPrice = element.getAttribute('data-price');
        part = element.getAttribute('data-part');
        const input = document.getElementById(part + '-price');
        input.value = newPrice;
        price = newPrice;


        const partNumber = element.getAttribute('data-code').split('-')[0];

        const target = document.getElementById(partNumber + '-append');
        target.innerHTML = price;
    }

    // A function to copy content to cliboard
    function copyPrice(elem) {
        // Get the text field
        let parentElement = document.getElementById("priceReport");

        let tdElements = parentElement.getElementsByTagName('td');
        let tdTextContent = [];

        const elementLenght = tdElements.length;

        for (let i = 0; i < elementLenght; i++) {
            if (tdElements[i].textContent.trim() !== 'content_copy') {
                let text = tdElements[i].textContent === 'موجود نیست' ? '-' : tdElements[i].textContent;
                tdTextContent.push(text);
            }
        }

        const chunkSize = 2;

        let finalResult = []
        const size = tdTextContent.length;
        for (let i = 0; i < size; i += chunkSize) {
            finalResult.push(tdTextContent.slice(i, i + chunkSize));
        }

        // Copy the text inside the text field

        let text = '';
        for (let item of finalResult) {
            text += item.join(' : ');
            text += '\n';
        }
        copyToClipboard(text);

        // Alert the copied text
        elem.innerHTML = `done`;
        setTimeout(() => {
            elem.innerHTML = `content_copy`;
        }, 1500);

    }

    function copyItemPrice(elem) {
        // Get the parent <td> element
        var parentTd = elem.parentNode;

        // Get the siblings <td> elements
        var sibling1 = parentTd.previousElementSibling;
        var sibling2 = sibling1.previousElementSibling;

        // Retrieve the innerHTML of the sibling <td> elements
        var sibling1HTML = sibling1.innerHTML;
        var sibling2HTML = sibling2.innerHTML;

        let text = sibling2HTML + ' : ' + (sibling1HTML === 'موجود نیست' ? '-' : sibling1HTML);

        copyToClipboard(text);

        // Alert the copied text
        elem.innerHTML = `done`;
        setTimeout(() => {
            elem.innerHTML = `content_copy`;
        }, 1500);
    }

    function deleteGivenPrice(element) {
        const partNumber = element.getAttribute('data-part');
        const id = element.getAttribute('data-del');

        // Accessing the form fields to get thier value for an ajax store operation
        const customer_id = document.getElementById('customer_id').value;
        const notification_id = document.getElementById('notification_id').value;
        const code = element.getAttribute('data-code');
        const resultBox = document.getElementById('price-' + partNumber);
        // Defining a params instance to be attached to the axios request
        const params = new URLSearchParams();
        params.append('delete_price', 'delete_price');
        params.append('partNumber', partNumber);
        params.append('customer_id', customer_id);
        params.append('notification_id', notification_id);
        params.append('code', code);
        params.append('id', id);

        axios.post("./app/Controllers/deleteGivenPrice.php", params)
            .then(function(response) {
                if (response.data) {
                    console.log(response.data);
                    resultBox.innerHTML = (response.data);
                } else {
                    console.log(response.data);
                }
            })
            .catch(function(error) {

            });
    }

    function closeTab() {
        // Set up a timeout to close the tab after 2 minutes (120,000 milliseconds)
        setTimeout(function() {
            // Try to close the window (tab)
            // This may not work if the window was not opened by a script or if the browser blocks the action.
            window.close();
        }, 60000);
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
