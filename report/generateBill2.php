<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once './LoadBillDetails.php';
require_once('./views/Layouts/header.php');
?>
<script src="./public/js/persianDate.js"></script>
<link rel="stylesheet" href="./public/css/bill.css?v=<?= rand() ?>" />
<style>
    .bill_icon {
        width: 25px;
        height: 25px;
        max-width: 25px !important;
        cursor: pointer;
    }

</style>
<div style="height: 350px !important;" class="rtl h-1/3 grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6  px-2 mb-3">
    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/customer.svg" alt="customer icon">
                انتخاب مشتری
            </h2>
        </div>
        <div class="relative flex justify-center px-3">
            <input onkeyup="convertToPersian(this); searchCustomer(this.value)" type="text" name="customer" class="rounded-md py-3 px-3 w-full border text-md border-gray-300 focus:outline-none text-gray-500" id="customer_name" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
            <img class="absolute left-5 top-3 cursor-pointer" onclick="(() => {
                                                                                    searchCustomer('');
                                                                                    document.getElementById('customer_name').value = '';
                                                                                })();" src="./public/img/clear.svg" alt="customer icon">

        </div>
        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        <div id="customer_results" style="overflow-y: auto; height:300px" class="p-3 overflow-y-auto">
            <!-- Search Results are going to be appended here -->
        </div>
    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/barcode.svg" alt="customer icon">
                انتخاب کد فنی
            </h2>
        </div>
        <div class="relative flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); searchPartNumber(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border text-md border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder="کد فنی قطعه مورد نظر را وارد کنید..." />
            <img class="absolute left-5 top-3 cursor-pointer" onclick="(() => {
                                                                                    searchPartNumber('');
                                                                                    document.getElementById('serial').value = '';
                                                                                })();" src="./public/img/clear.svg" alt="customer icon">
        </div>
        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        <p id="select_box_error" class="px-3 tiny-text text-red-500 hidden">
            لیست اجناس انتخاب شده برای افزودن به رابطه خالی بوده نمیتواند!
        </p>
        <div id="selected_box" class="p-3" style="overflow-y: auto; height:300px">
            <!-- selected items are going to be added here -->
        </div>
    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/inventory.svg" alt="inventory icon">
                انتخاب کالای موجود
            </h2>
        </div>

        <div class="relative flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); searchInStock(this.value)" type="text" name="stock_partNumber" id="stock_partNumber" class="rounded-md py-3 px-3 w-full border text-md border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
            <img class="absolute left-5 top-3 cursor-pointer" onclick="(() => {
                                                                                    searchInStock('');
                                                                                    document.getElementById('stock_partNumber').value = '';
                                                                                })();" src="./public/img/clear.svg" alt="customer icon">

        </div>

        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        <div id="stock_result" class="p-3" style="overflow-y: auto; height:300px"></div>
    </div>
