<?php
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GivenPriceControllerTelegram.php');
require_once './utilities/helper.php';

if ($isValidCustomer) :
?>
    <div class="py-10">
        <div class="accordion">
            <?php
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
                    $message_date = $reportResult['message_date'];
                    $fullName = $reportResult['fullName'];
                    $profile = $reportResult['profile'];
                    $relation_ids = $reportResult['relation_id'];

                    foreach ($explodedCodes as $code_index => $code) {
                        $relation_id =  array_key_exists($code, $relation_ids) ? $relation_ids[$code] : 'xxx';
                        $max = 0;
                        if (array_key_exists($code, $existing)) {
                            foreach ($existing[$code] as $item) {
                                $max  += max($item['relation']['sorted']);
                            }
                        }
                        if ($max > 0) : ?>
                            <div class="accordion-header bg-slate-500">
                                <p class="flex items-center gap-2 w-36">
                                    <?php
                                    if ($max > 0) {
                                        echo '<i class="material-icons text-green-500 rounded-circle">check_circle</i>';
                                    } else {
                                        echo '<i class="material-icons text-red-600 rounded-circle">do_not_disturb_on</i>';
                                    }
                                    echo "<span class='text-white font-bold'>" . strtoupper($code) . "</span>"
                                    ?>

                                </p>
                                <div class="px-7">
                                    <img class='userImage inline' src="http://telegram.om-dienstleistungen.de/img/telegram/<?= $profile ?>" alt="User profile" srcset="">
                                    <span class="text-white text-sm"><?= $fullName ?></span>
                                </div>
                            </div>
                            <div class="accordion-content overflow-hidden bg-grey-lighter" style="<?= $max > 0 ? 'max-height: 1000vh;' : 'max-height: 0vh;' ?>">
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
                                                    <p class="text-center text-sm bg-gray-600 text-white p-2 my-3 rounded-md font-bold">
                                                        <?= strtoupper($index); ?>
                                                    </p>
                                                    <?php if ($information) { ?>
                                                        <div class="p-2 bg-blue-400 rounded-md text-white text-sm font-bold">
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
                                                        <p class="text-sm font-bold">
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
                                                                        <div class="relative">
                                                                            <p class="text-center bold bg-gray-600 text-white px-2 py-3">
                                                                                <?= strtoupper($goods[$index]['partnumber']) ?>
                                                                            </p>
                                                                            <div class="custom-tooltip" id="<?= $goods[$index]['partnumber'] . '-google' ?>">
                                                                                <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?= $goods[$index]['partnumber'] ?>'>
                                                                                    <img class="w-5 h-auto" src="./public/img/google.png" alt="google">
                                                                                </a>
                                                                                <a target='_blank' href='https://partsouq.com/en/search/all?q=<?= $goods[$index]['partnumber'] ?>'>
                                                                                    <img class="w-5 h-auto" src="./public/img/part.png" alt="part">
                                                                                </a>
                                                                                <a title="بررسی تک آیتم" target='_blank' href='../../1402/singleItemReport.php?code=<?= $goods[$index]['partnumber'] ?>'>
                                                                                    <img class="w-5 h-auto" src="./public/img/singleItem.svg" />
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-1 pt-2">
                                                                        <table class="min-w-full text-sm font-light p-2">
                                                                            <thead class="font-medium">
                                                                                <tr>
                                                                                    <?php
                                                                                    if (abs(array_sum($exist[$index])) > 0) {
                                                                                        foreach ($exist[$index] as $brand => $amount) {
                                                                                            if ($amount > 0) { ?>
                                                                                                <th onclick="appendBrand(this)" scope="col" class="<?php echo $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> text-white text-center py-2 relative hover:cursor-pointer" data-key="<?php echo $index ?>" data-part="<?= $partNumber ?>" data-brand="<?php echo $brand ?>" onmouseover="seekExist(this)" onmouseleave="closeSeekExist(this)">
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
                                                                                        echo '<p class="text-rose-500 text-center font-bold"> در حال حاضر موجود نیست </p>';
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
                                                            <?php $finalPriceForm = null;
                                                            if ($givenPrice !== null && count($givenPrice) > 0) {
                                                                $target = current($givenPrice);
                                                                $priceDate = $target['created_at'];
                                                                if (checkDateIfOkay($applyDate, $priceDate) && $target['price'] !== 'موجود نیست') :
                                                                    $rawGivenPrice = $target['price'];

                                                                    $finalPriceForm = applyDollarRate($rawGivenPrice, $priceDate);
                                                            ?>
                                                                    <tr class="min-w-full mb-1  bg-cyan-400 hover:cursor-pointer">
                                                                        <td>
                                                                        </td>
                                                                        <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?php echo $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                            <?php echo $target['price'] === null ? 'ندارد' :  $finalPriceForm ?>
                                                                        </td>
                                                                        <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                            افزایش قیمت <?= $appliedRate ?> در صد
                                                                        </td>
                                                                        <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?php echo $partNumber ?>" class="bold <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?> ">
                                                                            <?php echo array_key_exists("partnumber", $target) ? $target['partnumber'] : '' ?>
                                                                        </td>
                                                                        <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                            <?php if (!array_key_exists("ordered", $target)) {
                                                                            ?>
                                                                                <img class="userImage" src="../../userimg/<?php echo $target['userID'] ?>.jpg" alt="userimage">
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                endif;
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
                                                                        <td colspan="5" scope="col" class="text-gray-800 py-2 text-center bg-indigo-300">
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
                                                        <input type="text" hidden name="customer_id" value="<?= $customer ?>">
                                                        <input type="text" hidden id="notification_id" name="notification_id" value="<?= $notification_id ?>">
                                                        <div class="rtl col-span-6 sm:col-span-4">
                                                            <label class="block font-medium text-sm text-gray-700">
                                                                قیمت
                                                            </label>
                                                            <?php
                                                            $value = null;
                                                            if ($finalPriceForm) {
                                                                $value = $finalPriceForm;
                                                            } else if (current($givenPrice)) {
                                                                $value = current($givenPrice)['price'];
                                                            }
                                                            ?>
                                                            <input value="<?= $value ?>" onkeyup="update_price(this)" data-target="<?= $relation_id ?>" name="price" class="ltr price-input-custome mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="<?php echo $partNumber ?>-price" data-code="<?php echo $code ?>" type="text" />
                                                            <p class="mt-2"></p>
                                                        </div>


                                                        <div class="rtl">
                                                            <button onclick="telegram(this)" data-target="<?= $relation_id ?>" data-customer="<?= $customer ?>" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
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
                                                                <td class="text-white bold text-center py-2 px-2 ">پیام دریافتی
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="min-w-full mb-4 border-b-2 border-white">
                                                                <td class="text-gray-800 py-2 text-center bg-indigo-300">
                                                                    <?= nl2br($messages); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="min-w-full mb-1 border-b-2 bg-red-400">
                                                                <td>
                                                                    <i class="px-1 material-icons tiny-text text-white">access_time</i>
                                                                    <span class="text-white px-1 py-2"><?= date('Y-m-d H:m:i', $message_date) ?></span>
                                                                </td>

                                                            </tr>
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
                        endif;
                    }
                    ?>
            <?php
                }
            endforeach;
        else :
            echo "<p class='rtl col-6 mx-auto flex items-center justify-center pt-10'>کد جدیدی در گروه جهت گزارش ارائه نگردیده است</p>"; ?>
        </div>
    <?php endif;
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
    <script src="./public/js/givePrice.js?v=<?= rand() ?>"></script>
    <?php

    require_once('./views/Layouts/footer.php');
