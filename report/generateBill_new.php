<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once './LoadBillDetails.php';
require_once('./views/Layouts/header.php');
?>

<!-- Utility files and styles  -->
<script src="./public/js/persianDate.js"></script>
<link rel="stylesheet" href="./public/css/bill.css?v=<?= rand() ?>" />
<style>
    .tab-op {
        background-color: transparent !important;
        border: none !important;
        width: 100% !important;
    }

    .tab-op-number {
        text-align: center !important;
    }

    .tab-op:focus {
        outline: 2px solid lightgray !important;
    }

    .hidden-action {
        display: none;
        background-color: white;
    }

    .add-column:hover .hidden-action {
        display: flex !important;
    }
</style>

<main>
    <!-- search and initialing data section -->
    <div style="height: 350px !important;" class="rtl h-1/3 grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6  px-2 mb-3">
        <!-- Search for customer section -->
        <section class="bg-white min-h-full rounded-lg shadow-md">
            <div class="flex items-center justify-between p-3">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <img src="./public/img/customer.svg" alt="customer icon">
                    انتخاب مشتری
                </h2>
            </div>
            <div class="relative flex justify-center px-3">
                <input onkeyup="convertToPersian(this); searchCustomer(this.value)" type="text" name="customer" class="rounded-md py-3 px-3 w-full border text-md border-gray-300 focus:outline-none text-gray-500" id="customer_name" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
                <img class="absolute left-5 top-3 cursor-pointer" onclick="(() => {searchCustomer('');document.getElementById('customer_name').value = '';})();" src="./public/img/clear.svg" alt="customer icon">
            </div>
            <div class="hidden sm:block">
                <div class="py-2">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>
            <div id="customer_results" style="overflow-y: auto; height:300px" class="p-3 overflow-y-auto">
                <!-- Search Results are going to be appended here -->
            </div>
        </section>

        <!-- search for goods base on the part number section -->
        <section class="bg-white min-h-full rounded-lg shadow-md">
            <div class="flex items-center justify-between p-3">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <img src="./public/img/barcode.svg" alt="customer icon">
                    انتخاب کد فنی
                </h2>
            </div>
            <div class="relative flex justify-center px-3">
                <input onkeyup="convertToEnglish(this); searchPartNumber(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border text-md border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder="کد فنی قطعه مورد نظر را وارد کنید..." />
                <img class="absolute left-5 top-3 cursor-pointer" onclick="(() => {searchPartNumber('');document.getElementById('serial').value = '';})();" src="./public/img/clear.svg" alt="customer icon">
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
        </section>

        <!-- Search in the stock base on existing using part number section -->
        <section class="bg-white min-h-full rounded-lg shadow-md">
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
        </section>
    </div>

    <!-- Bill editing and information section -->
    <section class="rtl grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6 px-2 mb-4">
        <!-- bill and customer information table -->
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
                        <td class="py-2 px-3 text-white bg-gray-800 text-md">تلفون</td>
                        <td class="py-2 px-4">
                            <input onblur="ifCustomerExist(this)" onkeyup="sanitizeCustomerPhone(this);updateCustomerInfo(this)" class="w-full p-2 border text-gray-500 ltr" placeholder="093000000000" type="text" name="phone" id="phone">
                        </td>
                    </tr>
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

        <!-- bill body table -->
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
                <img class="cursor-pointer" onclick="addNewBillItemManually()" src="./public/img/add.svg" alt="add icon">
            </div>
        </div>
    </section>

    <div class="h-16"></div> <!-- adding a little white space at the bottom to be more user friendly -->
    <div class="rtl fixed flex justify-between items-center min-w-full h-12 bottom-0 bg-gray-800 px-3">
        <ul class="flex gap-3">
            <?php if (!$isCompleteFactor) : ?>
                <li>
                    <button id="incomplete_save_button" class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="saveIncompleteForm()">
                        ذخیره تغییرات پیش فاکتور
                    </button>
                </li>
                <li>
                    <button id="complete_save_button" class="diable bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="generateBill()">
                        صدور فاکتور
                    </button>
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

    <!-- a modal to alert user from leak of required information before saving or marking bill as complete -->
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


    <!-- A modal to preview the bill to show the user it's requested items -->
    <div id="previewBill" style="display:none; overflow:scroll" class="fixed inset-0 bg-gray-100 justify-center" style="z-index: 10000000000;">
        <div id="bill_body_pdf" class="rtl bill bg-white " style="margin-top: 100px;">
            <div class="bill_header">
                <div class="bill_info">
                    <div class="nisha-bill-info">
                        <div class="A-main">
                            <div class="A-1">شماره</div>
                            <div class="A-2"><span id="billNO_bill">5555</span></div>

                        </div>
                        <div class="B-main">
                            <div class="B-1">تاریخ</div>
                            <div class="B-2"><span id="date_bill">1402-10-30</span></div>

                        </div>
                    </div>
                </div>
                <div class="headline">
                    <h2 style="margin-bottom: 7px;"> پیش فاکتور یدک شاپ</h2>
                    <h2 style="margin-bottom: 7px;">لوازم یدکی هیوندای و کیا</h2>
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
                <p class="w-1/2" id="userAddress" style="font-size: 12px;"></p>
                <img id="copy_icon" class="cursor-pointer" src="./public/img/copy.svg" alt="copy customer info" onclick="copyInfo(this)">
            </div>
            <div class="bill_items">
                <table>
                    <thead>
                        <tr style="padding: 10px !important;">
                            <th class="text-right w-12">ردیف</th>
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
                <div class="tahvilgirande-box">
                    <div class="tahvilgirande-box-header">مشخصات تحویل گیرنده</div>
                    <div class="tahvilgirande-box-inner">
                        <div>نام</div>
                        <div>شماره تماس</div>
                        <div>امضا</div>
                    </div>
                </div>
            </div>

            <div class="footer-box">
                <p class="footer-box-adress">
                    تهران ، میدان بهارستان ، خیابان مصطفی خمینی ، خیابان نظامیه ، بن بست ویژه ، پلاک ۴
                </p>
                <p class="footer-box-tell">
                    <span>
                        ۷۰ ۹۳ ۹۷ ۳۳ - ۰۲۱
                    </span>
                    <span>
                        ۸۸ ۶۷ ۹۴ ۳۳ - ۰۲۱
                    </span>
                    <span>
                        ۸۰۹ ۱۹ ۳۶۶ - ۰۲۱
                    </span>
                    <span>
                        ۴۳۲ ۱۹ ۳۶۶ - ۰۲۱
                    </span>
                </p>
            </div>
        </div>
    </div>