</div>
<div class="rtl grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6 px-2 mb-4">
    <div class="bg-white rounded-lg shadow-md p-2 w-full">
        <table class="min-w-full border border-gray-800 text-gray-400 mb-5">
            <thead>
                <tr class="bg-gray-800 text-white text-center border-b mb-2">
                    <th colspan="2" class="py-2">
                        مشخصات خریدار
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">نام</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border" type="hidden" name="id" id="id">
                        <input class="w-full p-2 border" type="hidden" name="type" id="mode" value='create'>
                        <input onkeyup="updateCustomerInfo(this)" class="w-full p-2 border text-gray-500" placeholder="نام مشتری را وارد کنید..." type="text" name="name" id="name">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">نام خانوادگی</td>
                    <td class="py-2 px-4">
                        <input onkeyup="updateCustomerInfo(this)" class="w-full p-2 border text-gray-500" placeholder="نام خانوادگی مشتری را وارد کنید..." type="text" name="family" id="family">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">تلفون</td>
                    <td class="py-2 px-4">
                        <input onkeyup="sanitizeInput(this);updateCustomerInfo(this)" class="w-full p-2 border text-gray-500 ltr" placeholder="093000000000" type="text" name="phone" id="phone">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">آدرس</td>
                    <td class="py-2 px-4">
                        <textarea onkeyup="updateCustomerInfo(this)" name="address" id="address" cols="30" rows="4" class="border p-2 w-full text-gray-500" placeholder="آدرس مشتری"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">ماشین</td>
                    <td class="py-2 px-4">
                        <input onkeyup="updateCustomerInfo(this)" class="w-full p-2 border text-gray-500" placeholder="نوعیت ماشین مشتری را مشخص کنید" type="text" name="car" id="car">
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="min-w-full border border-gray-800 text-gray-400 mb-5">
            <thead>
                <tr class="bg-gray-800 text-white text-center border-b mb-2">
                    <th colspan="2" class="py-2">
                        اطلاعات فاکتور
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="<?= !$billInfo['billNO'] ? 'hidden' : '' ?>">
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">شماره فاکتور</td>
                    <td class="py-2 px-4">
                        <input readonly onkeyup="updateBillInfo(this)" class="w-full p-2 border text-gray-500" placeholder="شماره فاکتور را وارد نمایید" type="text" name="billNO" id="billNO">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">تعداد اقلام</td>
                    <td class="py-2 px-4">
                        <input readonly class="w-full p-2 border text-gray-500" placeholder="تعداد اقلام فاکتور" type="text" name="quantity" id="quantity">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">جمع کل</td>
                    <td class="py-2 px-4">
                        <input readonly class="w-full p-2 border text-gray-500" placeholder="جمع کل اقلام فاکتور" type="text" name="totalPrice" id="totalPrice">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">تخفیف</td>
                    <td class="py-2 px-4">
                        <input onkeyup="updateBillInfo(this)" class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="discount" id="discount">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">مالبات (۰٪)</td>
                    <td class="py-2 px-4">
                        <input onkeyup="updateBillInfo(this)" class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="tax" id="tax">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-3 text-white bg-gray-800 text-md">عوارض</td>
                    <td class="py-2 px-4">
                        <input onkeyup="updateBillInfo(this)" class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="withdraw" id="withdraw">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="bg-gray-800 text-white h-10 border-top">
                        <p id="total_in_word" class="px-3 text-md"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow-md p-2 w-full col-span-3">
        <div class="container mx-auto">
            <table class="min-w-full border border-gray-800 text-gray-400">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="py-2 px-4 border-b text-white w-10">#</th>
                        <!-- <th class="py-2 px-4 border-b text-white">کد فنی</th> -->
                        <th class="py-2 px-4 border-b text-white text-right w-2/4">نام قطعه</th>
                        <th class="py-2 px-4 border-b text-white w-18"> تعداد</th>
                        <th class="py-2 px-4 border-b text-white  w-18"> قیمت</th>
                        <th class="py-2 px-4 border-b text-white  w-18"> قیمت کل</th>
                        <th class="py-2 px-4 border-b w-12 h-12 font-medium  w-18">
                            <img class="bill_icon" src="./public/img/setting.svg" alt="settings icon">
                        </th>
                    </tr>
                </thead>
                <tbody id="bill_body" class="text-gray-800">
                </tbody>
            </table>
            <img class="cursor-pointer" onclick="addManually()" src="./public/img/add.svg" alt="add icon">
        </div>
    </div>
</div>

<div class="h-16"></div>
<div class="rtl fixed flex justify-between items-center min-w-full h-12 bottom-0 bg-gray-800 px-3">
    <ul class="flex gap-3">
        <?php if (!$isCompleteFactor) : ?>
        <li>
            <p class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="saveIncompleteForm()">
                ذخیره تغییرات پیش فاکتور
            </p>
        </li>
        <li>
            <p class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="generateBill()">
                صدور فاکتور
            </p>
        </li>
        <?php else : ?>
        <li>
            <p class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="saveCompleteForm()">
                ویرایش
            </p>
        </li>
        <li>
            <p class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="generateBill2()">
                پرینت
            </p>
        </li>
        <?php endif; ?>
    </ul>
    <p id="save_message" class="hidden bg-white text-green-400 px-3 py-1">ویرایش موفقانه صورت گرفت</p>
</div>

<div id="popup-modal" tabindex="-1" class="hidden h-screen overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-white/30">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="close-modal" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <img class="mx-auto mb-4 text-gray-400 w-16 h-16 dark:text-gray-200" src="./public/img/warning.svg" alt="warning sign icon">
                <h3 id="message" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">

                </h3>
            </div>
        </div>
    </div>
