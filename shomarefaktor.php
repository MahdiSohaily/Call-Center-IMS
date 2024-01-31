<?php
require_once './app/controller/FactorController.php';
require_once './layout/heroHeader.php';
?>
<style>
    .btn {
        border: 1px solid #0d782e;
        box-shadow: 0px 1px 2px 1px #1f8b40;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        color: white;
        background-color: #47a965;
    }

    .btn:hover {
        background-color: #45a049;
    }
</style>
<div class="shomare-faktor-date">
    <?= jdate('Y/m/d')  ?> -
    <?= jdate('l J F'); ?>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>
            <iframe width="100%" height="470px" src=""></iframe>
        </p>
    </div>
</div>

<div class="shomare-faktor-box">
    <a class="print-button hover:cursor-pointer" onClick="window.print()">چاپ <i class="fas fa-print"></i></a>
    <form>
        <label class="inline-block mr-3 pb-2 bold" for="invoice_time">زمان فاکتور</label>
        <br>
        <input class="p-2 mr-3" data-gdate="<?= date('Y/m/d') ?>" value="<?= (jdate("Y/m/d", time(), "", "Asia/Tehran", "en")) ?>" type="text" name="invoice_time" id="invoice_time">
        <span id="span_invoice_time"></span>
    </form>


    <form class="shomare-faktor-form" action="php/shomare-faktor-form-save.php" method="get" autocomplete="off">
        <input minlength="3" id="kharidar" class="kharidar" name="kharidar" type="text" placeholder="نام خریدار را وارد کنید ...">
        <button onclick="copiedEffect(this)" class="save-shomare-faktor-form hover:cursor-pointer" type="submit"> گرفتن شماره فاکتور</button>
        <a href="./report/factor_new.php" class="btn save-shomare-faktor-form" type="submit">مدیریت فاکتور</a>
    </form>
    <div class="shomare-faktor-result">
    </div>
