<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<script src="./public/js/html2pdf.js"></script>
<link rel="stylesheet" href="./public/css/bill.css" />
<div id="bill_body_pdf" class="rtl bill">
    <div class="bill_header">
        <div class="bill_info">
            <table id="bill___ddate">
                <tr>
                    <td class="text-sm">شماره فاکتور:</td>
                    <td class="px-1 text-sm"><span id="billNO"></span></td>
                </tr>
                <tr>
                    <td class="text-sm"> تاریخ:</td>
                    <td class="px-1 text-sm"><span id="date"></span></td>
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

    <div class="customer_info relative flex">
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
        <p style="flex: 1;">امضاء تحویل گیرنده</p>
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
<p id="action_message" style="bottom:-100px; left:50%; transform: translateX(-50%); transition:all 0.5 all;" class="fixed bg-green-800 text-white py-3 px-5 rounded ">فاکتور شما با موفقیت ثبت شد</p>
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
                if (localStorage.getItem('operation') !== 'print')
                    BillInfo.billNO = bill_number - 1;
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
        if ((event.ctrlKey || event.metaKey) && (event.key === 'p' || event.keyCode === 80)) {
            event.preventDefault();
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

    function copyInfo(element) {
        const info = document.getElementById('name').innerHTML;
        const billNo = document.getElementById('billNO').innerHTML;

        const combinedText = `مشتری : ${info} \nشماره فاکتور : ${billNo}`;

        const textarea = document.createElement('textarea');
        textarea.value = combinedText;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);


        element.src = './public/img/complete.svg';

        setTimeout(() => {
            element.src = './public/img/copy.svg';
        }, 2000);
    }

    // Check if the code has already run for this unique identifier
    if (localStorage.getItem('operation') == 'save') {
        localStorage.setItem('operation', 'saved');
        var params = new URLSearchParams();
        params.append('saveInvoice', 'saveInvoice');
        params.append('customerInfo', JSON.stringify(customerInfo));
        params.append('BillInfo', JSON.stringify(BillInfo));
        params.append('billItems', JSON.stringify(billItems));
        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                console.log(response);
                const data = response.data;
                if (data == 'error') {
                    alert('خطایی رخ داده است');
                } else {
                    document.getElementById("action_message").style.bottom = "10px";
                    setTimeout(() => {
                        document.getElementById("action_message").style.bottom = "-100px";
                    }, 2000);
                }


            }).catch(function(error) {
                console.log(error);
            });
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
