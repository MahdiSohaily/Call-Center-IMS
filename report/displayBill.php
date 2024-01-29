<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<script src="./public/js/html2pdf.js"></script>
<link rel="stylesheet" href="./public/css/bill.css?v=<?= rand() ?>" />
<div id="bill_body_pdf" class="rtl bill">
    <div class="bill_header">
        <div class="bill_info">
            <div class="nisha-bill-info">
                <div class="A-main">
                    <div class="A-1">شماره</div>
                    <div class="A-2"><span id="billNO">5555</span></div>
                </div>
                <div class="B-main">
                    <div class="B-1">تاریخ</div>
                    <div class="B-2"><span id="date">1402-10-30</span></div>
                </div>
            </div>
        </div>
        <div class="headline">
            <h2 style="margin-bottom: 7px;">فاکتور فروش یدک شاپ</h2>
            <h2 style="margin-bottom: 7px;">لوازم یدکی هیوندای و کیا</h2>
        </div>
        <div class="log_section">
            <img class="logo" src="./public/img/logo.png" alt="logo of yadakshop">
        </div>
    </div>
    <div class="customer_info relative flex">
        <ul class="w-1/2">
            <li class="text-sm">
                نام :
                <span id="name"></span>
            </li>
            <li class="text-sm">
                شماره تماس:
                <span id="phone"></span>
            </li>
        </ul>
        <p class="w-1/2" id="userAddress" style="font-size: 13px;"></p>
        <img id="copy_icon" class="cursor-pointer" src="./public/img/copy.svg" alt="copy customer info" onclick="copyInfo(this)">
    </div>
    <div class="bill_items">
        <table>
            <thead>
                <tr class="bg-gray">
                    <th class="text-right w-8">ردیف</th>
                    <!-- <th class="text-right">کد فنی</th> -->
                    <th class="text-right">نام قطعه</th>
                    <th class="text-center w-12 border-r border-l-2 border-gray-800"> تعداد</th>
                    <th class="text-right w-32"> قیمت واحد</th>
                    <th class="text-right w-32"> قیمت کل (ریال)</th>
                </tr>
            </thead>
            <tbody id="bill_body">
            </tbody>
        </table>
    </div>
    <div class="bill_footer">
        <table class="w-full">
            <tbody>
                <tr class="bg-gray border-b border-gray-800">
                    <td class="text-right w-8"></td>
                    <td class="text-right">جمع فاکتور</td>
                    <td class="text-center w-12 border-r border-l-2 border-gray-800">
                        <span id="quantity" class="w-full"></span>
                    </td>
                    <td class="text-right w-32">
                        <span id="totalPrice" class="w-full"></span>
                    </td>
                    <td class="text-right w-32">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="w-8 border-l-2 border-gray-800 text-left">تخفیف : </td>
                    <td colspan="2" class="text-right w-8">
                        <span id="discount"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding:15px;" class="border-t border-b border-gray-800"></td>
                    <td colspan="2" style="padding:15px;" class="border-t border-b border-gray-800"></td>
                </tr>
                <tr>
                    <td class="text-right w-8"></td>
                    <td class="text-right">
                        <p>مبلغ قابل پرداخت:
                            <span id="total_in_word"></span>
                        </p>
                    </td>
                    <td class="text-center w-12 border-l-2 border-gray-800">

                    </td>
                    <td class="text-right w-32">
                        <span id="totalPrice2" class="w-full"></span>
                    </td>
                    <td class="text-right w-32">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex gap-5" style="margin-top: 20px;">
        <div class="tahvilgirande-box">
            <div class="tahvilgirande-box-header">مشخصات تحویل گیرنده</div>
            <div class="tahvilgirande-box-inner">
                <div>نام</div>
                <div>شماره تماس</div>
                <div>امضا</div>
            </div>
        </div>
        <div class="description-box flex-grow">
            <div class="tahvilgirande-box-header">توضیحات فاکتور</div>
            <div class="tahvilgirande-box-inner">
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
    <ul class="action_menu">
        <li style="position: relative;">
            <img class="action_button print" onclick="window.print();" src="./public/img/print.svg" alt="print icon">
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
                    const data = response.data;
                    if (data) {
                        BillInfo.billNO = data;
                        displayBill();
                        displayCustomer();
                        displayBillDetails();

                        document.getElementById("action_message").style.bottom = "10px";
                        setTimeout(() => {
                            document.getElementById("action_message").style.bottom = "-100px";
                        }, 2000);

                    } else {
                        document.getElementById("action_message").style.bottom = "10px";
                        document.getElementById("action_message").style.backgroundColor = "red";
                        document.getElementById("action_message").innerHTML = "فاکتور شما ثبت نشد شماره تماش مشتری از قبل در سیستم ریزو شده است.";
                        setTimeout(() => {
                            document.getElementById("action_message").style.bottom = "-100px";
                        }, 5000);
                    }



                }).catch(function(error) {
                    console.log(error);
                });
        } else {
            displayBill();
            displayCustomer();
            displayBillDetails();
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
                <td class="text-sm text-center">
                    <span>${counter}</span>
                </td>
                <td class="text-sm">
                    <span>${item.partName}</span>
                </td>
                <td class="text-sm border-r border-l-2 border-gray-800">
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
            if (customerInfo.address.length > 0) {
                document.getElementById('userAddress').innerHTML = 'نشانی :‌' + customerInfo.address;
            }
        }

        function displayBillDetails() {
            document.getElementById('billNO').innerHTML = BillInfo.billNO;
            document.getElementById('date').innerHTML = BillInfo.date.replace(/-/g, "/");
            document.getElementById('quantity').innerHTML = BillInfo.quantity;
            document.getElementById('totalPrice').innerHTML = formatAsMoney(BillInfo.totalPrice);
            document.getElementById('totalPrice2').innerHTML = formatAsMoney(BillInfo.totalPrice);
            document.getElementById('discount').innerHTML = BillInfo.discount;
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
    </script>
    <?php
    require_once('./views/Layouts/footer.php');
