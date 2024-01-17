<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<script src="./public/js/html2pdf.js"></script>

<style>
    .bill {
        width: 800px;
        margin-inline: auto;
        padding: 20px;
        background-color: white;
        min-height: 80vh;
    }

    .bill_header {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 7px;
    }

    .bill_info,
    .headline,
    .log_section {
        flex: 1;
    }

    .bill_info ul li {
        font-size: 14px !important;
    }

    .headline {
        text-align: center;
    }

    .log_section {
        display: flex;
        flex-direction: column;
        align-items: end;
    }

    .logo {
        width: 100px;
    }

    .customer_info {
        background-color: lightgray;
        margin-block: 10px;
        border-radius: 10px;
        padding: 10px;
        display: flex;
    }

    .customer_info>ul {
        flex: 1;
    }

    .customer_info>ul>li:first-child {
        padding-bottom: 10px;
    }

    .bill_items,
    .bill_footer {
        border: 1px solid black;
        margin-bottom: 10px;
    }

    .bill_items>table,
    .bill_footer>table {
        width: 100%;
    }

    thead {
        background-color: lightgray;
        font-size: 14px;
    }

    th {
        padding: 7px;
        color: black !important;
    }

    .bill_items>table td {
        padding: 7px;
    }

    .bill_footer>table td {
        padding: 3px 7px;
        font-size: 14px;
    }

    .action_button {
        width: 40px;
        cursor: pointer;
        height: 40px;
        cursor: pointer
    }

    .action_menu {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action_tooltip {
        position: absolute;
        width: 100px;
        height: 36px;
        border-bottom: 1px dotted black;
        background-color: gray;
        color: white;
        padding: 5px 10px;
        text-align: center;
        right: 100%;
        top: calc(50% - 18px);
        font-size: 14px;
        margin-right: 10px;
        border-radius: 5px;
        display: none;
    }

    .action_tooltip::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -5px;
        /* Adjust this value based on your design */
        margin-top: -5px;
        border-width: 5px 0 5px 5px;
        border-style: solid;
        border-color: transparent transparent transparent gray;
    }

    .action_button:hover+.action_tooltip {
        display: block;
    }

    .bill_info_footer {
        background-color: lightgray;
        color: black
    }

    @media print {
        * {
            font-size: 12px !important;
        }

        main,
        body,
        #wrapper {
            background-color: white !important;
        }

        .bill {
            width: 100% !important;
        }

        #page_header {
            display: none;
        }

        * {
            direction: rtl !important;
        }

        @page :footer {
            display: none !important;
        }

        @page :header {
            display: none !important;
        }

        @page {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        main {
            padding-block: 10px !important;
            margin: 0 !important;
        }

        #nav {
            display: none !important;
        }

        #side_nav {
            display: none !important;
        }

        .customer_info {
            background-color: white !important;
            border: 1px solid black !important;
        }

        .bill {
            padding: 0 !important;
        }

        .action_menu {
            display: none;
        }

        .bill_items {
            border: none !important;
        }

        .bill_items>table {
            border: 1px solid black !important;
        }

        .bill_items>table tr {
            background-color: white !important;
        }

        .bill_items>table tr:not(:last-child) {
            border-bottom: 1px solid black !important;
        }

        .bill_items>table tr th {
            color: black !important;
            border-bottom: 1px solid black !important;
        }

        .bill_items>table td {
            padding: 5px;
        }

        .bill_items>table td span {
            font-size: 10px !important;
        }

        .bill_footer>table td {
            padding: 3px 5px;
            font-size: 12px !important;
        }

        .bill_footer thead tr {
            background-color: white !important;
            border-bottom: 1px solid black !important;
        }

        .bill_info_footer {
            background-color: white !important;
        }
    }
</style>
<div id="bill_body_pdf" class="rtl bill">
    <div class="bill_header">
        <div class="bill_info">
            <ul>
                <li>
                    شماره فاکتور:
                    <span id="billNO"></span>
                </li>
                <li>
                    تاریخ:
                    <span id="date"></span>
                </li>
            </ul>
        </div>
        <div class="headline">
            <h2 style="margin-bottom: 7px;">فاکتور فروش</h2>
            <h2 style="margin-bottom: 7px;">یدک شاپ</h2>
        </div>
        <div class="log_section">
            <img class="logo" src="./public/img/logo.png" alt="logo of yadakshop">
        </div>

    </div>
    <p style="text-align: center; font-size: 12px;">نشانی: تهران - میدان بهارستان - کوچه نظامیه - بن بست ویژه پلاک ۴</p>

    <div class="customer_info">
        <ul>
            <li class="text-sm">
                نام :
                <span id="name"></span>
            </li>
            <li class="text-sm">
                شماره تماس:
                <span id="phone"></span>
            </li>
        </ul>
    </div>
    <div class="bill_items">
        <table>
            <thead>
                <tr style="padding: 10px !important;">
                    <th class="text-right">ردیف</th>
                    <!-- <th class="text-right">کد فنی</th> -->
                    <th class="text-right">نام قطعه</th>
                    <th class="text-right"> تعداد</th>
                    <th class="text-right"> قیمت</th>
                    <th class="text-right"> قیمت کل</th>
                </tr>
            </thead>
            <tbody id="bill_body">
            </tbody>
        </table>
    </div>
    <div class="bill_footer">
        <table class="w-full">
            <thead>
                <tr>
                    <th colspan="6">
                        اطلاعات فاکتور
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>تعداد
                        :
                        <input readonly placeholder="تعداد اقلام فاکتور" type="text" name="quantity" id="quantity">
                    </td>
                    <td>تخفیف
                        :
                        <input readonly placeholder="0" type="number" name="discount" id="discount">
                    </td>
                    <td>جمع
                        :
                        <input readonly placeholder="جمع کل اقلام فاکتور" type="text" name="totalPrice" id="totalPrice">
                    </td>
                </tr>
                <tr class="bill_info_footer">
                    <td style="padding:5px;">مبلغ قابل پرداخت : </td>
                    <td colspan="5" style="padding:10px;">
                        <p id="total_in_word" class="px-3 text-sm"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="display: flex; margin-top: 20px;">
        <p style="flex: 1;">امضاء خریدار</p>
        <p style="flex: 1;">امضاء فروشنده</p>
    </div>