</main>
<script src="./public/js/displayBill.js?v=<?= rand() ?>"></script>
<script>
    // Accessing the conatainers to have global access for easy binding data
    const customer_results = document.getElementById('customer_results');
    const resultBox = document.getElementById("selected_box");
    const stock_result = document.getElementById("stock_result");
    const bill_body = document.getElementById("bill_body");


    // Modal to alert user the leak of data 
    const modal = document.getElementById("popup-modal");
    const btn_close_modal = document.getElementById("close-modal");
    const error_message = document.getElementById("message");
    btn_close_modal.addEventListener("click", function() {
        modal.classList.remove("flex");
        modal.classList.add("hidden");
    })

    // Assign the customer info received from the server to the JS Object to work with and display after ward
    const customerInfo = <?= json_encode($customerInfo); ?>;
    const BillInfo = <?= json_encode($billInfo); ?>;
    BillInfo.totalInWords = numberToPersianWords(<?= (float)$billInfo['total'] ?>)
    const billItems = <?= $billItems ?>;

    function bootstrap() {
        displayCustomer(customerInfo);
        displayBill();
    }

    // A functionn to display Bill customer information in the table
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

    // A function to display bill items and calculate the amount and goods count and display bill details afterword
    function displayBill() {
        let counter = 1;
        let activeIndex = 0;
        let template = ``;
        let totalPrice = 0;
        BillInfo.quantity = 0;

        for (const item of billItems) {
            const payPrice = Number(item.quantity) * Number(item.price_per);
            totalPrice += payPrice;
            BillInfo.quantity += Number(item.quantity);
            template += `
            <tr id="${item.id}" class="even:bg-gray-100 border-gray-800 add-column" >
                <td class="py-3 px-4 w-10 relative text-left">
                    <span>${counter}</span>
                    <div class="absolute inset-0 flex flex-col items-start justify-center hidden-action">
                        <img title="افزودن ردیف" class="cursor-pointer w-6" src="./public/img/top_arrow.svg" />
                        <img title="افزودن ردیف" class="cursor-pointer w-6" src="./public/img/bottom_arrow.svg" />
                    </div>
                </td>
                <td class="relative py-3 px-4 w-2/4" >
                    <input type="text" class="tab-op w-2/4 p-2 border text-gray-500 w-42" onchange="editCell(this, 'partName', '${item.id}', '${item.partName}')" value="${item.partName}" />
                    <div class="absolute left-0 top-2 flex flex-wrap gap-1 w-42">
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','اصلی')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">اصلی</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','چین')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">چین</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','کره')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">کره</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','متفرقه')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">متفرقه</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','تایوان')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">تایوان</span>
                        <span style="font-size:13px" onclick="appendSufix('${item.id}','شرکتی')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">شرکتی</span>`;
            if (customerInfo.car != '' && customerInfo.car != null) {
                template += `<span style="font-size:13px" onclick="appendCarSufix('${item.id}','${customerInfo.car}')" class="cursor-pointer text-md text-white bg-gray-600 rounded p-1" title="">${customerInfo.car}</span>`;
            }
            template += `</div>
                </td>
                <td class="text-center w-18 py-3 px-4">
                    <input  onchange="editCell(this, 'quantity', '${item.id}', '${item.quantity}')" type="number" style="direction:ltr !important;" class="tab-op tab-op-number  p-2 border border-1 w-16" value="${item.quantity}" />
                </td>
                <td class="text-center py-3 px-4 w-18" >
                    <input onchange="editCell(this, 'price_per', '${item.id}', '${item.price_per}')" type="text" style="direction:ltr !important;" class="tab-op tab-op-number w-18 p-2 border " onkeyup="displayAsMoney(this);convertToEnglish(this)" value="${formatAsMoney(item.price_per)}" />
                </td>
                <td class="text-center py-3 px-4 ltr">${formatAsMoney(payPrice)}</td>
                <td class="text-center py-3 px-4 w-18 h-12 font-medium">
                    <img onclick="deleteItem(${item.id})" class="bill_icon" src="./public/img/subtract.svg" alt="subtract icon">
                </td>
            </tr> `;
            counter++;
        }

        bill_body.innerHTML = template;
        BillInfo.totalPrice = (totalPrice);
        BillInfo.totalInWords = numberToPersianWords(totalPrice);
        // Display the Bill Information
        document.getElementById('billNO').value = BillInfo.billNO;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('totalPrice').value = formatAsMoney(BillInfo.totalPrice);
        document.getElementById('total_in_word').innerHTML = BillInfo.totalInWords;
    }

    // A function to display bill items and calculate the amount and goods count and display bill details afterword
    function updatedisplayBill() {
        let counter = 1;
        let activeIndex = 0;
        let totalPrice = 0;
        BillInfo.quantity = 0;

        for (const item of billItems) {
            const payPrice = Number(item.quantity) * Number(item.price_per);
            totalPrice += payPrice;
            BillInfo.quantity += Number(item.quantity);
        }

        BillInfo.totalPrice = (totalPrice);
        BillInfo.totalInWords = numberToPersianWords(totalPrice);
        // Display the Bill Information
        document.getElementById('billNO').value = BillInfo.billNO;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('totalPrice').value = formatAsMoney(BillInfo.totalPrice);
        document.getElementById('total_in_word').innerHTML = BillInfo.totalInWords;
    }

    // Add new bill item manually using the icon on the browser or shift + ctrl key press
    function addNewBillItemManually() {
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

    // Updating the bill inforation section (EX: setting the discount or tax)
    function updateBillInfo(element) {
        const proprty = element.getAttribute("name");
        BillInfo[proprty] = element.value;
    }

    // updating the customer information by modifying the customer information table section 
    function updateCustomerInfo(element) {
        const proprty = element.getAttribute("name");
        customerInfo[proprty] = element.value;
        displayBill();
    }

    // Edit the item property by clicking on it and giving a new value
    function editCell(cell, property, itemId, originalValue) {
        const newValue = cell.value;

        // Update the corresponding item in your data structure (billItems)
        updateItemProperty(itemId, property, newValue);

        if (property == 'quantity' || property == 'price_per') {
            const parentRow = cell.closest('tr');
            const secondToLastTd = parentRow.querySelector('td:nth-last-child(2)');

            const totalpriceParent = parentRow.querySelector('td:nth-last-child(3)');
            const totalpriceValue = Number(totalpriceParent.querySelector('input').value.replace(/\D/g, ""));

            const thirdToLastTd = parentRow.querySelector('td:nth-last-child(4)');
            const value = (thirdToLastTd.querySelector('input').value);
            // Find the second-to-last td element in the same row


            // Modify the innerHTML of the second-to-last td element
            if (secondToLastTd) {
                secondToLastTd.innerHTML = formatAsMoney(Number(totalpriceValue) * value); // Replace 'New Value' with the desired content
            }
        }

    }

    // Update the edited item property in the data source
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
        updatedisplayBill();
    }

    // Adding item suffix to it
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

    // Append the customer car brand to the items
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

    // deleiting the specific bill item
    function deleteItem(id) {
        for (let i = 0; i < billItems.length; i++) {
            if (billItems[i].id == id) {
                billItems.splice(i, 1);
                break;
            }
        }
        displayBill();
    }

    // display the bill total amount alphabiticly ------------- START
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
    // display the bill total amount alphabiticly -------------- END 

    // Mark bill as completed and send it for the print
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

    // Update the 
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

    // Rmove the white space and leading +98 from user phone number
    function sanitizeCustomerPhone(inputElement) {
        // Get the input value and remove white spaces
        let inputValue = inputElement.value.replace(/\s/g, '');


        // Check if inputValue is defined and not null
        if (inputValue && inputValue.indexOf('+98') === 0) {
            // If it does, replace '+98' with '0'
            inputValue = '0' + inputValue.slice(3);
            // Update the input value
            inputValue = toEnglish(inputValue);
            inputElement.value = inputValue;
        } else {
            inputValue = toEnglish(inputValue);
            inputElement.value = inputValue;
        }
    }

    function ifCustomerExist(element) {

        if (element.value.length > 0) {
            var params = new URLSearchParams();
            params.append('isPhoneExist', 'isPhoneExist');
            params.append('phone', element.value);

            axios.post("./app/Controllers/BillController.php", params)
                .then(function(response) {
                    const customer = response.data;
                    if (customer !== 0) {
                        document.getElementById('name').value = customer.name;
                        document.getElementById('family').value = customer.family;
                        document.getElementById('address').value = customer.address;
                        document.getElementById('car').value = customer.car;
                        customerInfo['id'] = customer.id;
                        customerInfo['name'] = customer.name;
                        customerInfo['family'] = customer.family;
                        customerInfo['address'] = customer.address;
                        customerInfo['car'] = customer.car;
                        customerInfo.mode = "update";
                    } else {
                        document.getElementById('name').value = '';
                        document.getElementById('family').value = '';
                        document.getElementById('address').value = '';
                        document.getElementById('car').value = '';
                        customerInfo['id'] = null;
                        customerInfo['name'] = null;
                        customerInfo['family'] = null;
                        customerInfo['address'] = null;
                        customerInfo['car'] = null;
                        customerInfo.mode = "create";
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
            element.classList.remove('border-2');
            element.classList.remove('border-red-500');
            document.getElementById('complete_save_button').disabled = false;
            document.getElementById('incomplete_save_button').disabled = false;

            document.getElementById('complete_save_button').classList.remove('opacity-50');
            document.getElementById('complete_save_button').classList.remove('cursor-not-allowed');
            document.getElementById('incomplete_save_button').classList.remove('opacity-50');
            document.getElementById('incomplete_save_button').classList.remove('cursor-not-allowed');
            save_message.classList.add('hidden');
            save_message.innerHTML = 'تغییرات موفقانه ذخیره شد';
            save_message.style.color = 'seagreen';
        }

    }

    function toEnglish(value) {
        const englishCharMap = {
            'ش': 'a',
            'ذ': 'b',
            'ز': 'c',
            'ی': 'd',
            'ث': 'e',
            'ب': 'f',
            'ل': 'g',
            'ا': 'h',
            'ه': 'i',
            'ت': 'j',
            'ن': 'k',
            'م': 'l',
            'پ': 'm',
            'د': 'n',
            'خ': 'o',
            'ح': 'p',
            'ض': 'q',
            'ق': 'r',
            'س': 's',
            'ف': 't',
            'ع': 'u',
            'ر': 'v',
            'ص': 'w',
            'ط': 'x',
            'غ': 'y',
            'ظ': 'z',
            'و': ':',
            'گ': "'",
            'ک': ";",
            'چ': "]",
            '۱': '1',
            '۲': '2',
            '۳': '3',
            '۴': '4',
            '۵': '5',
            '۶': '6',
            '۷': '7',
            '۸': '8',
            '۹': '9',
            '۰': '0'
        };

        const customInput = value;
        let customText = '';
        const inputText = value;
        for (let i = 0; i < inputText.length; i++) {
            const char = inputText[i];
            if (char in englishCharMap) {
                customText += englishCharMap[char];
            } else {
                customText += char;
            }
        }
        return customText;
    }

    function handelShortcuts(event) {
        if (event.ctrlKey && event.shiftKey) {
            addNewBillItemManually();
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

    bootstrap(); // Display the form data after retrieving every initial data

    document.addEventListener('keydown', handelShortcuts);
    document.addEventListener("keydown", function(event) {
        // Check if the Ctrl key is pressed and the key is 'S'
        if (event.ctrlKey && event.key === 's') {
            // Prevent the default browser behavior for Ctrl + S (e.g., saving the page)
            event.preventDefault();

            // Call the saveIncompleteForm function
            saveIncompleteForm();

            // Optionally, use return false to further prevent default behavior
            return false;
        }
    });

    <?php if (!$isCompleteFactor) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all input elements with the class "tab-op" within the table
            const tableInputFields = document.querySelectorAll('table input.tab-op');

            // If there are "tab-op" inputs within the table
            if (tableInputFields.length > 0) {
                // Give focus to the first "tab-op" input
                tableInputFields[0].focus();
                tableInputFields[0].select();
            }
        });
    <?php endif; ?>

    document.addEventListener("keydown", function(event) {
        // Check if the Tab key is pressed
        if (event.key === 'Tab') {
            // Get all input elements with the class "tab-op" within the table
            const tableInputFields = document.querySelectorAll('table input.tab-op');

            // Find the currently focused input element
            const focusedInput = document.activeElement;

            // Check if the focused input is within the table or outside
            const isTableInput = Array.from(tableInputFields).includes(focusedInput);

            // If the focused input is within the table and has the class "tab-op"
            if (isTableInput) {
                // Prevent the default Tab behavior
                event.preventDefault();

                // Find the index of the currently focused input element
                const currentIndex = Array.from(tableInputFields).indexOf(focusedInput);

                // Calculate the index of the next input element with the class "tab-op"
                let nextIndex = (currentIndex + 1) % tableInputFields.length;

                // Use setTimeout to delay focusing on the next input element
                setTimeout(() => {
                    // Focus on the next input element with the class "tab-op"
                    tableInputFields[nextIndex].focus();
                    tableInputFields[nextIndex].select();
                }, 0);
            }
            // Allow default Tab behavior for inputs outside the table or without the "tab-op" class
        }
    });


    document.addEventListener("keydown", function(event) {

        if (event.key === 'Enter') {
            // Get all input elements with the class "tab-op" within the table
            const tableInputFields = document.querySelectorAll('table input.tab-op');

            // Find the currently focused input element
            const focusedInput = document.activeElement;
            const currentIndex = Array.from(tableInputFields).indexOf(focusedInput);

            // Check if the focused input is within the table and has the class "tab-op"
            if (Array.from(tableInputFields).includes(focusedInput)) {
                // Prevent the default Enter behavior
                event.preventDefault();

                const inputsPerRow = 3;
                // Calculate the index of the first input of the next row
                const nextRowFirstIndex = (Math.trunc(currentIndex / 3) * inputsPerRow + inputsPerRow) % tableInputFields.length;

                // Focus on the first input of the next row
                tableInputFields[nextRowFirstIndex].focus();
            }
        }
    });
</script>
<script src="./public/js/billSearchPart.js?= rand() ?>"></script>
<?php
require_once('./views/Layouts/footer.php');