</div>
<div id="previewBill" style="display:none" class="fixed inset-0 bg-gray-100 justify-center items-center" style="z-index: 10000000000;">
    <div id="bill_body_pdf" class="rtl bill">
        <div class="bill_header">
            <div class="bill_info">
                <table>
                    <tr>
                        <td class="text-sm">شماره فاکتور:</td>
                        <td class="px-1 text-sm"><span id="billNO_bill"></span></td>
                    </tr>
                    <tr>
                        <td class="text-sm"> تاریخ:</td>
                        <td class="px-1 text-sm"><span id="date_bill"></span></td>
                    </tr>
                </table>
            </div>
            <div class="headline">
                <h2 style="margin-bottom: 7px;">فاکتور فروش</h2>
                <h2 style="margin-bottom: 7px;">یدک شاپ</h2>
            </div>
            <div class="log_section">
                <img class="logo" src="./public/img/logo.png" alt="logo of yadakshop">
            </div>
        </div>
        <div class="customer_info">
            <ul>
                <li class="text-sm">
                    نام :
                    <span id="name_bill"></span>
                </li>
                <li class="text-sm">
                    شماره تماس:
                    <span id="phone_bill"></span>
                </li>
            </ul>
            <p style="text-align: center; font-size: 12px;">نشانی: تهران - میدان بهارستان - کوچه نظامیه - بن بست ویژه پلاک ۴</p>
            <img id="copy_icon" class="cursor-pointer" src="./public/img/copy.svg" alt="copy customer info" onclick="copyInfo(this)">
        </div>
        <div class="bill_items">
            <table>
                <thead>
                    <tr style="padding: 10px !important;">
                        <th class="text-right">ردیف</th>
                        <!-- <th class="text-right">کد فنی</th> -->
                        <th class="text-right">نام قطعه</th>
                        <th class="text-center"> تعداد</th>
                        <th class="text-right"> قیمت</th>
                        <th class="text-right"> قیمت کل</th>
                    </tr>
                </thead>
                <tbody id="bill_body_result">
                </tbody>
            </table>
        </div>
        <div class="bill_footer">
            <table>
                <thead>
                    <tr>
                        <th colspan="4">
                            اطلاعات فاکتور
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>تعداد
                            :
                            <input readonly placeholder="تعداد اقلام فاکتور" type="text" name="quantity" id="quantity_bill">
                        </td>
                        <td>تخفیف
                            :
                            <input readonly placeholder="0" type="number" name="discount" id="discount_bill">
                        </td>
                        <td>جمع
                            :
                            <input readonly placeholder="جمع کل اقلام فاکتور" type="text" name="totalPrice" id="totalPrice_bill">
                        </td>
                    </tr>
                    <tr class="bill_info_footer">
                        <td style="padding:5px;">مبلغ قابل پرداخت : </td>
                        <td colspan="5" style="padding:10px;">
                            <p id="total_in_word_bill" class="px-3 text-sm"></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="display: flex; margin-top: 20px;">
            <p style="flex: 1;">امضاء تحویل گیرنده</p>
        </div>
    </div>