</div>
<div id="resultBox" class="shomare-faktor-list-show">
    <div class="today-faktor-statistics">
        <div class="">
            <?php
            if (count($factors)) : ?>
                <div class="ranking mb-2">
                    <p class="text-white px-2">تعداد کل</p>
                    <span class="counter">
                        <?= count($factors) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="">
            <p class="today-faktor-plus">+</p>
            <?php
            if (count($countFactorByUser)) :
                $n = 1;
                foreach ($countFactorByUser as $row) :
                    $profile = '../userimg/default.png';
                    if (file_exists("../userimg/" . $row['user'] . ".jpg")) {
                        $profile = "../userimg/" . $row['user'] . ".jpg";
                    }
            ?>
                    <div class="ranking mb-2">
                        <img class="hover:cursor-pointer" data-id="<?= $row['user']; ?>" onclick="userReport(this)" src="<?= $profile ?>" />
                        <?php if ($n == 1) {
                            echo '<i class="fas ranking-icon fa-star golden"></i>';
                        }

                        if ($n == 2) {
                            echo '<i class="fas ranking-icon fa-star silver"></i>';
                        }

                        if ($n == 3) {
                            echo '<i class="fas ranking-icon fa-thumbs-up lucky"></i>';
                        }
                        $n = $n + 1; ?>
                        <span class="counter"><?= $row['count_shomare']; ?></span>
                    </div>

            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div>
        <table class="customer-list jadval-shomare">
            <thead>
                <tr class="table-heading">
                    <th>شماره فاکتور</th>
                    <th></th>
                    <th>خریدار</th>
                    <th>کاربر</th>
                    <?php
                    $isAdmin = $_SESSION['username'] === 'niyayesh' || $_SESSION['username'] === 'babak' ? true : false;
                    if ($isAdmin) : ?>
                        <th class="edit">ویرایش</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php

                if (count($factors)) :
                    foreach ($factors as $factor) :
                        $profile = '../userimg/' . $factor['user'] . '.jpg';
                        if (!file_exists($profile)) :
                            $profile = '../userimg/default.jpg"';
                        endif;
                ?>
                        <tr>
                            <td>
                                <div title="کپی کردن شماره فاکتور" style="cursor: pointer;" data-billNumber="<?= $factor['shomare'] ?>" class="jadval-shomare-blue" onClick='copyBillNumberSingle(this)'>
                                    <i class="fas fa-paste"></i>
                                    <?= $factor['shomare'] ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($factor['exists_in_bill']) : ?>
                                    <img class="w-6 mr-4 cursor-pointer" title="مشاهده فاکتور" src="./public/img/bill.svg" onclick="displayBill('<?= $factor['bill_id'] ?>')" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="jadval-shomare-kharidar"><?= $factor['kharidar'] ?></div>
                            </td>
                            <td class="flex justify-center">
                                <img onclick="userReport(this)" class="user-img hover:cursor-pointer mt-2" data-id="<?= $factor['user']; ?>" src="<?= $profile ?>" />
                            </td>

                            <?php
                            if ($isAdmin) : ?>
                                <td class="edit"><a id="<?= $factor["id"] ?>" class="edit-shomare-faktor-btn">ویرایش<i class="fas fa-edit"></i></a></td>
                            <?php endif; ?>
                        </tr>
                <?php
                    endforeach;
                endif;

                ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    const resultBox = document.getElementById('resultBox');
    let filter = false;
    $(function() {
        $("#invoice_time").persianDatepicker({
            months: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
            dowTitle: ["شنبه", "یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنج شنبه", "جمعه"],
            shortDowTitle: ["ش", "ی", "د", "س", "چ", "پ", "ج"],
            showGregorianDate: !1,
            persianNumbers: !0,
            formatDate: "YYYY/MM/DD",
            selectedBefore: !1,
            selectedDate: null,
            startDate: null,
            endDate: null,
            prevArrow: '\u25c4',
            nextArrow: '\u25ba',
            theme: 'default',
            alwaysShow: !1,
            selectableYears: null,
            selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            cellWidth: 25, // by px
            cellHeight: 20, // by px
            fontSize: 13, // by px
            isRTL: !1,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onShow: function() {},
            onHide: function() {},
            onSelect: function() {
                const date = ($("#invoice_time").attr("data-gdate"));
                var params = new URLSearchParams();
                params.append('getFactor', 'getFactor');
                params.append('date', date);
                axios.post("./factorAjax.php", params)
                    .then(function(response) {
                        resultBox.innerHTML = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            },
            onRender: function() {}
        });
    });

    const element = document.getElementById('invoice_time');


    function userReport(element) {
        const id = element.getAttribute('data-id');
        const date = ($("#invoice_time").attr("data-gdate"));
        var params = new URLSearchParams();

        filter = !filter;

        if (filter == false) {
            params.append('getFactor', 'getFactor');
            params.append('date', date);
            axios.post("./factorAjax.php", params)
                .then(function(response) {
                    resultBox.innerHTML = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
            return;
        }

        params.append('getReport', 'getReport');
        params.append('date', date);
        params.append('user', id);
        axios.post("./factorAjax.php", params)
            .then(function(response) {
                resultBox.innerHTML = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function copiedEffect(element) {
        const kharidar_value = document.getElementById('kharidar').value;

        console.log(kharidar_value.length);

        if (kharidar_value.length < 3) {
            element.innerHTML = 'گرفتن شماره فاکتور <i class="fas fa-ban" style="color:red; margin-inline:5px"></i>';
            return;
        }
        element.innerHTML = 'انجام شد<i class="fas fa-check" style="color:red; margin-inline:5px"></i>';
    }

    function copyBillNumberSingle(element) {
        const billNumber = element.getAttribute('data-billNumber');
        copyToClipboard(billNumber);

        element.innerHTML = '<i class="fas fa-check" style="color:red; margin-inline:5px"></i>' + billNumber;
    }

    function displayBill(id) {
        const factor_id = id;

        const form = document.createElement('form');
        form.className = 'absolute bottom-2 left-1/2';
        form.method = 'post';
        form.action = './report/generateBill_new.php';

        const inputBillId = document.createElement('input');
        inputBillId.type = 'hidden';
        inputBillId.name = 'BillId';
        inputBillId.value = factor_id;

        form.appendChild(inputBillId);

        document.body.appendChild(form);
        form.submit();
    }
</script>



<?php require_once './layout/heroFooter.php';
