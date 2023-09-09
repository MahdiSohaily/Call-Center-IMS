<?php
require_once('./views/Layouts/header.php');
require_once './utilities/helper.php';
require_once './database/connect.php';
require_once('./app/Controllers/GivenPriceController.php');

if ($isValidCustomer) {
    if ($finalResult) {
        $explodedCodes = $finalResult['explodedCodes'];
        $not_exist = $finalResult['not_exist'];
        $existing = $finalResult['existing'];
        $customer = $finalResult['customer'];
        $completeCode = $finalResult['completeCode'];
        $notification = $finalResult['notification'];
        $rates = $finalResult['rates'];
?>
        <div class="accordion mb-10">
            <?php
            foreach ($explodedCodes as $code_index => $code) {
                $max = 0;
                if (array_key_exists($code, $existing)) {
                    foreach ($existing[$code] as $item) {
                        $max  += $item['relation']['amount'];
                    }
                }
            ?>
                <div id="<?= $code ?>" class="accordion-header bg-slate-500">
                    <p class="flex items-center gap-2">
                        <?php echo "<span class='text-white'>{$code}</span>";
                        if (in_array($code, $not_exist)) {
                            echo '<i class="material-icons text-neutral-400 bg-white rounded-circle">block</i>';
                        } else if ($max > 0) {
                            echo '<i class="material-icons text-green-500 bg-white rounded-circle">check_circle</i>';
                        } else {
                            echo '<i class="material-icons text-red-600 bg-white rounded-circle">do_not_disturb_on</i>';
                        } ?>
                    </p>
                </div>
                <div class="accordion-content overflow-hidden bg-grey-lighter" style="<?= $max > 0 ? 'max-height: 1000vh' : 'max-height: 0vh' ?>">
                    <?php
                    if (array_key_exists($code, $existing)) {
                        foreach ($existing[$code] as $index => $item) {
                            $partNumber = $index;
                            $information = $item['information'];
                            $relation = $item['relation'];
                            $goods =  $relation['goods'];
                            $givenPrice =  $item['givenPrice'];
                            $estelam = $item['estelam'];
                            $customer = $customer;
                            $completeCode = $completeCode;
                    ?>
                            <div class="grid grid-cols-1 grid-cols-1 lg:grid-cols-9 gap-6 lg:gap-2 lg:p-2 overflow-auto">
                                <!-- Start the code info section -->
                                <div class="min-w-full bg-white rounded-lg col-span-2 overflow-auto shadow-md mt-2">
                                    <div class="rtl p-3">
                                        <p style="font-size: 0.8rem;" class="text-left bg-gray-600 text-white p-2 my-3 rounded-md">
                                            <?php echo $index; ?>
                                        </p>
                                        <?php if ($information) { ?>
                                            <div>
                                                <p class="my-2">قطعه: <?php echo $information['relationInfo']['name'] ?></p>
                                                <?php if (array_key_exists("status_name", $information['relationInfo'])) { ?>
                                                    <p class="my-2">وضعیت: <?php echo  $information['relationInfo']['status_name'] ?></p>
                                                <?php } ?>
                                                <ul>
                                                    <?php foreach ($information['cars'] as $item) {
                                                    ?>
                                                        <li class="" v-for="elem in relationCars">
                                                            <?php echo $item ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php if ($information['relationInfo']['description'] !== '' && $information['relationInfo']['description'] !== null) { ?>
                                                    <p>توضیحات:</p>
                                                    <p class="bg-red-500 text-white rounded-md p-2 shake">
                                                        <?php echo $information['relationInfo']['description'] ?>
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
                                                foreach ($goods as $index => $element) {
                                                ?>
                                                    <tr>
                                                        <td class="relative px-1 hover:cursor-pointer" data-part="<?php echo $goods[$index]['information']['partnumber'] ?>" onmouseleave="hideToolTip(this)" onmouseover="showToolTip(this)">
                                                            <p class="text-center bold bg-gray-600 text-white px-2 py-3">
                                                                <?php echo $goods[$index]['information']['partnumber'] ?>
                                                            </p>
                                                            <div class="custome-tooltip-2" id="<?php echo $goods[$index]['information']['partnumber'] . '-google' ?>">
                                                                <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?php echo $goods[$index]['information']['partnumber'] ?>'>
                                                                    <img class="w-5 h-auto" src="./public/img/google.png" alt="google">
                                                                </a>
                                                                <a target='_blank' href='https://partsouq.com/en/search/all?q=<?php echo $goods[$index]['information']['partnumber'] ?>'>
                                                                    <img class="w-5 h-auto" src="./public/img/part.png" alt="part">
                                                                </a>
                                                            </div>
                                                        </td>


                                                        <td class="px-1 pt-2">
                                                            <table class="min-w-full text-left text-sm font-light">
                                                                <thead class="font-medium">
                                                                    <tr>
                                                                        <?php
                                                                        foreach ($rates as $rate) {
                                                                        ?>
                                                                            <th v-for="rate in rates" scope="col" class="text-gray-800 text-center py-2 <?php echo $rate['status'] !== 'N' ? $rate['status'] : 'bg-green-700' ?>">
                                                                                <?php echo $rate['amount'] ?>
                                                                            </th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="py-3">
                                                                        <?php
                                                                        foreach ($rates as $rate) {
                                                                            $price = doubleval($goods[$index]['information']['price']);
                                                                            $price = str_replace(",", "", $price);
                                                                            $avgPrice = round(($price * 110) / 243.5);
                                                                            $finalPrice = round($avgPrice * $rate['amount'] * 1.2 * 1.2 * 1.3);
                                                                        ?>
                                                                            <td class="text-bold whitespace-nowrap px-3 py-2 text-center hover:cursor-pointer <?php echo $rate['status'] !== 'N' ? $rate['status'] : 'bg-gray-100' ?>" onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">
                                                                                <?php echo $finalPrice ?>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                    <?php if ($goods[$index]['information']['mobis'] > 0 && $goods[$index]['information']['mobis'] !== '-') { ?>
                                                                        <tr class="bg-neutral-400">
                                                                            <?php
                                                                            foreach ($rates as $rate) {
                                                                                $price = doubleval($goods[$index]['information']['mobis']);
                                                                                $price = str_replace(",", "", $price);
                                                                                $avgPrice = round(($price * 110) / 243.5);
                                                                                $finalPrice = round($avgPrice * $rate['amount'] * 1.25 * 1.3)

                                                                            ?>
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">

                                                                                    <?php echo  $finalPrice ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <?php if ($goods[$index]['information']['korea'] > 0 && $goods[$index]['information']['mobis'] !== '-') { ?>
                                                                        <tr class="bg-amber-600" v-if="props.relation.goods[key].korea > 0">
                                                                            <?php
                                                                            foreach ($rates as $rate) {
                                                                                $price = doubleval($goods[$index]['information']['korea']);
                                                                                $price = str_replace(",", "", $price);
                                                                                $avgPrice = round(($price * 110) / 243.5);
                                                                                $finalPrice = round($avgPrice * $rate['amount'] * 1.25 * 1.3)

                                                                            ?>
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">

                                                                                    <?php echo  $finalPrice ?>
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
                                            <tbody id="price-<?php echo $partNumber ?>">
                                                <?php if ($givenPrice !== null) {
                                                ?>
                                                    <?php foreach ($givenPrice as $price) { ?>
                                                        <?php if ($price['price'] !== null && $price['price'] !== '') {
                                                            if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                                                                <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer">
                                                                <?php } else { ?>
                                                                <tr class="min-w-full mb-1  bg-indigo-200 hover:cursor-pointer">
                                                                <?php  } ?>
                                                                <td data-part="<?php echo $partNumber ?>" data-code="<?php echo $code ?>" onclick="deleteGivenPrice(this)" data-del='<?php echo $price['id'] ?>' scope="col" class="text-center text-gray-800 px-2 py-1 <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <i id="deleteGivenPrice" class="material-icons" title="حذف قیمت">close</i>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?php if (array_key_exists("ordered", $price)) {
                                                                        echo 'قیمت دستوری';
                                                                    } else {
                                                                        echo $price['name'] . ' ' . $price['family'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partNumber ?>" class="bold <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?> ">
                                                                    <?php echo array_key_exists("partnumber", $price) ? $price['partnumber'] : '' ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?php if (!array_key_exists("ordered", $price)) {
                                                                    ?>
                                                                        <img class="userImage" src="../../userimg/<?php echo $price['userID'] ?>.jpg" alt="userimage">
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                </tr>
                                                                <tr class="min-w-full mb-1 border-b-2 <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'bg-red-500' : 'bg-indigo-300' ?>" data-price='<?php echo $price['price'] ?>'>
                                                                    <td></td>
                                                                    <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                                                        <div class="rtl flex items-center w-full <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                                                            <i class="px-1 material-icons tiny-text <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
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

                                                        <?php }
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

                                            <?php
                                            date_default_timezone_set("Asia/Tehran"); ?>
                                            <input type="text" hidden name="store_price" value="store_price">
                                            <input type="text" hidden name="partNumber" value="<?php echo $partNumber ?>">
                                            <input type="text" hidden id="customer_id" name="customer_id" value="<?php echo $customer ?>">
                                            <input type="text" hidden id="notification_id" name="notification_id" value="<?php echo $notification_id ?>">
                                            <div class="rtl col-span-6 sm:col-span-4">
                                                <label class="block font-medium text-sm text-gray-700">
                                                    قیمت
                                                </label>
                                                <input value="<?= current($givenPrice) ? current($givenPrice)['price'] : '' ?>" onkeyup="update_price(this)" name="price" class="ltr price-input-custome mt-1 block w-full border-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="<?php echo $partNumber ?>-price" data-code="<?php echo $code ?>" type="text" />
                                                <p class="mt-2"></p>
                                            </div>


                                            <div class="rtl">
                                                <button onclick="createRelation(this)" data-code="<?php echo $code ?>" data-part="<?php echo $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                    ثبت قیمت
                                                </button>
                                                <button onclick="donotHave(this)" data-code="<?php echo $code ?>" data-part="<?php echo $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                    موجود نیست
                                                </button>
                                                <button onclick="askPrice(this)" data-code="<?php echo $code ?>" data-user="<?php echo $_SESSION['user_id'] ?>" data-part="<?php echo $partNumber ?>" type="button" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                    ارسال به نیایش
                                                </button>
                                            </div>
                                        </form>
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
        <script src="./public/js/givePrice.js?v=<?= rand() ?>"></script>
<?php
    }
} else {
    echo "<p class='rtl col-6 mx-auto flex items-center justify-center h-full'>کاربر درخواست دهنده و یا مشتری مشخص شده معتبر نمی باشد</p>";
}

require_once('./views/Layouts/footer.php');