</div>
<ul class="action_menu">
    <li style="position: relative;">
        <img class="action_button print" onclick="saveInvoice();" src="./public/img/print.svg" alt="print icon">
        <p class="action_tooltip">پرینت</p>
    </li>
    <li style="position: relative;">
        <img class="action_button share" src="./public/img/share.svg" alt="print icon">
        <p class="action_tooltip">اشتراک گذاری</p>
    </li>
    <li style="position: relative;">
        <img class="action_button pdf" src="./public/img/pdf.svg" onclick="handleSaveAsPdfClick()" alt="print icon">
        <p class="action_tooltip">پی دی اف</p>
    </li>
</ul>
<script>
    let bill_number = null;
    const customerInfo = JSON.parse(localStorage.getItem('customer_info'));
    const BillInfo = JSON.parse(localStorage.getItem('bill_info'));
    const billItems = JSON.parse(localStorage.getItem('bill_items'));
    getBillNumber();


    function getBillNumber() {

        var params = new URLSearchParams();
        params.append('getFactorNumber', 'getFactorNumber');
        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                bill_number = (response.data);
                BillInfo.billNO = bill_number;
                displayBill();
                displayCustomer();
                displayBillDetails();
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function displayBill() {
        let counter = 1;
        let template = ``;
        let totalPrice = 0;

        for (const item of billItems) {

            const payPrice = Number(item.quantity) * Number(item.price_per);
            totalPrice += payPrice;

            template += `
            <tr style="padding: 10px !important;" class="even:bg-gray-100">
                <td class="text-sm">
                    <span>${counter}</span>
                </td>
                <td class="text-sm">
                    <span>${item.partName}</span>
                </td>
                <td class="text-sm">
                    <span>${item.quantity}</span>
                </td>
                <td class="text-sm">
                    <span>${formatAsMoney(Number(item.price_per))}</span>
                </td>
                <td class="text-sm">
                    <span>${formatAsMoney(payPrice)}</span>
                </td>
            </tr> `;
            counter++;
        }
        bill_body.innerHTML = template;
    }

    function formatAsMoney(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function displayCustomer() {
        document.getElementById('name').innerHTML = customerInfo.name + " " + customerInfo.family ?? '';
        document.getElementById('phone').innerHTML = customerInfo.phone;
        // document.getElementById('car').innerHTML = customerInfo.car;
        // document.getElementById('address').innerHTML = customerInfo.address;
    }

    function displayBillDetails() {
        document.getElementById('billNO').innerHTML = BillInfo.billNO;
        document.getElementById('date').innerHTML = BillInfo.date;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('totalPrice').value = formatAsMoney(BillInfo.totalPrice);
        document.getElementById('discount').value = BillInfo.discount;
        document.getElementById('total_in_word').innerHTML = BillInfo.totalInWords;
    }

    document.addEventListener('keydown', function(event) {
        // Check if Ctrl (or Command on Mac) + P is pressed
        if ((event.ctrlKey || event.metaKey) && (event.key === 'p' || event.keyCode === 80)) {
            // Prevent the default action (in this case, printing)
            event.preventDefault();
            // Optionally, you can provide some feedback to the user
            console.log('Printing is disabled.');
        }
    });

    function handleSaveAsPdfClick() {
        const content = document.getElementById('bill_body_pdf');
        const opt = {
            filename: BillInfo.billNO + '-' + customerInfo.name + " " + customerInfo.family ?? '' + '.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(content).save();
    }

    function saveInvoice() {
        window.print();
    }

    // Check if the code has already run by checking a flag in sessionStorage
    if (!sessionStorage.getItem('codeExecuted')) {
        var params = new URLSearchParams();
        params.append('saveInvoice', 'saveInvoice');
        params.append('customerInfo', JSON.stringify(customerInfo));
        params.append('BillInfo', JSON.stringify(BillInfo));
        params.append('billItems', JSON.stringify(billItems));

        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                const data = response.data;

                if (data == 'error') {
                    alert('خطایی رخ داده است');
                } else {
                    alert('فاکتور شما با موفقیت ثبت شد');
                }

                // Set the flag in sessionStorage to indicate that the code has been executed
                sessionStorage.setItem('codeExecuted', true);
            }).catch(function(error) {
                console.log(error);
            });
    } else {
        // The code has already run, you can choose to skip or perform some other action
        console.log('Code already executed');
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
