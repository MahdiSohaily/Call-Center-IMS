<?php
require_once('./views/Layouts/header.php');
require_once './database/connect.php';
require_once './utilities/helper.php';
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
        $relation_ids = $finalResult['relation_id'];
?>
        <div class="flex justify-between">
            <div class="m-2 p-2 w-1/4 bg-gray-600 relative">
                <table class="min-w-full h-full text-sm font-light p-2">
                    <thead class="font-medium">
                        <tr class="border">
                            <th class="text-left px-3 py-2">کد فنی</th>
                            <th class="text-left px-3 py-2">قیمت</th>
                            <th class="text-right  py-2" onclick="closeTab()">
                                <i id="copy_all" title="کاپی کردن مقادیر دارای قیمت" onclick="copyItemsWith(this)" class="text-xl pr-1 text-sm material-icons hover:cursor-pointer text-green-500">content_copy</i>
                                <i id="copy_all" title="کاپی کردن مقادیر" onclick="copyPrice(this)" class="text-xl pr-2 text-sm material-icons hover:cursor-pointer text-rose-500">content_copy</i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="priceReport">
                        <?php
                        foreach ($explodedCodes as $code) {
                            $relation_id =  array_key_exists($code, $relation_ids) ? $relation_ids[$code] : 'xxx';
                            $max = 0;
                            if (array_key_exists($code, $existing)) {
                                foreach ($existing[$code] as $item) {
                                    $max  += max($item['relation']['sorted']);
                                }
                            } ?>

                            <tr class="border">
                                <td class="px-3 py-2 text-left text-white hover:cursor-pointer" data-move="<?= $code ?>" onclick="onScreen(this)"><?= strtoupper($code) ?></td>
                                <td class="px-3 py-2 text-left text-white">
                                    <?php
                                    if (in_array($code, $not_exist)) {
                                        echo "<p class ='text-red-600' data-relation='" . $relation_id . "' id='" . $code . '-append' . "'>کد اشتباه</p>";
                                    } else {
                                        if ($max && current($existing[$code])['givenPrice']) {

                                            $target = current(current($existing[$code])['givenPrice']);
                                            $priceDate = $target['created_at'];

                                            $finalPrice = trim(current(current($existing[$code])['givenPrice'])['price']);


                                            if (checkDateIfOkay($applyDate, $priceDate) && $target['price'] !== 'موجود نیست') :
                                                $rawGivenPrice = $target['price'];
                                                $finalPrice = applyDollarRate($rawGivenPrice);
                                            endif; //

                                            echo $finalPrice !== 'موجود نیست' ? "<p data-relation='" . $relation_id . "' id='" . $code . '-append' . "'>" . $finalPrice . "</p>" : "<p data-relation='" . $relation_id . "' id='" . $code . '-append' . "' class ='text-yellow-400'>نیاز به بررسی</p>";
                                        } else if ($max) {
                                            echo "<p data-relation='" . $relation_id . "' id='" . $code . '-append' . "'class ='text-green-400'>نیاز به قیمت</p>";
                                        } else if ($max == 0) {
                                            echo "<p data-relation='" . $relation_id . "' id='" . $code . '-append' . "'>" . 'موجود نیست' . "</p>";
                                        }
                                    ?>
                                </td>
                                <td class="text-right py-2" onclick="closeTab()">
                                    <i title="کاپی کردن مقادیر" onclick="copyItemPrice(this)" class="px-4 text-white text-sm material-icons hover:cursor-pointer">content_copy</i>
                                </td>
                            <?php
                                    }
                            ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="m-2 p-2 w-1/4 bg-gray-600 relative">
                <form class="rtl w-full h-full border border-white flex gap-2 p-2 " target="_blank" action="giveOrderedPriceNew.php" method="post">
                    <div class="w-5/6 h-full">
                        <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
                        <input type="text" name="user" value="<?= $_SESSION["id"] ?>" hidden>
                        <input type="text" name="customer" value="1" id="target_customer" hidden>
                        <textarea onchange="filterCode(this)" id="code" name="code" required class="h-full bg-transparent ltr w-full p-3 text-white placeholder-white" placeholder="لطفا کد های مورد نظر خود را در خط های مجزا قرار دهید"></textarea>
                    </div>
                    <button type="type" class="inline-flex self-end p-3 bg-indigo-500 border-indigo-700  rounded-md font-semibold text-xs text-white hover:bg-indigo-700">
                        جستجو
                    </button>
                </form>
            </div>
            <div class="m-2 rtl w-1/5 flex justify-start bg-gray-600 p-2">
                <table class="col-6 text-sm border border-white font-light mb-2 w-full h-full">
                    <thead class="font-medium bg-gray-600 border-b border-white">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-white text-right">
                                نام
                            </th>
                            <th scope="col" class="px-3 py-3 text-white text-right">
                                نام خانوادگی
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        <tr>
                            <td class="px-1">
                                <p class="text-right bold px-2 py-3">
                                    <?= $customer_info['name'] ?>
                                </p>
                            </td>
                            <td class=" px-1">
                                <p class="text-right bold px-2 py-3">
                                    <?= $customer_info['family'] ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="accordion mb-10">
            <?php
            foreach ($explodedCodes as $code_index => $code) {
                $relation_id =  array_key_exists($code, $relation_ids) ? $relation_ids[$code] : 'xxx';
                $max = 0;
                if (array_key_exists($code, $existing)) {
                    foreach ($existing[$code] as $item) {
                        $max  += max($item['relation']['sorted']);
                    }
                }
            ?>
                <div id="<?= $code ?>" class="accordion-header bg-slate-500">
                    <p class="flex items-center gap-2">
                        <?= "<span class='text-white'>{$code}</span>";
                        if (in_array($code, $not_exist)) {
                            echo '<i class="material-icons text-neutral-400">block</i>';
                        } else if ($max > 0) {
                            echo '<i class="material-icons text-green-500">check_circle</i>';
                        } else {
                            echo '<i class="material-icons text-red-600">do_not_disturb_on</i>';
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
                            $exist =  $relation['existing'];
                            $sorted =  $relation['sorted'];
                            $stockInfo =  $relation['stockInfo'];
                            $givenPrice =  $item['givenPrice'];
                            $estelam = $item['estelam'];
                            $limit_id = $relation['limit_alert'];
                            // $customer = $customer;
                            // $completeCode = $completeCode;
                    ?>
                            <div class="grid grid-cols-1 grid-cols-1 lg:grid-cols-10 gap-6 lg:gap-2 lg:p-2 overflow-auto">
                                <!-- Start the code info section -->
                                <div class="min-w-full bg-white rounded-lg col-span-2 overflow-auto shadow-md mt-2">
                                    <div class="rtl p-3">
                                        <p style="font-size: 0.8rem;" class="text-left bg-gray-600 text-white p-2 my-3 rounded-md">
                                            <?= strtoupper($index); ?>
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
                                                                <?= strtoupper($goods[$index]['partnumber']); ?>
                                                            </p>
                                                            <div class="custome-tooltip-2" id="<?= $goods[$index]['partnumber'] . '-google' ?>">
                                                                <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?= $goods[$index]['partnumber'] ?>'>
                                                                    <img class="w-5 h-auto" src="./public/img/google.png" alt="google">
                                                                </a>
                                                                <a target='_blank' href='https://partsouq.com/en/search/all?q=<?= $goods[$index]['partnumber'] ?>'>
                                                                    <img class="w-5 h-auto" src="./public/img/part.png" alt="part">
                                                                </a>
                                                                <a title="بررسی تک آیتم" target='_blank' href='../../1402/singleItemReport.php?code=<?= $goods[$index]['partnumber'] ?>'>
                                                                    <svg title="بررسی" class="w-5 h-auto" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M512.3 510.1m-415.7 0a415.7 415.7 0 1 0 831.4 0 415.7 415.7 0 1 0-831.4 0Z" fill="#FFFFFF" />
                                                                        <path d="M512.3 933.8c-57.2 0-112.7-11.2-164.9-33.3-50.5-21.3-95.8-51.9-134.7-90.8-38.9-38.9-69.5-84.2-90.8-134.7-22.1-52.2-33.3-107.7-33.3-164.9s11.2-112.7 33.3-164.9c21.3-50.5 51.9-95.8 90.8-134.7s84.2-69.5 134.7-90.8c52.2-22.1 107.7-33.3 164.9-33.3S625 97.6 677.2 119.7c50.5 21.3 95.8 51.9 134.7 90.8 38.9 38.9 69.5 84.2 90.8 134.7 22.1 52.2 33.3 107.7 33.3 164.9S924.8 622.8 902.7 675c-21.3 50.5-51.9 95.8-90.8 134.7-38.9 38.9-84.2 69.5-134.7 90.8-52.2 22.1-107.7 33.3-164.9 33.3z m0-831.4c-55 0-108.4 10.8-158.7 32-48.5 20.5-92.1 49.9-129.6 87.4-37.4 37.4-66.8 81-87.4 129.6-21.3 50.3-32 103.6-32 158.7 0 55 10.8 108.4 32 158.7 20.5 48.5 49.9 92.1 87.4 129.6 37.4 37.4 81 66.8 129.6 87.4 50.3 21.3 103.6 32 158.7 32s108.4-10.8 158.7-32c48.5-20.5 92.1-49.9 129.6-87.4s66.8-81 87.4-129.6c21.3-50.3 32-103.6 32-158.7 0-55-10.8-108.4-32-158.7-20.5-48.5-49.9-92.1-87.4-129.6-37.4-37.4-81-66.8-129.6-87.4-50.2-21.2-103.6-32-158.7-32z" fill="#0A0408" />
                                                                        <path d="M512.3 510.1m-331.6 0a331.6 331.6 0 1 0 663.2 0 331.6 331.6 0 1 0-663.2 0Z" fill="#55B7A8" />
                                                                        <path d="M512.4 933.8c-0.1 0-0.1 0 0 0-2.4 0-4.9-0.1-7.3-0.3l1.3-15.9c2 0.2 4 0.2 6 0.2 2.8 0 5.6-0.2 8.4-0.5l1.8 15.9c-3.5 0.4-6.9 0.6-10.2 0.6z m-24.5-3.3c-5.4-1.5-10.9-3.5-16.2-5.9l6.7-14.5c4.5 2.1 9.1 3.8 13.7 5l-4.2 15.4z m51.6-0.8l-4.6-15.3c4.5-1.4 9.1-3.2 13.6-5.4l7.1 14.3c-5.3 2.6-10.7 4.8-16.1 6.4z m-82.8-13.4c-4.6-3-9.1-6.4-13.6-10.1l10.2-12.3c4 3.3 8 6.3 12.1 8.9l-8.7 13.5z m113.6-1.6l-9-13.2c4-2.7 8-5.8 11.9-9.2l10.5 12.1c-4.4 3.8-8.9 7.2-13.4 10.3z m-139.5-19.9c-3.8-3.8-7.5-8-11.2-12.3l12.3-10.3c3.4 4 6.8 7.9 10.3 11.4l-11.4 11.2z m165-1.9l-11.6-11.1c3.4-3.6 6.8-7.5 10.2-11.5l12.4 10.1c-3.6 4.4-7.3 8.6-11 12.5z m-186.3-23.5c-3.1-4.3-6.2-8.9-9.3-13.6l13.5-8.6c2.8 4.5 5.8 8.8 8.7 12.8l-12.9 9.4z m207.3-2.2l-13.1-9.3c2.9-4.1 5.8-8.4 8.6-12.9l13.6 8.5c-3 4.8-6.1 9.4-9.1 13.7z m-225-25.4c-2.6-4.6-5.2-9.5-7.8-14.4l14.3-7.3c2.4 4.7 4.9 9.4 7.4 13.8l-13.9 7.9z m242.4-2.4l-14-7.8c2.5-4.4 4.9-9.1 7.3-13.8l14.3 7.2c-2.4 4.9-5 9.8-7.6 14.4z m-257.3-26.7c-2.2-4.8-4.4-9.8-6.5-14.9l14.8-6.2c2 4.9 4.2 9.7 6.3 14.4l-14.6 6.7z m272-2.5l-14.6-6.6c2.1-4.7 4.2-9.5 6.2-14.4l14.8 6.1c-2.1 5-4.2 10.1-6.4 14.9z m-284.6-27.6c-1.9-5-3.7-10.1-5.5-15.3l15.1-5.2c1.7 5 3.5 10 5.4 14.8l-15 5.7z m297-2.5l-15-5.6c1.8-4.9 3.6-9.8 5.3-14.9l15.2 5.1c-1.8 5.3-3.7 10.4-5.5 15.4z m-307.6-28.2c-1.6-5.1-3.2-10.3-4.6-15.5l15.4-4.4c1.5 5.1 3 10.2 4.5 15.1l-15.3 4.8z m318-2.5l-15.3-4.7c1.5-5 3-10.1 4.5-15.2l15.4 4.3c-1.5 5.2-3 10.5-4.6 15.6z m-326.9-28.6c-1.3-5.1-2.6-10.4-3.9-15.7l15.6-3.6c1.2 5.2 2.5 10.3 3.8 15.4l-15.5 3.9z m335.7-2.6l-15.5-3.9c1.3-5.1 2.5-10.2 3.7-15.4l15.6 3.6c-1.2 5.2-2.5 10.5-3.8 15.7z m-343-29c-1.1-5.2-2.2-10.5-3.2-15.9l15.7-3c1 5.2 2 10.4 3.1 15.5l-15.6 3.4z m350.2-2.6l-15.7-3.2c1.1-5.1 2.1-10.3 3-15.5l15.7 2.9c-0.9 5.3-1.9 10.6-3 15.8z m-356.2-29.1c-0.9-5.2-1.7-10.6-2.5-16l15.8-2.3c0.8 5.2 1.6 10.5 2.5 15.7l-15.8 2.6z m362.1-2.7l-15.8-2.6c0.8-5.1 1.6-10.4 2.4-15.7l15.8 2.3c-0.7 5.4-1.6 10.8-2.4 16z m-366.8-29.3c-0.7-5.3-1.3-10.7-1.9-16l15.9-1.7c0.6 5.3 1.2 10.5 1.8 15.7l-15.8 2z m371.4-2.7l-15.9-2c0.6-5.2 1.2-10.5 1.8-15.8l15.9 1.7c-0.6 5.5-1.2 10.9-1.8 16.1z m-374.9-29.4c-0.5-5.3-0.9-10.7-1.3-16.1l16-1.1c0.4 5.3 0.8 10.6 1.3 15.8l-16 1.4z m378.2-2.7l-15.9-1.4c0.5-5.3 0.9-10.6 1.2-15.8l16 1.1c-0.4 5.3-0.8 10.7-1.3 16.1zM321 561.2c-0.3-5.3-0.5-10.8-0.7-16.1l16-0.6c0.2 5.3 0.4 10.6 0.7 15.8l-16 0.9z m382.8-2.7l-16-0.8c0.3-5.2 0.5-10.6 0.7-15.9l16 0.5c-0.2 5.4-0.5 10.9-0.7 16.2z m-383.9-29.6c-0.1-5.4-0.2-10.8-0.2-16.1h16c0 5.3 0.1 10.6 0.2 15.9l-16 0.2z m384.9-2.7l-16-0.3c0.1-5.3 0.1-10.6 0.1-15.9v-5.3l16-0.1v5.4c0.1 5.5 0 10.9-0.1 16.2z m-369-29.3l-16-0.2c0.1-5.4 0.2-10.8 0.4-16.1l16 0.5c-0.2 5.1-0.4 10.5-0.4 15.8z m352.9-7.9c-0.1-5.3-0.3-10.6-0.5-15.9l16-0.6c0.2 5.3 0.4 10.8 0.5 16.1l-16 0.4z m-352-23.9l-16-0.8c0.3-5.4 0.6-10.8 0.9-16.1l16 1c-0.3 5.3-0.6 10.7-0.9 15.9z m350.8-7.8c-0.3-5.3-0.7-10.6-1-15.8l16-1.2c0.4 5.3 0.8 10.7 1.1 16.1l-16.1 0.9z m-348.7-23.8l-15.9-1.3c0.4-5.4 0.9-10.8 1.5-16.1l15.9 1.6c-0.5 5.2-1 10.5-1.5 15.8z m346.3-7.9c-0.5-5.3-1-10.6-1.6-15.8l15.9-1.8c0.6 5.3 1.1 10.7 1.6 16.1l-15.9 1.5zM342 401.9l-15.9-1.9c0.6-5.3 1.3-10.7 2.1-16l15.8 2.2c-0.6 5.2-1.3 10.5-2 15.7z m339.6-7.8c-0.7-5.2-1.4-10.5-2.2-15.7l15.8-2.4c0.8 5.3 1.5 10.7 2.2 16l-15.8 2.1z m-335.2-23.6l-15.8-2.5c0.9-5.3 1.8-10.7 2.7-15.9l15.7 2.8c-0.8 5.2-1.7 10.4-2.6 15.6z m330.5-7.7c-0.9-5.2-1.8-10.5-2.8-15.6l15.7-3c1 5.2 2 10.6 2.9 15.9l-15.8 2.7z m-324.8-23.4l-15.7-3.2c1.1-5.3 2.2-10.6 3.4-15.8l15.6 3.5c-1.2 5.1-2.3 10.3-3.3 15.5z m318.9-7.7c-1.1-5.2-2.3-10.4-3.5-15.4l15.6-3.7c1.2 5.2 2.4 10.5 3.6 15.8l-15.7 3.3z m-312-23.2l-15.5-3.9c1.3-5.3 2.7-10.5 4.1-15.7l15.4 4.3c-1.3 5-2.7 10.2-4 15.3z m304.7-7.6c-1.4-5.1-2.8-10.3-4.2-15.2l15.4-4.4c1.5 5.1 2.9 10.4 4.3 15.6l-15.5 4zM367.5 278l-15.3-4.7c1.6-5.2 3.2-10.4 4.9-15.5l15.2 5.1c-1.7 5-3.3 10.1-4.8 15.1z m287.4-7.4c-1.6-5-3.3-10.1-5-15l15.1-5.3c1.8 5 3.5 10.2 5.1 15.4l-15.2 4.9z m-277.4-22.5l-15-5.5c1.9-5.1 3.9-10.2 5.8-15.2l14.8 6c-1.9 4.8-3.8 9.7-5.6 14.7z m266.9-7.3c-1.9-4.9-3.9-9.8-5.9-14.6l14.7-6.2c2.1 4.9 4.1 10 6.1 15.1l-14.9 5.7zM389.3 219l-14.6-6.5c2.2-5 4.6-10 6.9-14.8l14.4 7.1c-2.3 4.5-4.6 9.3-6.7 14.2z m242.8-7.1c-2.2-4.8-4.6-9.5-6.9-14.1l14.2-7.4c2.5 4.7 4.9 9.6 7.2 14.6l-14.5 6.9z m-229-21l-14-7.7c2.7-4.9 5.4-9.6 8.2-14.2l13.6 8.4c-2.6 4.3-5.2 8.8-7.8 13.5z m214.6-6.8c-2.7-4.6-5.4-9-8.2-13.3l13.4-8.7c2.9 4.5 5.8 9.2 8.6 14l-13.8 8z m-198.2-19.8l-13.1-9.1c3.2-4.6 6.5-9.1 9.8-13.3l12.5 10c-3.1 3.9-6.2 8.1-9.2 12.4z m181.2-6.2c-3.1-4.2-6.4-8.3-9.6-12.1l12.1-10.4c3.5 4.1 7 8.4 10.3 13l-12.8 9.5z m-162-17.9L427 129.4c3.9-4.2 7.9-8.1 11.9-11.7l10.7 11.9c-3.7 3.2-7.3 6.8-10.9 10.6z m142-5.4c-3.7-3.7-7.5-7.1-11.3-10.1l10-12.5c4.2 3.4 8.4 7.1 12.5 11.2l-11.2 11.4z m-119.4-14.7l-9.3-13c4.8-3.4 9.6-6.4 14.5-8.9l7.4 14.2c-4.1 2.2-8.4 4.8-12.6 7.7z m95.8-4.1c-4.3-2.7-8.7-5-13.1-6.9l6.3-14.7c5.1 2.2 10.2 4.9 15.2 7.9l-8.4 13.7z m-69.6-9.4l-5.1-15.2c5.6-1.9 11.2-3.2 16.9-4l2.3 15.8c-4.7 0.7-9.5 1.8-14.1 3.4z m42.7-2c-4.7-1.1-9.5-1.8-14.3-2l0.8-16c5.7 0.3 11.5 1.1 17.2 2.5l-3.7 15.5z" fill="#0A0408" />
                                                                        <path d="M912.7 518.1h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16zM867.4 312.7h-6.2v-16h6.2v16z m-22.2 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16zM867.4 723.5h-6.2v-16h6.2v16z m-22.2 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z m-32 0h-16v-16h16v16z" fill="#0A0408" />
                                                                        <path d="M809.7 475.1c0 72.8-131.9 181.7-131.9 181.7S545.9 547.9 545.9 475.1s59-131.9 131.9-131.9 131.9 59.1 131.9 131.9z" fill="#EBB866" />
                                                                        <path d="M677.8 667.1l-5.1-4.2c-5.5-4.5-134.8-112.1-134.8-187.8 0-77.1 62.7-139.9 139.9-139.9S817.7 398 817.7 475.1c0 75.7-129.3 183.3-134.8 187.8l-5.1 4.2z m0-315.9c-68.3 0-123.9 55.6-123.9 123.9 0 27.7 22 66.7 63.7 112.6 24.7 27.2 49.7 49.5 60.1 58.5 10.5-9 35.4-31.3 60.1-58.5 41.7-46 63.7-84.9 63.7-112.6 0.2-68.3-55.4-123.9-123.7-123.9z" fill="#0A0408" />
                                                                        <path d="M677.8 491.9c-22.7 0-41.1-18.5-41.1-41.1 0-22.7 18.5-41.1 41.1-41.1s41.1 18.5 41.1 41.1h-16c0-13.9-11.3-25.1-25.1-25.1s-25.1 11.3-25.1 25.1 11.3 25.1 25.1 25.1v16z" fill="#0A0408" />
                                                                        <path d="M677.8 558.2c-22.7 0-41.1-18.5-41.1-41.1h16c0 13.9 11.3 25.1 25.1 25.1s25.1-11.3 25.1-25.1-11.3-25.1-25.1-25.1v-16c22.7 0 41.1 18.5 41.1 41.1s-18.4 41.1-41.1 41.1z" fill="#0A0408" />
                                                                        <path d="M669.8 394.2h16v178.7h-16z" fill="#0A0408" />
                                                                        <path d="M678.9 739.1h-2.2c-12.5 0-22.6-10.1-22.6-22.6v-2.2c0-12.5 10.1-22.6 22.6-22.6h2.2c12.5 0 22.6 10.1 22.6 22.6v2.2c-0.1 12.5-10.2 22.6-22.6 22.6z" fill="#FFFFFF" />
                                                                        <path d="M678.9 747.1h-2.2c-16.8 0-30.6-13.7-30.6-30.6v-2.2c0-16.8 13.7-30.6 30.6-30.6h2.2c16.8 0 30.6 13.7 30.6 30.6v2.2c-0.1 16.9-13.8 30.6-30.6 30.6z m-2.2-47.3c-8 0-14.6 6.5-14.6 14.6v2.2c0 8 6.5 14.6 14.6 14.6h2.2c8 0 14.6-6.5 14.6-14.6v-2.2c0-8-6.5-14.6-14.6-14.6h-2.2z" fill="#0A0408" />
                                                                        <path d="M513.4 949.4h-2.2c-12.5 0-22.6-10.1-22.6-22.6v-2.2c0-12.5 10.1-22.6 22.6-22.6h2.2c12.5 0 22.6 10.1 22.6 22.6v2.2c0 12.5-10.1 22.6-22.6 22.6z" fill="#DC444A" />
                                                                        <path d="M513.4 957.4h-2.2c-16.8 0-30.6-13.7-30.6-30.6v-2.2c0-16.8 13.7-30.6 30.6-30.6h2.2c16.8 0 30.6 13.7 30.6 30.6v2.2c0 16.9-13.7 30.6-30.6 30.6z m-2.2-47.3c-8 0-14.6 6.5-14.6 14.6v2.2c0 8 6.5 14.6 14.6 14.6h2.2c8 0 14.6-6.5 14.6-14.6v-2.2c0-8-6.5-14.6-14.6-14.6h-2.2z" fill="#0A0408" />
                                                                        <path d="M513.4 120.6h-2.2c-12.5 0-22.6-10.1-22.6-22.6v-2.2c0-12.5 10.1-22.6 22.6-22.6h2.2c12.5 0 22.6 10.1 22.6 22.6V98c0 12.5-10.1 22.6-22.6 22.6z" fill="#DC444A" />
                                                                        <path d="M513.4 128.6h-2.2c-16.8 0-30.6-13.7-30.6-30.6v-2.2c0-16.8 13.7-30.6 30.6-30.6h2.2c16.8 0 30.6 13.7 30.6 30.6V98c0 16.9-13.7 30.6-30.6 30.6z m-2.2-47.3c-8 0-14.6 6.5-14.6 14.6v2.2c0 8 6.5 14.6 14.6 14.6h2.2c8 0 14.6-6.5 14.6-14.6v-2.2c0-8-6.5-14.6-14.6-14.6h-2.2z" fill="#0A0408" />
                                                                    </svg>
                                                                </a>
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
                                                                                    <th onclick="appendBrand(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $brand ?>" data-part="<?= $partNumber ?>" scope="col" class="<?= $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> text-white text-center py-2 relative hover:cursor-pointer" data-key="<?= $index ?>" data-part="<?= $partNumber ?>" data-brand="<?= $brand ?>" onmouseover="seekExist(this)" onmouseleave="closeSeekExist(this)">
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
                                                                                                        <?php if ($item !== 0 && $item['name'] === $brand && $item['qty'] > 0) { ?>
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
                                                                            <td class="text-bold whitespace-nowrap px-3 py-2 text-center hover:cursor-pointer <?= $rate['status'] !== 'N' ? $rate['status'] : 'bg-gray-100' ?>" onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">
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
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">

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
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPrice ?>" data-part="<?= $partNumber ?>">

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
                                    <?php if ($limit_id && $_SESSION['username'] === 'niyayesh' || $limit_id && $_SESSION['username'] === 'mahdi') :
                                        $fraction = explode('-', $limit_id);
                                        $id = $fraction[0];
                                        $type = $fraction[1];

                                        $overall = overallSpecification($conn, $id, $type);
                                        $inventory = inventorySpecification($conn, $id, $type);
                                        $mode = 'create';

                                        if ($overall) :
                                            $mode = 'update';
                                        else :
                                            $overall = ['original_all' => 0, 'fake_all' => 0];
                                            $inventory = ['original' => 0, 'fake' => 0];
                                        endif;
                                    ?>
                                        <div class="p-3 rtl ">
                                            <form id="f-<?= $partNumber ?>" action="" class="bg-gray-200 rounded-md p-3" method="post">
                                                <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                                <input id="type" type="hidden" name="type" value="<?= $type ?>" />
                                                <input id="operation" type="hidden" name="operation" value="<?= $mode ?>" />
                                                <div class="flex gap-2">
                                                    <fieldset class="flex-grow">
                                                        <legend> هشدار موجودی انبار یدک شاپ:</legend>
                                                        <div class="col-span-12 sm:col-span-4 mb-3 flex flex-wrap gap-2 ">
                                                            <div class="flex-grow">
                                                                <label for="original" class="block font-medium text-sm text-gray-700">
                                                                    مقدار اصلی
                                                                </label>
                                                                <input name="original" value="<?= $inventory['original'] ? $inventory['original'] : 0 ?>" class="ltr border text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="original" type="number" min='0' />
                                                            </div>
                                                            <div class="flex-grow">
                                                                <label for="fake" class="block font-medium text-sm text-gray-700">
                                                                    مقدار غیر اصلی
                                                                </label>
                                                                <input name="fake" value="<?= $inventory['fake'] ? $inventory['fake'] : 0 ?>" class="ltr border text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="fake" type="number" min='0' />
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="flex-grow">
                                                        <legend> هشدار موجودی کلی:</legend>
                                                        <div class="col-span-12 sm:col-span-4 mb-3 flex flex-wrap gap-2 ">
                                                            <div class="flex-grow">
                                                                <label for="original" class="block font-medium text-sm text-gray-700">
                                                                    مقدار اصلی
                                                                </label>
                                                                <input name="original_all" value="<?= $overall['original_all'] ? $overall['original_all'] : 0 ?>" class="ltr border text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="original_all" type="number" min='0' />
                                                            </div>
                                                            <div class="flex-grow">
                                                                <label for="fake" class="block font-medium text-sm text-gray-700">
                                                                    مقدار غیر اصلی
                                                                </label>
                                                                <input name="fake_all" value="<?= $overall['fake_all'] ? $overall['fake_all'] : 0 ?>" class="ltr border text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="fake_all" type="number" min='0' />
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <button onclick="setLimitAlert(event)" data-form="<?= $partNumber ?>" class="button bg-blue-400 px-5 py-2 rounded-md text-white" type="submit">ذخیره</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <script>
                                    function setLimitAlert(e) {
                                        e.preventDefault();
                                        const formId = 'f-' + e.target.getAttribute('data-form');
                                        const targetForm = document.getElementById(formId);

                                        const id = targetForm.querySelector('#id').value;

                                        const type = targetForm.querySelector('#type').value;
                                        const operation = targetForm.querySelector('#operation').value;
                                        const original = targetForm.querySelector('#original').value;
                                        const fake = targetForm.querySelector('#fake').value;
                                        const original_all = targetForm.querySelector('#original_all').value;
                                        const fake_all = targetForm.querySelector('#fake_all').value;

                                        const params = new URLSearchParams();
                                        params.append('id', id);
                                        params.append('type', type);
                                        params.append('operation', operation);
                                        params.append('original', original);
                                        params.append('fake', fake);
                                        params.append('original_all', original_all);
                                        params.append('fake_all', fake_all);

                                        axios
                                            .post("./saveGoodLimitAJAX.php", params)
                                            .then(function(response) {
                                                if (response.data == true) {
                                                    const form_success = document.getElementById('form_success');
                                                    form_success.style.bottom = "10px";
                                                    setTimeout(() => {
                                                        form_success.style.bottom = "-300px";
                                                    }, 2000);
                                                } else {
                                                    const form_error = document.getElementById('form_error');
                                                    form_error.style.bottom = "10px";
                                                    setTimeout(() => {
                                                        form_error.style.bottom = "-300px";
                                                    }, 2000);
                                                }
                                            })
                                            .catch(function(error) {});
                                    }
                                </script>

                                <!-- Given Price section -->
                                <div class="min-w-full bg-white rounded-lg col-span-3 overflow-auto shadow-md">
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

                                                <?php
                                                $finalPriceForm = '';
                                                if ($givenPrice !== null && count($givenPrice) > 0) {
                                                    $target = current($givenPrice);
                                                    $priceDate = $target['created_at'];
                                                    if (checkDateIfOkay($applyDate, $priceDate) && $target['price'] !== 'موجود نیست') :
                                                        $rawGivenPrice = $target['price'];

                                                        $finalPriceForm = (applyDollarRate($rawGivenPrice));
                                                ?>
                                                        <tr class="min-w-full mb-1  bg-cyan-400 hover:cursor-pointer">
                                                            <td>
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                <?= $target['price'] === null ? 'ندارد' :  $finalPriceForm ?>
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                افزایش قیمت <?= $additionRate ?> در صد
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" class="bold <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?> ">
                                                                <?= array_key_exists("partnumber", $target) ? $target['partnumber'] : '' ?>
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                <?php if (!array_key_exists("ordered", $target)) {
                                                                ?>
                                                                    <img class="userImage" src="../../userimg/<?= $target['userID'] ?>.jpg" alt="userimage">
                                                                <?php
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    foreach ($givenPrice as $price) { ?>
                                                        <?php if ($price['price'] !== null && $price['price'] !== '') {
                                                            if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                                                                <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer">
                                                                <?php } else { ?>
                                                                <tr class="min-w-full mb-1  bg-indigo-200 hover:cursor-pointer">
                                                                <?php  } ?>
                                                                <td data-part="<?= $partNumber ?>" data-code="<?= $code ?>" onclick="deleteGivenPrice(this)" data-del='<?= $price['id'] ?>' data-target="<?= $relation_id ?>" scope="col" class="text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <i id="deleteGivenPrice" class="material-icons" title="حذف قیمت">close</i>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?= $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?php if (array_key_exists("ordered", $price)) {
                                                                        echo 'قیمت دستوری';
                                                                    } else {
                                                                        echo $price['name'] . ' ' . $price['family'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" class="bold <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?> ">
                                                                    <?= array_key_exists("partnumber", $price) ? $price['partnumber'] : '' ?>
                                                                </td>
                                                                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                    <?php if (!array_key_exists("ordered", $price)) {
                                                                    ?>
                                                                        <img class="userImage" src="../../userimg/<?= $price['userID'] ?>.jpg" alt="userimage">
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                </tr>
                                                                <tr class="min-w-full mb-1 border-b-2 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'bg-red-500' : 'bg-indigo-300' ?>" data-price='<?= $price['price'] ?>'>
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

                                                        <?php }
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

                                            <?php
                                            date_default_timezone_set("Asia/Tehran"); ?>
                                            <input type="text" hidden name="store_price" value="store_price">
                                            <input type="text" hidden name="partNumber" value="<?= $partNumber ?>">
                                            <input type="text" hidden id="customer_id" name="customer_id" value="<?= $customer ?>">
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
                                                <input value="<?= $value ?>" onkeyup="update_price(this)" data-target="<?= $relation_id ?>" name="price" class="ltr price-input-custome mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="<?= $partNumber ?>-price" data-code="<?= $code ?>" type="text" />
                                                <p class="mt-2"></p>
                                            </div>


                                            <div class="rtl">
                                                <button onclick="createRelation(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                    ثبت قیمت
                                                </button>
                                                <button onclick="donotHave(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" type="submit" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
                                                    موجود نیست
                                                </button>
                                                <button onclick="askPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-user="<?= $_SESSION['user_id'] ?>" data-part="<?= $partNumber ?>" type="button" class="disabled:cursor-not-allowed  disabled:bg-gray-500 tiny-txt inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 px-2 py-2">
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
        <p id="copied_message" style="display:none;position: fixed; top:50%; left:50%; transform: translate(-50%, -50%); font-size: 60px;font-weight: bold; color:seagreen">کد ها کاپی شدند</p>
        <script src="./public/js/givePrice.js?v=<?= rand() ?>"></script>
<?php
    }
} else {
    echo "<p class='rtl col-6 mx-auto flex items-center justify-center h-full'>کاربر درخواست دهنده و یا مشتری مشخص شده معتبر نمی باشد</p>";
}

require_once('./views/Layouts/footer.php');