</div>
<script>
    const customer_results = document.getElementById('customer_results');
    const resultBox = document.getElementById("selected_box");
    const stock_result = document.getElementById("stock_result");
    const bill_body = document.getElementById("bill_body");


    const modal = document.getElementById("popup-modal");
    const btn_close_modal = document.getElementById("close-modal");
    const error_message = document.getElementById("message");

    btn_close_modal.addEventListener("click", function() {
        modal.classList.remove("flex");
        modal.classList.add("hidden");
    })

    const customerInfo = <?php print_r(json_encode($customerInfo)); ?>;
    displayCustomer(customerInfo);

    function displayCustomer(customer) {
        document.getElementById('id').value = customerInfo.id;
        document.getElementById('mode').value = customerInfo.mode;
        document.getElementById('name').value = customerInfo.name;
        document.getElementById('name').value = customerInfo.name;
        document.getElementById('family').value = customerInfo.family;
        document.getElementById('phone').value = customerInfo.phone;
        document.getElementById('car').value = customerInfo.car;
        document.getElementById('address').value = customerInfo.address;
    }


    const BillInfo = {
        id: "<?= htmlspecialchars($billInfo['id']) ?>",
        billNO: "<?= htmlspecialchars($billInfo['billNO']) ?>",
        date: "<?= htmlspecialchars($billInfo['date']) ?>",
        totalPrice: <?= (float)$billInfo['total'] ?>, // Assuming total is a numeric value
        quantity: <?= (int)$billInfo['quantity'] ?>, // Assuming quantity is an integer
        tax: <?= (float)$billInfo['tax'] ?>, // Assuming tax is a numeric value
        discount: <?= (float)$billInfo['discount'] ?>, // Assuming discount is a numeric value
        withdraw: <?= (float)$billInfo['withdraw'] ?>, // Assuming withdraw is a numeric value
        totalInWords: numberToPersianWords(<?= (float)$billInfo['total'] ?>)
    };

    const billItems = <?php print_r($billItems) ?>;
    displayBill()

    function searchCustomer(pattern) {
        pattern = pattern.trim();
        if (pattern.length > 3) {
            customer_results.innerHTML = `<tr class=''>
                                            <div class='w-full h-52 flex justify-center items-center'>
                                                <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                            </div>
                                         </tr>`;
            var params = new URLSearchParams();
            params.append('customer_search', 'customer_search');
            params.append('pattern', pattern);

            if (pattern.length > 3) {
                axios.post("./app/Controllers/BillController.php", params)
                    .then(function(response) {
                        let template = '';
                        if (response.data.length > 0) {
                            for (const customer of response.data) {
                                template += `
                                    <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border border-gray-300">
                                        <p class="text-md font-semibold text-gray-600">
                                            ` + customer.name + `
                                            ` + customer.family + `
                                        </p>
                                        <p class="text-md font-semibold text-gray-600">
                                            ` + customer.phone + `
                                        </p>
                                            <i  data-id="` + customer.id + `" 
                                                data-name="` + customer.name + `" 
                                                data-family="` + customer.family + `" 
                                                data-phone="` + customer.phone + `"
                                                data-address="` + customer.address + `"
                                                data-car="` + customer.car + `"
                                                onclick="selectCustomer(this)"
                                                    class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                                            </i>
                                        </div>
                                    `;
                            }
                        } else {
                            template += `
                                    <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border border-gray-300">
                                        <p class="text-md font-semibold text-gray-600">
                                           مشتری ای با مشخصات وارده در سیستم موجود نیست
                                        </p>
                                        </div>
                                    `;
                        }
                        customer_results.innerHTML = template;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            } else {
                customer_results.innerHTML = "کد فنی وارد شده فاقد اعتبار است";
            }
        } else {
            customer_results.innerHTML = "";
        }
    };

    function selectCustomer(customer) {
        customerInfo.id = customer.getAttribute('data-id');
        customerInfo.mode = 'update';
        customerInfo.name = customer.getAttribute('data-name').trim();
        customerInfo.family = customer.getAttribute('data-family').trim();
        customerInfo.phone = customer.getAttribute('data-phone');
        customerInfo.car = customer.getAttribute('data-car');
        customerInfo.address = customer.getAttribute('data-address');

        document.getElementById('id').value = customerInfo.id;
        document.getElementById('mode').value = customerInfo.mode;
        document.getElementById('name').value = customerInfo.name;
        document.getElementById('family').value = customerInfo.family;
        document.getElementById('phone').value = customerInfo.phone;
        document.getElementById('phone').setAttribute('readOnly', true);
        document.getElementById('car').value = customerInfo.car;
        document.getElementById('address').value = customerInfo.address;
        document.getElementById('customer_name').value = '';
        customer_results.innerHTML = "";
        displayBill();
    }

    function searchPartNumber(pattern) {

        if (pattern.length > 6) {
            pattern = pattern.replace(/\s/g, "");
            pattern = pattern.replace(/-/g, "");
            pattern = pattern.replace(/_/g, "");

            resultBox.innerHTML = `<tr class=''>
                                            <div class='w-full h-52 flex justify-center items-center'>
                                                <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                            </div>
                                        </tr>`;
            var params = new URLSearchParams();
            params.append('partNumber', pattern);

            axios.post("./app/Controllers/BillController.php", params)
                .then(function(response) {
                    const data = response.data;
                    if (response.data.length > 0) {

                        resultBox.innerHTML = createPartNumberTemplate(data);
                    } else {
                        resultBox.innerHTML = `<div class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                            <div class="w-full py-3 flex justify-between items-center">      
                                <p class="text-md font-semibold text-white">
                                      کد مد نظر شما موجود نیست.
                                </p>
                            </div>
                        </div>`;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
            resultBox.innerHTML = "";
        }
    }

    function searchInStock(pattern) {

        if (pattern.length > 6) {
            pattern = pattern.replace(/\s/g, "");
            pattern = pattern.replace(/-/g, "");
            pattern = pattern.replace(/_/g, "");

            stock_result.innerHTML = `<tr class=''>
                                            <div class='w-full h-52 flex justify-center items-center'>
                                                <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                            </div>
                                        </tr>`;
            var params = new URLSearchParams();
            params.append('searchInStock', pattern);

            axios.post("./app/Controllers/BillController.php", params)
                .then(function(response) {
                    const data = response.data;
                    if (response.data.length > 0) {

                        stock_result.innerHTML = createStockTemplate(data);
                    } else {
                        stock_result.innerHTML = `<div class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                                                    <div class="w-full py-3 flex justify-between items-center">      
                                                        <p class="text-md font-semibold text-white">
                                                            کد مد نظر شما موجود نیست.
                                                        </p>
                                                    </div>
                                                </div>`;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
            stock_result.innerHTML = "";
        }
    }

    function createPartNumberTemplate(data) {
        let template = ``;
        for (const item of data) {
            template += `
                        <div id="box-${item.id}" class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                            <div class="w-full py-3 flex justify-between items-center">      
                                <p class="text-md font-semibold text-white">
                                       ${item.partnumber}
                                </p>
                                <p class="text-md text-white">اسم قطعه بعدا اضافه می شود</p>
                            </div>
                            <div class="w-full flex justify-between items-center">
                                    <input type="number" onkeyup="updateCredential('data-price',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="قیمت" />
                                    <input type="number" onkeyup="updateCredential('data-quantity',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="تعداد" />
                                <i id="${item.id}"
                                    data-quantity= "0"
                                    data-price= "0"
                                    data-partNumber = "${item.partnumber}"
                                    data-name = "بعدا اضافه می شود"
                                    onclick="selectGood(this)"
                                        class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                                </i>
                            </div>
                            <div class="w-full h-6 flex justify-between items-center">
                                <p id="error-${item.id}" class="d-none text-md text-red-600 pt-3">
                                انتخاب قیمت بیشتر از موجودی امکان پذیر نمی باشد
                                </p>
                            </div>
                        </div>
                        `;
        }

        return template;
    }

    function createStockTemplate(data) {
        let template = ``;
        for (const item of data) {
            template += `
                        <div id="box-${item.id}" class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                            <div class="w-full py-3 flex justify-between items-center">      
                                <p class="text-md font-semibold text-white">
                                    ${item.partnumber}
                                </p>
                                <p class="text-md font-semibold text-white">
                                برند : 
                                    ${item.brand_name}
                                </p>
                                <p class="text-md font-semibold text-white">
                                موجودی :‌  
                                    ${item.existing}
                                </p>
                                <p class="text-md text-white">اسم قطعه بعدا اضافه می شود</p>
                            </div>
                            <div class="w-full flex justify-between items-center">
                                    <input type="number" onkeyup="updateCredential('data-price',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="قیمت" />
                                    <input type="number" onkeyup="checkExisting(this, ${item.existing},${item.id});updateCredential('data-quantity',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="تعداد" />
                                <i id="${item.id}"
                                    data-quantity= "0"
                                    data-price= "0"
                                    data-partNumber = "${item.partnumber}"
                                    data-name = "بعدا اضافه می شود"
                                    data-max = "${item.existing}"
                                    onclick="selectGood(this)"
                                        class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                                </i>
                            </div>
                            <div class="w-full h-6 flex justify-between items-center">
                            <p id="error-${item.id}" class="d-none text-md text-red-600 pt-3">
                            انتخاب قیمت بیشتر از موجودی امکان پذیر نمی باشد
                            </p>
                            </div>
                        </div>
                        `;
        }

        return template;
    }

    function checkExisting(element, max, specidier) {
        if (element.value > max) {
            element.value = max;
            document.getElementById("error-" + specidier).classList.toggle("d-none");
            document.getElementById("error-" + specidier).innerHTML = "انتخاب مقدار بیشتر از موجودی امکان پذیر نیست.";

            setTimeout(() => {
                document.getElementById("error-" + specidier).classList.toggle("d-none");
            }, 2000);
        }
    }

    function updateCredential(property, specifier, value) {
        document.getElementById(specifier).setAttribute(property, value);
    }

    function selectGood(element) {
        const id = element.getAttribute('id');
        const name = element.getAttribute('data-name');
        const price = element.getAttribute('data-price');
        const quantity = element.getAttribute('data-quantity');
        const partNumber = element.getAttribute('data-partNumber');
        const max = element.getAttribute('data-max') ?? 'undefined';

        if (price <= 0 || quantity <= 0) {
            document.getElementById("error-" + id).classList.toggle("d-none");
            document.getElementById("error-" + id).innerHTML = "لطفا مقادیر وارده را درست بررسی نمایید";

            setTimeout(() => {
                document.getElementById("error-" + id).classList.toggle("d-none");
            }, 2000);

            return false;
        }

        billItems.push({
            id,
            partName: name,
            price_per: price,
            quantity,
            max,
            partNumber
        });
        document.getElementById("box-" + id).style.display = "none";
        displayBill();
    }

    function addManually() {
        billItems.push({
            id: Math.floor(Math.random() * (9000000 - 1000000 + 1)) + 1000000,
            partName: "اسم قطعه را وارد کنید.",
            price_per: 0,
            quantity: 1,
            max: 'undefined',
            partNumber: 'NOTPART'
        });
        displayBill();
    }

    function updateBillInfo(element) {

        const proprty = element.getAttribute("name");
        BillInfo[proprty] = element.value;
    }

    function updateCustomerInfo(element) {
        const proprty = element.getAttribute("name");
        customerInfo[proprty] = element.value;
    }

    function displayBill() {
        let counter = 1;
        let template = ``;
        let totalPrice = 0;
        BillInfo.quantity = 0;

        for (const item of billItems) {
            const payPrice = Number(item.quantity) * Number(item.price_per);
            totalPrice += payPrice;
            BillInfo.quantity += Number(item.quantity);
            template += `
            <tr id="${item.id}" class="even:bg-gray-100 border-gray-800" >
                <td class="py-3 px-4 w-10">
                    <span>${counter}</span>
                </td>
                <td class="relative py-3 px-4 w-2/4" onclick="editCell(this, 'partName', '${item.id}', '${item.partName}')">
                    <span class="cursor-pointer text-lg" title="برای ویرایش دوبار کلیک نمایید">${item.partName}</span>
                    <input type="text" class="w-2/4 p-2 border text-gray-500 hidden w-42" value="${item.partName}" />
                    <div class="absolute left-0 top-2 flex flex-wrap gap-1 w-42">
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','اصلی')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">اصلی</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','چین')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">چین</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','کره')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">کره</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','متفرقه')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">متفرقه</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','تایوان')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">تایوان</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','DYC')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">DYC</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','KGC')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">KGC</span>`;
            if (customerInfo.car != '') {
                template += `<span style="font-size:13px" onclick="appendCarSufix('${item.id}','${customerInfo.car}')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">${customerInfo.car}</span>`;
            }
            template += `</div>
                </td>
                <td class="text-center w-18 py-3 px-4" onclick="editCell(this, 'quantity', '${item.id}', '${item.quantity}')">
                    <span class="cursor-pointer text-lg text-center" title="برای ویرایش دوبار کلیک نمایید">${item.quantity}</span>
                    <input type="number" style="direction:ltr !important;" class="p-2 border border-1 hidden w-16" onkeyup="convertToEnglish(this)" value="${item.quantity}" />
                </td>
                <td class="text-center py-3 px-4 w-18" onclick="editCell(this, 'price_per', '${item.id}', '${item.price_per}')">
                    <span class="cursor-pointer text-lg text-center" title="برای ویرایش دوبار کلیک نمایید">${formatAsMoney(Number(item.price_per))}</span>
                    <input type="text" style="direction:ltr !important;" class=" w-18 p-2 border hidden" onkeyup="format(this);convertToEnglish(this)" value="${Number(item.price_per)}" />
                </td>
                <td class="text-center py-3 px-4">${formatAsMoney(payPrice)}</td>
                <td class="text-center py-3 px-4 w-18 h-12 font-medium">
                    <img onclick="deleteItem(${item.id})" class="bill_icon" src="./public/img/subtract.svg" alt="subtract icon">
                </td>
            </tr> `;
            counter++;
        }
        bill_body.innerHTML = template;
        BillInfo.totalPrice = (totalPrice);
        BillInfo.totalInWords = numberToPersianWords(totalPrice);

        document.getElementById('billNO').value = BillInfo.billNO;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('totalPrice').value = formatAsMoney(BillInfo.totalPrice);
        document.getElementById('total_in_word').innerHTML = BillInfo.totalInWords;
    }

    function format(inputElement) {
        // Remove non-digit characters
        let inputValue = inputElement.value.replace(/[^\d]/g, '');

        // Add comma as a 3-digit separator
        inputValue = inputValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Update the input value
        inputElement.value = inputValue;
    }

    function editCell(cell, property, itemId, originalValue) {
        const input = cell.querySelector('input');
        const span = cell.querySelector('span');

        // Make input visible and set focus
        input.classList.remove('hidden');
        input.focus();
        input.select();

        // Hide the span
        span.classList.add('hidden');

        // Update input value with the original value
        input.value = originalValue;

        // Handle changes when the input loses focus
        input.addEventListener('blur', function() {
            const newValue = input.value;

            // Update the span with the new value
            span.innerText = newValue;

            // Make the span visible again
            span.classList.remove('hidden');

            // Hide the input
            input.classList.add('hidden');

            // Update the corresponding item in your data structure (billItems)
            updateItemProperty(itemId, property, newValue);
        });
    }

    function updateItemProperty(itemId, property, newValue) {
        newValue = newValue.replace(/,/g, '');
        for (let i = 0; i < billItems.length; i++) {
            if (billItems[i].id == itemId) {
                if (property !== 'quantity') {
                    billItems[i][property] = newValue;
                    break;
                } else {
                    if (billItems[i]['max'] === 'undefined') {
                        billItems[i][property] = newValue;
                        break;
                    } else {
                        if (billItems[i]['max'] >= newValue) {
                            billItems[i][property] = newValue;
                            break;
                        } else {
                            modal.classList.remove("hidden");
                            modal.classList.add("flex");
                            message.innerHTML = " مقدار انتخاب شده بیشتر از مقداری موجودی در انبار بوده نمیتواند.";

                            setTimeout(() => {
                                modal.classList.remove("flex");
                                modal.classList.add("hidden");
                            }, 2000);
                            break;
                        }
                    }
                }
            }
        }
        displayBill();
    }

    function appendSufix(itemId, suffix) {
        for (let i = 0; i < billItems.length; i++) {
            if (billItems[i].id == itemId) {

                const partName = billItems[i].partName;
                let lastIndex = partName.lastIndexOf('-');

                let result = lastIndex !== -1 ? partName.substring(0, lastIndex) : partName;
                billItems[i].partName = result + ' - ' + suffix;
            }
        }
        displayBill();
    }

    function appendCarSufix(itemId, suffix) {
        for (let i = 0; i < billItems.length; i++) {
            if (billItems[i].id == itemId) {

                const partName = billItems[i].partName;
                let lastIndex = partName;
                billItems[i].partName = partName + ' ' + suffix;
            }
        }
        displayBill();
    }

    function deleteItem(id) {
        for (let i = 0; i < billItems.length; i++) {
            if (billItems[i].id == id) {
                billItems.splice(i, 1);
                break;
            }
        }
        displayBill();
    }

    function formatAsMoney(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ریال';
    }

    function numberToPersianWords(number) {
        const units = [
            '', // ones
            'هزار', // thousands
            'میلیون', // millions
            'میلیارد', // billions
            'تریلیارد', // trillions
            'پادا', // quadrillions
            'هکتا', // quintillions
            'اکتا', // sextillions
            'نونا', // septillions
            'دسیلیارد', // decillions
        ];
        const numberStr = String(number).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        const chunks = numberStr.split(',');

        let words = [];
        const size = chunks.length;
        for (let index in chunks) {

            let word = converter(removeLeadingZeros(chunks[index]));
            if (word.length > 0) {
                word += " " + units[size - (Number(index) + 1)];
                words.push(word);
            }
        }

        return words.join(' و ') + ' ریال';
    }

    function removeLeadingZeros(numberString) {
        // Use regular expression to match and remove leading zeros
        const cleanedNumber = numberString.replace(/^0+/, '');

        return cleanedNumber;
    }

    function converter(number) {
        const ones = ['صفر', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
        const teens = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
        const tens = ["", "", 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];

        if (Number(number > 99)) {
            const hole = Math.trunc(number / 100);
            const remainder = number % 100;
            let delimiters = '';

            if (remainder > 0) {
                delimiters = ' و ';
            }

            return ones[hole] + ' صد' + delimiters + converter(remainder);

        } else if (Number(number) > 19) {
            const hole = Math.trunc(number / 10);
            const remainder = number % 10;

            let delimiters = '';

            if (remainder > 0) {
                delimiters = ' و ';
            }

            return tens[hole] + delimiters + converter(remainder);
        } else if (Number(number) > 9) {
            const hole = Math.trunc(number / 10);
            const remainder = number % 10;
            return teens[remainder] + ' ';

        } else if (Number(number) > 0) {
            return ones[number];
        } else {
            return '';
        }
    }

    function getBillNumber() {

        var params = new URLSearchParams();
        params.append('getFactorNumber', 'getFactorNumber');
        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                bill_number = (response.data);
                BillInfo.billNO = bill_number;
                document.getElementById('billNO').value = BillInfo.billNO;
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function generateBill() {
        if (BillInfo.date == 'null') {
            BillInfo.date = moment().locale('fa').format('YYYY/M/D');
        }

        if (customerInfo.name === null || BillInfo.billNO === null || billItems.length == 0) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
            message.innerHTML = "لطفا برای  ثبت فاکتور , مشتری مد نظر , شماره فاکتور و اقلام مندرج در فاکتور را مشخص نمایید";
            return false
        }
        // Convert the object to a JSON string and store it in local storage
        localStorage.setItem('customer_info', JSON.stringify(customerInfo));
        localStorage.setItem('bill_info', JSON.stringify(BillInfo));
        localStorage.setItem('bill_items', JSON.stringify(billItems));
        localStorage.setItem('operation', 'save');

        window.location.href = './displayBill.php';
    }

    function generateBill2() {
        if (BillInfo.date == 'null') {
            BillInfo.date = moment().locale('fa').format('YYYY/M/D');
        }

        if (customerInfo.name === null || BillInfo.billNO === null || billItems.length == 0) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
            message.innerHTML = "لطفا برای  ثبت فاکتور , مشتری مد نظر , شماره فاکتور و اقلام مندرج در فاکتور را مشخص نمایید";
            return false
        }
        // Convert the object to a JSON string and store it in local storage
        localStorage.setItem('customer_info', JSON.stringify(customerInfo));
        localStorage.setItem('bill_info', JSON.stringify(BillInfo));
        localStorage.setItem('bill_items', JSON.stringify(billItems));
        localStorage.setItem('operation', 'print');

        window.location.href = './displayBill.php';
    }

    function saveIncompleteForm() {
        if (BillInfo.date == 'null')
            BillInfo.date = moment().locale('fa').format('YYYY/M/D');
        var params = new URLSearchParams();
        params.append('saveIncompleteForm', 'saveIncompleteForm');
        params.append('customer_info', JSON.stringify(customerInfo));
        params.append('bill_info', JSON.stringify(BillInfo));
        params.append('bill_items', JSON.stringify(billItems));



        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                const data = response.data;
                const save_message = document.getElementById('save_message');
                save_message.classList.remove('hidden');

                setTimeout(() => {
                    save_message.classList.add('hidden');
                }, 3000);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function saveCompleteForm() {
        if (BillInfo.date == 'null')
            BillInfo.date = moment().locale('fa').format('YYYY/M/D');
        var params = new URLSearchParams();
        params.append('saveCompleteForm', 'saveCompleteForm');
        params.append('customer_info', JSON.stringify(customerInfo));
        params.append('bill_info', JSON.stringify(BillInfo));
        params.append('bill_items', JSON.stringify(billItems));



        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                const data = response.data;
                const save_message = document.getElementById('save_message');
                save_message.classList.remove('hidden');

                setTimeout(() => {
                    save_message.classList.add('hidden');
                }, 3000);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function sanitizeInput(inputElement) {
        // Get the input value
        let inputValue = inputElement.value;
        // Check if inputValue is defined and not null
        if (inputValue && inputValue.indexOf('+98') === 0) {
            // If it does, replace '+98' with '0'
            inputValue = '0' + inputValue.slice(3);
            // Update the input value
            inputElement.value = inputValue;
        }
    }

    function handleKeyDown(event) {
        if (event.ctrlKey && event.shiftKey) {
            addManually();
        }

        if (event.keyCode === 120) {
            // Toggle the preview bill and use a callback to get data after the toggle
            togglePreviewBill(function() {
                getBillData();
            });
        }
    }

    function togglePreviewBill(callback) {
        var previewBill = document.getElementById('previewBill');
        previewBill.style.display = (previewBill.style.display === 'none') ? 'flex' : 'none';

        // Call the callback after the toggle animation is complete
        setTimeout(callback, 0);
    }

    document.addEventListener('keydown', handleKeyDown);


    <?php if (!$billInfo['billNO']) {

        echo 'getBillNumber()';
    }
    ?>

</script>
<script src="./public/js/displayBill.js?v=<?= rand() ?>"></script>
<?php
require_once('./views/Layouts/footer.php');