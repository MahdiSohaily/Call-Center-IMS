<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
$sql = "SELECT * FROM cars";
$cars = $conn->query($sql);

$status_sql = "SELECT * FROM status";
$status = $conn->query($status_sql);
?>
<style>
    fieldset {
        background-color: lightgray;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    legend {
        font-size: 18px;
        font-weight: bold;
    }

    .bill_icon {
        width: 25px;
        height: 25px;
        max-width: 25px !important;
        cursor: pointer;
    }
</style>
<div style="height: 450px !important;" class="rtl h-1/3 grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8  px-4 mb-4">
    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/customer.svg" alt="customer icon">
                انتخاب مشتری
            </h2>
        </div>
        <div class="flex justify-center px-3">
            <input onkeyup="convertToPersian(this); searchCustomer(this.value)" type="text" name="customer" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
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
        <div class="flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); searchPartNumber(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder="کد فنی قطعه مورد نظر را وارد کنید..." />
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

        <div class="flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); search(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
        </div>

        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="p-3">
        </div>
        <div id="output"></div>
    </div>
</div>
<div class="rtl grid grid-cols-1 md:grid-cols-4 gap-6 lg:gap-8 px-4 mb-4">
    <div class="bg-white rounded-lg shadow-md p-2 w-full">
        <table class="min-w-full border border-gray-800 text-gray-400">
            <thead>
                <tr class="bg-gray-800 text-white text-center border-b mb-2">
                    <th colspan="2" class="py-2">
                        مشخصات خریدار
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">نام</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border" type="hidden" name="id" id="id">
                        <input class="w-full p-2 border" type="hidden" name="type" id="mode" value='create'>
                        <input class="w-full p-2 border text-gray-500" placeholder="اسم کامل مشتری را وارد کنید..." type="text" name="name" id="name">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">تلفون</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="093000000000" type="text" name="" id="phone">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">آدرس</td>
                    <td class="py-2 px-4">
                        <textarea name="address" id="address" cols="30" rows="4" class="border p-2 w-full text-gray-500" placeholder="آدرس مشتری"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">ماشین</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="نوعیت ماشین مشتری را مشخص کنید" type="text" name="" id="car">
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
                        <th class="py-2 px-4 border-b text-white w-10">ردیف</th>
                        <th class="py-2 px-4 border-b text-white">کد فنی</th>
                        <th class="py-2 px-4 border-b text-white">نام قطعه</th>
                        <th class="py-2 px-4 border-b text-white"> تعداد</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت کل</th>
                        <th class="py-2 px-4 border-b w-12 h-12 font-medium">
                            <img class="bill_icon" src="./public/img/setting.svg" alt="settings icon">
                        </th>
                    </tr>
                </thead>
                <tbody id="bill_body">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="h-16"></div>
<div class="rtl fixed flex items-center min-w-full h-12 bottom-0 bg-gray-800 px-3">
    <ul>
        <li>
            <p class="bg-white rounded text-gray-800 px-3 py-1 cursor-pointer" onclick="">
                صدور فاکتور
            </p>
        </li>
        </li>
    </ul>
</div>
<script>
    const customer_results = document.getElementById('customer_results');
    const resultBox = document.getElementById("selected_box");
    const bill_body = document.getElementById("bill_body");

    const billItems = [];

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
                                    <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 border-gray-300">
                                        <p class="text-sm font-semibold text-gray-600">
                                            ` + customer.name + `
                                            ` + customer.family + `
                                        </p>
                                        <p class="text-sm font-semibold text-gray-600">
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
                                    <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 border-gray-300">
                                        <p class="text-sm font-semibold text-gray-600">
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
        document.getElementById('id').value = customer.getAttribute('data-id');
        document.getElementById('mode').value = 'update';
        document.getElementById('name').value = customer.getAttribute('data-name').trim() + " " + customer.getAttribute('data-family').trim();
        document.getElementById('phone').value = customer.getAttribute('data-phone');
        document.getElementById('car').value = customer.getAttribute('data-car');
        document.getElementById('address').value = customer.getAttribute('data-address');
        customer_results.innerHTML = "";
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
            params.append('pattern', pattern);

            axios.post("./app/Controllers/BillController.php", params)
                .then(function(response) {
                    const data = response.data;
                    let template = ``;
                    for (const item of data) {
                        template += `
                                    <div class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 bg-gray-800">
                                        <div class="w-full py-3 flex justify-between items-center">      
                                            <p class="text-sm font-semibold text-white">
                                                   ${item.partnumber}
                                            </p>
                                            <p class="text-sm text-white">اسم قطعه بعدا اضافه می شود</p>
                                        </div>
                                        <div class="w-full flex justify-between items-center">
                                                <input type="number" onkeyup="updateCredential('data-price',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-sm text-white border border-2 placeholder:text-white bg-gray-800" placeholder="قیمت" />
                                                <input type="number" onkeyup="updateCredential('data-quantity',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-sm text-white border border-2 placeholder:text-white bg-gray-800" placeholder="تعداد" />
                                            <i id="${item.id}"
                                                data-quantity= "0"
                                                data-price= "0"
                                                data-partNumber = "${item.partnumber}"
                                                data-name = "بعدا اضافه می شود"
                                                onclick="selectGood(this)"
                                                    class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                                            </i>
                                        </div>
                                    </div>
                        `;
                    }
                    resultBox.innerHTML = template;
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
            resultBox.innerHTML = "";
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

        billItems.push([id, name, price, quantity, partNumber]);
        displayBill();
    }

    function displayBill() {
        for (const item of billItems) {
            template = `
                    <tr id="item-" class="even:bg-gray-100">
                        <td class="py-2 px-4 border-b">۱</td>
                        <td class="py-2 px-4 border-b">553113f650</td>
                        <td class="py-2 px-4 border-b">سپر جلوی سانتافه</td>
                        <td class="py-2 px-4 border-b">4</td>
                        <td class="py-2 px-4 border-b">۴۰۰۰۰۰۰۰</td>
                        <td class="py-2 px-4 border-b">۱۶۰۰۰۰۰۰۰۰۰۰۰۰۰</td>
                        <td class="py-2 px-4 border-b w-12 h-12 font-medium">
                            <img class="bill_icon" src="./public/img/subtract.svg" alt="subtract icon">
                        </td>
                    </tr> `;
        }
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
