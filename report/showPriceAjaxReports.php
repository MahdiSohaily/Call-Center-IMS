<?php
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
        <div class="accordion">
            <?php

            foreach ($explodedCodes as $code_index => $code) {
            ?>
                <input type="checkbox" checked="true" name="panel" id="<?php echo $code ?>" class="hidden">
                <label for="<?php echo $code ?>" class="relative flex items-center bg-gray-700 text-white p-4 shadow border border-grey-500 hover:cursor-pointer">
                    <?php echo $code ?>
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
                            <div class="grid grid-cols-1 md:grid-cols-8 gap-6 lg:gap-2 lg:p-2 overflow-auto">
                                <div></div>
                                <!-- Start the code info section -->
                                <div class="bg-white rounded-lg overflow-auto">
                                    <div id="search_result" class="rtl p-3">
                                        <p class="text-center text-sm bg-gray-600 text-white p-2 my-3 rounded-md">
                                            <?php echo $index; ?>
                                        </p>
                                        <?php if ($information) { ?>
                                            <div>
                                                <p class="my-2">قطعه: <?php echo $information['relationInfo']['name'] ?></p>
                                                <p class="my-2">وضعیت: <?php echo array_key_exists("status_name", $information['relationInfo']) ? $information['relationInfo']['status_name'] : '' ?></p>
                                                <ul>
                                                    <?php foreach ($information['cars'] as $item) {
                                                    ?>
                                                        <li class="" v-for="elem in relationCars">
                                                            <?php echo $item ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
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
                                <div class="bg-white rounded-lg md:col-span-5 overflow-auto">
                                    <div class="p-3">
                                        <table class="min-w-full text-left text-sm font-light custom-table">
                                            <thead class="font-medium bg-green-600">
                                                <tr>
                                                    <th scope="col" class="px-3 py-3 text-white text-center">
                                                        شماره فنی
                                                    </th>
                                                    <th scope="col" class="px-3 py-3 text-white text-center">
                                                        مقدار موجودی
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
                                                    <tr style="position: relative;" class="relative">
                                                        <td class=" px-1">
                                                            <p class="text-center bold bg-gray-600 text-white px-2 py-3">
                                                                <?php echo $goods[$index]['partnumber'] ?>
                                                            </p>
                                                        </td>
                                                        <td class="px-1 pt-2">
                                                            <table class="min-w-full text-sm font-light p-2">
                                                                <thead class="font-medium">
                                                                    <tr>
                                                                        <?php foreach ($exist[$index] as $brand => $amount) {
                                                                            if ($amount > 0) {
                                                                        ?>
                                                                                <th scope="col" class="<?php echo $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> text-white text-center py-2 relative hover:cursor-pointer" data-key="<?php echo $index ?>" data-brand="<?php echo $brand ?>" onmouseover="seekExist(this)" onmouseleave="closeSeekExist(this)">
                                                                                    <?php echo $brand ?>
                                                                                    <div class="custome-tooltip" id="<?php echo $index . '-' . $brand ?>">
                                                                                        <?php
                                                                                        foreach ($stockInfo[$index][$brand] as $iterator => $item) {
                                                                                        ?>
                                                                                            <div>
                                                                                                <?php if ($item !== 0) { ?>
                                                                                                    <p><?php echo $iterator . ' : ' . $item ?></p>
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </th>
                                                                        <?php }
                                                                        } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="py-3">
                                                                        <?php foreach ($exist[$index] as $brand => $amount) {
                                                                            if ($amount > 0) {
                                                                                $total = 0;
                                                                                foreach ($stockInfo[$index][$brand] as $iterator => $item) {
                                                                                    $total += $item;
                                                                                } ?>
                                                                                <td class="<?php echo $brand == 'GEN' || $brand == 'MOB' ? $brand : 'brand-default' ?> whitespace-nowrap text-white px-3 py-2 text-center">
                                                                                    <?php echo $total;
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
                                                                            $price = doubleval($goods[$index]['price']);
                                                                            $price = str_replace(",", "", $price);
                                                                            $avgPrice = round(($price * 110) / 243.5);
                                                                            $finalPrice = round($avgPrice * $rate['amount'] * 1.2 * 1.2 * 1.3);
                                                                        ?>
                                                                            <td class="text-bold whitespace-nowrap px-3 py-2 text-center hover:cursor-pointer <?php echo $rate['status'] !== 'N' ? $rate['status'] : 'bg-gray-100' ?>" onclick="setPrice(this)" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">
                                                                                <?php echo $finalPrice ?>
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
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">

                                                                                    <?php echo  $finalPrice ?>
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
                                                                                <td class="text-bold whitespace-nowrap px-3 text-center py-2 hover:cursor-pointer" onclick="setPrice(this)" data-price="<?php echo $finalPrice ?>" data-part="<?php echo $partNumber ?>">

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
                                <div></div>
                            </div>
                        <?php } ?>
                </div>
            <?php
                    } else {
            ?>
                <div class="bg-white rounded-lg overflow-auto mb-3 py-4">
                    <p class="text-center">کد مد نظر در سیستم موجود نیست</p>
                </div>
        <?php
                    }
                }
        ?>
        <p id="form_success" class="custome-alert success px-3 tiny-text">
            ! موفقانه در پایگاه داده ثبت شد
        </p>
        <p id="form_error" class=" custome-alert error px-3 tiny-text">
            ! ذخیره سازی اطلاعات ناموفق بود
        </p>
        </div>
        <script>
            // Global controllers for operations messages
            const form_success = document.getElementById('form_success');
            const form_error = document.getElementById('form_error');
            // Global price variable
            let price = null;

            // A function to update the global price while typing in the input feild
            function update_price(element) {
                price = element.value;
            }

            // A function to set the price to we don't have
            function donotHave(element) {
                price = 'نداریم';
                part = element.getAttribute('data-part');
                const input = document.getElementById(part + '-price');
                input.value = price;

                createRelation(element);
            }

            // A function to send a request in order to ask the price for specific code
            function askPrice(element) {
                // Accessing the form fields to get thier value for an ajax store operation
                const partNumber = element.getAttribute('data-part');
                const user_id = element.getAttribute('data-user');
                const customer_id = document.getElementById('customer_id').value;

                const params = new URLSearchParams();
                params.append('askPrice', 'askPrice');
                params.append('partNumber', partNumber);
                params.append('customer_id', customer_id);
                params.append('user_id', user_id);

                axios.post("./app/Controllers/GivenPriceAjax.php", params)
                    .then(function(response) {
                        if (response.data == true) {
                            form_success.style.bottom = '10px';
                            setTimeout(() => {
                                form_success.style.bottom = '-300px';
                            }, 2000)
                        } else {
                            form_error.style.bottom = '10px';
                            setTimeout(() => {
                                form_error.style.bottom = '-300px';
                            }, 2000)
                        }
                    })
                    .catch(function(error) {});
            }

            // A function to create the relationship
            function createRelation(e) {
                // Accessing the form fields to get thier value for an ajax store operation
                const partNumber = e.getAttribute('data-part');
                const customer_id = document.getElementById('customer_id').value;
                const notification_id = document.getElementById('notification_id').value;

                const resultBox = document.getElementById('price-' + partNumber);

                // Defining a params instance to be attached to the axios request
                const params = new URLSearchParams();
                params.append('store_price', 'store_price');
                params.append('partNumber', partNumber);
                params.append('customer_id', customer_id);
                params.append('notification_id', notification_id);
                params.append('price', price);

                axios.post("./app/Controllers/GivenPriceAjax.php", params)
                    .then(function(response) {
                        if (response.data) {
                            form_success.style.bottom = '10px';
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

            // A function to set the price while cliking on the prices table
            function setPrice(element) {
                newPrice = element.getAttribute('data-price');
                part = element.getAttribute('data-part');
                const input = document.getElementById(part + '-price');
                input.value = newPrice;

                price = newPrice;
            }
        </script>
<?php
    }
} else {
    echo "<p class='rtl col-6 mx-auto flex items-center justify-center h-full'>کاربر درخواست دهنده و یا مشتری مشخص شده معتبر نمی باشد</p>";
}
