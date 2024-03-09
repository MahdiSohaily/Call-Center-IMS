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
                                                $finalPrice = applyDollarRate($rawGivenPrice, $priceDate);
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
                    ?>
                            <div class="grid grid-cols-1 grid-cols-1 lg:grid-cols-10 gap-6 lg:gap-2 lg:p-2 overflow-auto">
                                <!-- Start the code info section -->
                                <div class="min-w-full bg-white rounded-lg col-span-2 overflow-auto shadow-md mt-2">
                                    <div class="rtl p-3">
                                        <p class="text-sm text-center bg-gray-600 text-white p-2 my-3 rounded-md font-bold">
                                            <?= strtoupper($index); ?>
                                        </p>
                                        <?php if ($information) { ?>
                                            <div class="bg-blue-400 rounded-md p-3 text-sm text-white">
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
                                            <p class="">
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
                                                                    <?php
                                                                    $not_registered = !is_registered($goods[$index]['partnumber'], $conn);
                                                                    echo strtoupper($goods[$index]['partnumber']); ?>
                                                                </p>
                                                                <div class="custom-tooltip" id="<?= $goods[$index]['partnumber'] . '-google' ?>">
                                                                    <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?= $goods[$index]['partnumber'] ?>'>
                                                                        <img class="w-5 h-auto" src="./public/img/google.png" alt="google">
                                                                    </a>
                                                                    <a target='_blank' href='https://partsouq.com/en/search/all?q=<?= $goods[$index]['partnumber'] ?>'>
                                                                        <img class="w-5 h-auto" src="./public/img/part.png" alt="part">
                                                                    </a>
                                                                    <a title="بررسی تک آیتم" target='_blank' href='../../1402/singleItemReport.php?code=<?= $goods[$index]['partnumber'] ?>'>
                                                                        <img src="./public/img/singleItem.svg" class="w-5 h-auto" alt="">
                                                                    </a>
                                                                    <?php if ($not_registered) { ?>
                                                                        <a title="افزودن به لیست پیام خودکار" onclick="addSelectedGood('<?= $goods[$index]['partnumber'] ?>', this)">
                                                                            <img src="./public/img/add_good.svg" class="w-5 h-auto" alt="">
                                                                        </a>
                                                                    <?php } ?>
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
                                                                            echo '<p class="text-rose-500 text-sm text-center font-bold"> در حال حاضر موجود نیست </p>';
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

                                                        $finalPriceForm = (applyDollarRate($rawGivenPrice, $priceDate));
                                                ?>
                                                        <tr class="min-w-full mb-1  bg-cyan-400 hover:cursor-pointer text-sm">
                                                            <td>
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" scope="col" class="relative text-center text-gray-900 px-2 py-1 <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                <?= $target['price'] === null ? 'ندارد' :  $finalPriceForm ?>
                                                            </td>
                                                            <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $finalPriceForm ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                                                                افزایش قیمت <?= $appliedRate ?> در صد
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
                                                                <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer text-sm">
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
