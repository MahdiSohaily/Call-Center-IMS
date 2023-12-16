<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<style>
    .bill {
        width: 800px;
        margin-inline: auto;
        padding: 20px;
        min-height: 80vh !important;
        position: relative;
        /* Added position relative */
    }

    .bill::before {
        content: '';
        /* Added content for pseudo-element */
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: white;
        z-index: -1;
        /* Place behind the content */
    }

    .bill_header {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 10px;
        position: relative;
        /* Added position relative */
        z-index: 1;
        /* Ensure it's above the pseudo-element */
    }

    .bill_info,
    .headline,
    .log_section {
        flex: 1;
    }

    .headline {
        text-align: center;
    }

    .log_section {
        display: flex;
        justify-content: flex-end;
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
        border: 1px solid gray;
        margin-bottom: 10px;
    }

    .bill_items>table,
    .bill_footer>table {
        width: 100%;
    }

    thead {
        background-color: gray;
    }

    th {
        padding: 10px;
    }
    td {
        padding: 10px;
    }

    @media print {
        * {
            font-size: 14px;
        }

        .bill {
            width: 100% !important;
            padding: 0 !important;
        }

        .bill::before {
            display: none;
            /* Hide the pseudo-element in print */
        }

        #page_header,
        #nav,
        #side_nav {
            display: none !important;
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
    }
</style>

<div class="rtl bill">
    <div class="bill_header">
        <div class="bill_info">
            <ul>
                <li>
                    شماره فاکتور:
                    <span id="billNO"></span>
                </li>
                <li>
                    تاریخ:
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
    <div class="customer_info">
        <ul>
            <li>
                نام:
                <span id="name"></span>
            </li>
            <li>
                شماره تماس:
                <span id="phone"></span>
            </li>
        </ul>
        <ul>
            <li>
                خودرو:
                <span id="car"></span>
            </li>
            <li>
                آدرس:
                <span id="address"></span>
            </li>
        </ul>
    </div>
    <div class="bill_items">
        <table>
            <thead>
                <tr style="padding: 10px !important;">
                    <th>ردیف</th>
                    <th>کد فنی</th>
                    <th>نام قطعه</th>
                    <th> تعداد</th>
                    <th> قیمت</th>
                    <th> قیمت کل</th>
                </tr>
            </thead>
            <tbody id="bill_body">
            </tbody>
        </table>
    </div>
    <div class="bill_footer">
        <table>
            <thead>
                <tr>
                    <th colspan="2">
                        اطلاعات فاکتور
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>تعداد اقلام</td>
                    <td>
                        <input readonly placeholder="تعداد اقلام فاکتور" type="text" name="quantity" id="quantity">
                    </td>
                </tr>
                <tr>
                    <td>جمع کل</td>
                    <td>
                        <input readonly placeholder="جمع کل اقلام فاکتور" type="text" name="totalPrice" id="totalPrice">
                    </td>
                </tr>
                <tr>
                    <td>تخفیف</td>
                    <td>
                        <input readonly placeholder="0" type="number" name="discount" id="discount">
                    </td>
                </tr>
                <tr>
                    <td>مالبات (۰٪)</td>
                    <td>
                        <input readonly placeholder="0" type="number" name="tax" id="tax">
                    </td>
                </tr>
                <tr>
                    <td>عوارض</td>
                    <td>
                        <input readonly placeholder="0" type="number" name="withdraw" id="withdraw">
                    </td>
                </tr>
                <tr style="background-color: gray; color:white">
                    <td style="padding:10px;">مبلغ قابل پرداخت</td>
                    <td style="padding:10px;">
                        <p id="total_in_word" class="px-3 text-sm"></p>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    const customerInfo = JSON.parse(localStorage.getItem('customer_info'));
    const BillInfo = JSON.parse(localStorage.getItem('bill_info'));
    const billItems = JSON.parse(localStorage.getItem('bill_items'));
    displayBill();
    displayCustomer();
    displayBillDetails();

    function displayBill() {
        let counter = 1;
        let template = ``;
        let totalPrice = 0;

        for (const item of billItems) {

            const payPrice = Number(item.quantity) * Number(item.price);
            totalPrice += payPrice;

            template += `
            <tr style="padding: 10px !important;" class="even:bg-gray-100">
                <td>
                    <span>${counter}</span>
                </td>
                <td>
                    <span>${item.partNumber}</span>
                </td>
                <td>
                    <span>${item.name}</span>
                </td>
                <td>
                    <span>${item.quantity}</span>
                </td>
                <td>
                    <span>${formatAsMoney(Number(item.price))}</span>
                </td>
                <td>
                    <span>${formatAsMoney(payPrice)}</span>
                </td>
            </tr> `;
            counter++;
        }
        bill_body.innerHTML = template;
    }

    function formatAsMoney(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ریال';
    }

    function displayCustomer() {
        document.getElementById('name').innerHTML = customerInfo.name + " " + customerInfo.family;
        document.getElementById('phone').innerHTML = customerInfo.phone;
        document.getElementById('car').innerHTML = customerInfo.car;
        document.getElementById('address').innerHTML = customerInfo.address;
    }

    function displayBillDetails() {
        document.getElementById('billNO').innerHTML = BillInfo.billNO;
        document.getElementById('quantity').value = BillInfo.quantity;
        document.getElementById('totalPrice').value = BillInfo.totalPrice;
        document.getElementById('discount').value = BillInfo.discount;
        document.getElementById('tax').value = BillInfo.tax;
        document.getElementById('withdraw').value = BillInfo.withdraw;
        document.getElementById('total_in_word').innerHTML = BillInfo.totalInWords;
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
