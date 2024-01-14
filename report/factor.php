<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
require_once './app/Controllers/BillFilterController.php';
?>
<style>
    .accordion__content {
        max-height: 0em;
        transition: all 0.4s cubic-bezier(0.865, 0.14, 0.095, 0.87);
    }

    input[name='panel']:checked~.accordion__content {
        /* Get this as close to what height you expect */
        max-height: 50em;
    }

    .selected_day {
        background-color: rgb(156, 156, 156) !important;
    }

    .edit-container {
        opacity: 0 !important;
        transition: all 0.3s ease-in-out;
    }

    .card-container:hover .edit-container {
       opacity: 0.9 !important;
    }
</style>
<script src="./public/js/jalaliMoment.js"></script>
<div class="rtl min-h-screen grid grid-cols-1 md:grid-cols-3 gap-7 lg:gap-9  px-4 mb-4">
    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/incomplete.svg" alt="customer icon">
                پیش فاکتور ها
            </h2>
            <span onclick="createIncompleteBill()" class="cursor-pointer bg-gray-600 text-white rounded px-3 py-2 mx-3 text-sm">ایجاد پیش فاکتور</span>
        </div>
        <div class="border-t border-gray-200"></div>
        <div id="incomplete_bill" class="p-3 overflow-y-auto">
            <!-- Search Results are going to be appended here -->
        </div>

    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/saved_bill.svg" alt="customer icon">
                فاکتورهای ثبت شده
            </h2>
        </div>
        <div class="border-t border-gray-200"></div>

        <div id="completed_bill" class="p-3">
            <!-- selected items are going to be added here -->
        </div>
    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/select_user.svg" alt="inventory icon">
                انتخاب کاربر
            </h2>
        </div>
        <div class="border-t border-gray-200"></div>

        <div id="users_list" class="accordion flex flex-col min-h-screen p-3">
            <label for="users" class="block mb-2 text-sm font-medium text-gray-900">کاربر:</label>
            <select onchange="setUserId(this.value)" name="user_id" id="users" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option>کاربر مد نظر خود را انتخاب کنید.</option>
                <?php
                foreach ($users as $user) : ?>
                    <option <?= $user['id'] == $_SESSION['user_id'] ? 'selected' : '' ?> value="<?= $user['id'] ?>"><?= $user['name'] . " " . $user['family'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="accordion flex flex-col w-full py-3">
                <?php foreach (MONTHS as $index => $month) : ?>
                    <div class="">
                        <input class="accordion_condition hidden" type="checkbox" name="panel" id="month-<?= $index ?>">
                        <label for="month-<?= $index ?>" class="cursor-pointer relative block bg-gray-600 text-white p-2 shadow border-b border-grey"><?= $month ?></label>
                        <div class="accordion__content overflow-hidden bg-grey-lighter">
                            <h2 class="accordion__header pt-4 pl-4">روز مد نظر خود را انتخاب کنید:</h2>
                            <div class="flex justify-center items-center">
                                <div class="grid grid-cols-7 bg-gray-200 p-2 my-2 gap-0">
                                    <?php
                                    for ($counter = 1; $counter <= DAYS[$index]; $counter++) : ?>
                                        <div onclick="selectDay(this)" data-day="<?= $counter ?>" data-month="<?= $index + 1 ?>" id="<?= $index . '-' . $counter . '-day' ?>" class="days border w-10 h-10 flex justify-center items-center text-sm cursor-pointer hover:bg-gray-300"><?= $counter; ?></div>
                                    <?php endfor; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
<script>
    let user_id = <?= $_SESSION['user_id'] ?>;
    let now = moment().locale('en').format('YYYY-MM-DD');

    const year = (moment().locale('fa').format('YYYY'));
    const month = (moment().locale('fa').format('M'));
    const day = (moment().locale('fa').format('D'));

    let active_date = null;
    let active_user = null;

    const current_month = Number(month) - 1;

    document.getElementById('month-' + current_month).checked = 'checked';

    document.getElementById(current_month + '-' + day + '-day').style.backgroundColor = 'red';
    document.getElementById(current_month + '-' + day + '-day').style.color = 'white';

    // Select all elements with the class '.accordion_condition'
    const accordions = document.querySelectorAll('.accordion_condition');
    const days = document.querySelectorAll('.days');

    // Attach a click event listener to each selected element
    accordions.forEach(function(accordion) {
        accordion.addEventListener('click', function(e) {

            accordions.forEach(function(accordion) {
                accordion.checked = false;
            });

            e.target.checked = 'checked'
        });
    });

    // Attach a click event listener to each selected element
    days.forEach(function(day) {
        day.addEventListener('click', function(e) {
            ucCheckDays();
            e.target.classList.add('selected_day');
        });
    });

    function setUserId(id) {
        if (user_id != id) {
            user_id = id;
            ucCheckDays();
            bootStrap();
        }
    }

    function getUserSavedBills() {
        const completed_bill = document.getElementById('completed_bill');

        const params = new URLSearchParams();
        params.append('getUserCompleteBills', 'getUserCompleteBills');
        params.append('user', user_id);
        params.append('date', now);

        completed_bill.innerHTML = '';

        axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                const factors = response.data;
                if (factors.length > 0) {
                    for (const factor of factors) {
                        completed_bill.innerHTML += `
                    <div class="card-container flex justify-between cursor-pointer h-32 relative border p-3 rounded shadow-sm flex-wrap mb-2" >
                        <div class="w-20 flex justify-center items-center">
                            <img class=" w-12 h-12 rounded-full" src ="../../userimg/${user_id}.jpg"/>
                        </div>    
                        <div class="flex-grow flex flex-col justify-between px-3">
                            <div class ="flex justify-between">
                                <p class="text-sm">
                                    شماره فاکتور:
                                    ${factor.bill_number}
                                </p>
                                <p class="text-sm">
                                    تاریخ فاکتور:
                                    ${factor.bill_date}
                                </p>
                            </div>
                            <div class ="flex justify-between">
                                <p class="text-sm">
                                    مشتری: 
                                    ${factor.name} ${factor.family}</p>
                                <p class="text-sm">
                                    قیمت کل:
                                    ${factor.total}
                                </p>
                            </div>
                            <div class="">
                                <form id="form-${factor.id}" class="absolute bottom-2 left-1/2" method="post" action="./generateBill.php">
                                    <input type="hidden" name="BillId" value="${factor.id}">
                                </form>
                                </div>
                                <div onclick="submitForm('form-${factor.id}')" class="edit-container absolute left-0 right-0 bottom-0 top-0 bg-gray-100 flex justify-center items-center">
                                    <ul class="flex gap-2">
                                        <li title="ویرایش فاکتور">
                                            <svg width="30px" height="30px" viewBox="0 0 1024 1024" class="icon cursor-pointer hover:scale-125"  version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF" />
                                                <path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379" />
                                            </svg>
                                        </li>
                                        <li>
                                            <svg width="30px" height="30px" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" class="hover:scale-125">

                                            <defs>

                                            <style>.cls-1{fill:#cbecf9;}.cls-2{fill:#2fb1ea;}.cls-3{fill:#e5f5fc;}.cls-4{fill:#ffffff;}.cls-5{fill:#52e355;}.cls-6{fill:#90fc95;}.cls-7{fill:#f97171;}.cls-8{fill:#f88;}.cls-9{fill:#a4ecff;}.cls-10{fill:#d4ffd4;}.cls-11{fill:#ffbdbd;}.cls-12{fill:#bbf1ff;}.cls-13{fill:#fff4c5;}</style>

                                            </defs>

                                            <title>ایجاد پیش فاکتور از این فاکتور</title>

                                            <g id="Layer_2" data-name="Layer 2">

                                            <path class="cls-1" d="M201,232H95a20,20,0,0,1-20-20V64c0-11,9,0,20,0H201a20,20,0,0,1,20,20V212A20,20,0,0,1,201,232Z"/>

                                            <path class="cls-2" d="M201,234H95a22,22,0,0,1-22-22V64c0-2,.24-4.87,2.28-6.22,2.31-1.53,5.18-.3,8.81,1.25,3.1,1.32,7,3,10.91,3H201a22,22,0,0,1,22,22V212A22,22,0,0,1,201,234ZM77.77,61.07a.65.65,0,0,0-.28.05S77,61.58,77,64V212a18,18,0,0,0,18,18H201a18,18,0,0,0,18-18V84a18,18,0,0,0-18-18H95c-4.78,0-9-1.83-12.48-3.29A19.73,19.73,0,0,0,77.77,61.07Z"/>

                                            <rect class="cls-3" x="55" y="44" width="146" height="168" rx="20" ry="20"/>

                                            <path class="cls-2" d="M181,214H75a22,22,0,0,1-22-22V64A22,22,0,0,1,75,42H181a22,22,0,0,1,22,22V192A22,22,0,0,1,181,214ZM75,46A18,18,0,0,0,57,64V192a18,18,0,0,0,18,18H181a18,18,0,0,0,18-18V64a18,18,0,0,0-18-18Z"/>

                                            <rect class="cls-1" x="35" y="24" width="146" height="168" rx="20" ry="20"/>

                                            <rect class="cls-4" x="35" y="24" width="137" height="158.52" rx="20" ry="20"/>

                                            <path class="cls-2" d="M161,194H55a22,22,0,0,1-22-22V44A22,22,0,0,1,55,22H161a22,22,0,0,1,22,22V172A22,22,0,0,1,161,194ZM55,26A18,18,0,0,0,37,44V172a18,18,0,0,0,18,18H161a18,18,0,0,0,18-18V44a18,18,0,0,0-18-18Z"/>

                                            <rect class="cls-5" x="53" y="47" width="30" height="30" rx="6" ry="6"/>

                                            <rect class="cls-6" x="53" y="47" width="30" height="23" rx="6" ry="6"/>

                                            <path class="cls-2" d="M77,79H59a8,8,0,0,1-8-8V53a8,8,0,0,1,8-8H77a8,8,0,0,1,8,8V71A8,8,0,0,1,77,79ZM59,49a4,4,0,0,0-4,4V71a4,4,0,0,0,4,4H77a4,4,0,0,0,4-4V53a4,4,0,0,0-4-4Z"/>

                                            <rect class="cls-1" x="53" y="93" width="30" height="30" rx="6" ry="6"/>

                                            <rect class="cls-4" x="53" y="93" width="30" height="23" rx="6" ry="6"/>

                                            <path class="cls-2" d="M77,125H59a8,8,0,0,1-8-8V99a8,8,0,0,1,8-8H77a8,8,0,0,1,8,8v18A8,8,0,0,1,77,125ZM59,95a4,4,0,0,0-4,4v18a4,4,0,0,0,4,4H77a4,4,0,0,0,4-4V99a4,4,0,0,0-4-4Z"/>

                                            <rect class="cls-7" x="53" y="139" width="30" height="30" rx="6" ry="6"/>

                                            <rect class="cls-8" x="53" y="139" width="30" height="23" rx="6" ry="6"/>

                                            <path class="cls-2" d="M77,171H59a8,8,0,0,1-8-8V145a8,8,0,0,1,8-8H77a8,8,0,0,1,8,8v18A8,8,0,0,1,77,171ZM59,141a4,4,0,0,0-4,4v18a4,4,0,0,0,4,4H77a4,4,0,0,0,4-4V145a4,4,0,0,0-4-4Z"/>

                                            <rect class="cls-9" x="97" y="47" width="33" height="10" rx="3" ry="3"/>

                                            <rect class="cls-9" x="97" y="67" width="66" height="10" rx="3" ry="3"/>

                                            <rect class="cls-9" x="97" y="93" width="33" height="10" rx="3" ry="3"/>

                                            <rect class="cls-9" x="97" y="113" width="66" height="10" rx="3" ry="3"/>

                                            <rect class="cls-9" x="97" y="139" width="33" height="10" rx="3" ry="3"/>

                                            <rect class="cls-9" x="97" y="159" width="66" height="10" rx="3" ry="3"/>

                                            <circle class="cls-10" cx="24" cy="123" r="5"/>

                                            <circle class="cls-10" cx="128" cy="248" r="4"/>

                                            <circle class="cls-10" cx="248" cy="141" r="3"/>

                                            <circle class="cls-10" cx="189" cy="6" r="2"/>

                                            <circle class="cls-10" cx="218" cy="57" r="1"/>

                                            <circle class="cls-11" cx="249" cy="79" r="5"/>

                                            <circle class="cls-11" cx="201" cy="24" r="4"/>

                                            <circle class="cls-11" cx="19" cy="149" r="3"/>

                                            <circle class="cls-11" cx="24" cy="59" r="2"/>

                                            <circle class="cls-11" cx="85" cy="16" r="1"/>

                                            <circle class="cls-12" cx="58" cy="228" r="5"/>

                                            <circle class="cls-12" cx="234" cy="164" r="4"/>

                                            <circle class="cls-12" cx="131" cy="12" r="3"/>

                                            <circle class="cls-12" cx="230" cy="228" r="2"/>

                                            <circle class="cls-12" cx="228" cy="209" r="1"/>

                                            <circle class="cls-13" cx="7" cy="199" r="5"/>

                                            <circle class="cls-13" cx="234" cy="116" r="4"/>

                                            <circle class="cls-13" cx="175" cy="243" r="3"/>

                                            <circle class="cls-13" cx="42" cy="207" r="2"/>

                                            <circle class="cls-13" cx="157" cy="15" r="1"/>

                                            </g>

                                            </svg>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    }
                } else {
                    completed_bill.innerHTML = `<div class="flex flex-col justify-center items-center h-24 border border-rose-400 p-3 rounded shadow-sm shadow-rose-300 bg-rose-300">
                            <svg width="40px" height="40px" viewBox="0 -0.5 17 17" version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" class="si-glyph si-glyph-folder-error mb-2">
                                <title>938</title>
                                <defs>
                                </defs>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(1.000000, 2.000000)" fill="#fff">
                                        <path d="M7.35,3 L5.788,0.042 L2.021,0.042 L2.021,1.063 L0.023,1.063 L0.023,10.976 L1.043,10.976 L1.045,11.976 L15.947,11.976 L15.968,3 L7.35,3 L7.35,3 Z M10.918,9.109 L10.09,9.938 L8.512,8.361 L6.934,9.938 L6.104,9.109 L7.682,7.531 L6.104,5.953 L6.934,5.125 L8.512,6.701 L10.088,5.125 L10.918,5.953 L9.34,7.531 L10.918,9.109 L10.918,9.109 Z" class="si-glyph-fill"></path>
                                        <path d="M13.964,1.982 L13.964,1.042 L8.024,1.042 L8.354,1.982 L13.964,1.982 Z" class="si-glyph-fill"></path>
                                    </g>
                                </g>
                            </svg>      
                            <p class="text-sm text-white">فاکتوری برای تاریخ مشخص شده درج نشده است.</p>
                        </div>`;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function getUserIncompleteBills() {
        const incomplete_bill = document.getElementById('incomplete_bill');

        const params = new URLSearchParams();
        params.append('getUserIncompleteBills', 'getUserIncompleteBills');
        params.append('user', user_id);
        params.append('date', now);

        incomplete_bill.innerHTML = '';

        axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                const factors = response.data;
                if (factors.length > 0) {
                    for (const factor of factors) {
                        incomplete_bill.innerHTML += `
                        <div class="flex justify-between cursor-pointer h-32 relative border p-3 rounded shadow-sm flex-wrap mb-2">
                            <div class="w-20 flex justify-center items-center">
                                <img class=" w-12 h-12 rounded-full" src ="../../userimg/${user_id}.jpg"/>
                            </div>
                            <div class="flex-grow flex flex-col justify-between px-3">   
                                <div class="flex justify-between">
                                    <p class="text-sm">
                                        شماره فاکتور:
                                        ${factor.bill_number}
                                    </p>
                                    <p class="text-sm">
                                        تاریخ فاکتور:
                                        ${factor.bill_date}
                                    </p>
                                </div>
                                <div class="flex justify-between">
                                    <p class="text-sm">
                                        مشتری: 
                                        ${factor.name} ${factor.family}
                                    </p>
                                    <p class="text-sm">
                                        قیمت کل:
                                        ${factor.total}
                                    </p>
                                    </div>
                                    <div>
                                        <form id="form-${factor.id}" class="absolute bottom-2 left-1/2" method="post" action="./generateBill.php">
                                            <input type="hidden" name="BillId" value="${factor.id}">
                                        </form>
                                    </div>
                                    <div onclick="submitForm('form-${factor.id}')" class="absolute left-0 right-0 bottom-2 flex justify-center">
                                        <span class="px-5 py-1 rounded-sm text-xs text-center text-white bg-indigo-400 text-white"> ویرایش</span>
                                    </div>
                            </div>
                        </div>
                            `;
                    }
                } else {
                    incomplete_bill.innerHTML = `
                        <div class="flex flex-col justify-center items-center h-24 border border-orange-400 p-3 rounded shadow-sm shadow-orange-300 bg-orange-300">
                            <svg width="40px" height="40px" viewBox="0 -0.5 17 17" version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" class="si-glyph si-glyph-folder-error mb-2">
                                <title>938</title>
                                <defs>
                                </defs>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(1.000000, 2.000000)" fill="#fff">
                                        <path d="M7.35,3 L5.788,0.042 L2.021,0.042 L2.021,1.063 L0.023,1.063 L0.023,10.976 L1.043,10.976 L1.045,11.976 L15.947,11.976 L15.968,3 L7.35,3 L7.35,3 Z M10.918,9.109 L10.09,9.938 L8.512,8.361 L6.934,9.938 L6.104,9.109 L7.682,7.531 L6.104,5.953 L6.934,5.125 L8.512,6.701 L10.088,5.125 L10.918,5.953 L9.34,7.531 L10.918,9.109 L10.918,9.109 Z" class="si-glyph-fill"></path>
                                        <path d="M13.964,1.982 L13.964,1.042 L8.024,1.042 L8.354,1.982 L13.964,1.982 Z" class="si-glyph-fill"></path>
                                    </g>
                                </g>
                            </svg>      
                            <p class="text-sm text-white">پیش فاکتوری برای تاریخ مشخص شده درج نشده است.</p>
                        </div>`;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function selectDay(element) {

        const selectedMonth = element.getAttribute('data-month');
        const selectedDay = element.getAttribute('data-day');
        now = moment.from(year + "/" + selectedMonth + "/" + selectedDay, 'fa', 'YYYY/MM/DD').format('YYYY/MM/DD');

        bootStrap();
    }

    function submitForm(formId) {
        document.getElementById(formId).submit();
    }

    function ucCheckDays() {
        days.forEach(function(day) {
            day.classList.remove('selected_day');
        });
    }

    function bootStrap() {
        active_date = now;
        getUserSavedBills();
        getUserIncompleteBills();
    }

    function createIncompleteBill() {
        const params = new URLSearchParams();
        params.append('create_incomplete_bill', 'create_incomplete_bill');
        params.append('date', moment().locale('fa').format('YYYY-MM-DD'));

        axios.post("./app/Controllers/BillController.php", params)
            .then(function(response) {
                const factor_id = response.data;

                const form = document.createElement('form');
                form.className = 'absolute bottom-2 left-1/2';
                form.method = 'post';
                form.action = './generateBill.php';

                const inputBillId = document.createElement('input');
                inputBillId.type = 'hidden';
                inputBillId.name = 'BillId';
                inputBillId.value = factor_id;

                form.appendChild(inputBillId);

                document.body.appendChild(form);
                form.submit();

            })
            .catch(function(error) {
                console.log(error);
            });
    }

    bootStrap();
</script>
<?php
require_once('./views/Layouts/footer.php');
