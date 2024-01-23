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
        opacity: 1 !important;
    }
</style>
<script src="./public/js/jalaliMoment.js"></script>
<div class="rtl min-h-screen grid grid-cols-1 md:grid-cols-5 gap-4 lg:gap-5  px-4 mb-4">
    <div class="bg-white min-h-full col-span-2 rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/incomplete.svg" alt="customer icon">
                پیش فاکتور ها
            </h2>
            <span onclick="createIncompleteBill()" class="cursor-pointer bg-gray-600 text-white rounded px-3 py-2 mx-3 text-sm">ایجاد پیش فاکتور</span>
        </div>
        <div class="border-t border-gray-200"></div>
        <div class="p-3">
            <label for="incompleteBill" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">جستجو</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="incompleteBill" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  focus:outline-none" placeholder="جستجوی پیش فاکتور به اساس مشتری" required>
                <button onclick="searchForBill('incomplete')" type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">جستجو</button>
            </div>
        </div>
        <div id="incomplete_bill" class="p-3 overflow-y-auto">
            <!-- Search Results are going to be appended here -->
        </div>

    </div>

    <div class="bg-white min-h-full col-span-2 rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/saved_bill.svg" alt="customer icon">
                فاکتورهای ثبت شده
            </h2>
        </div>
        <div class="border-t border-gray-200"></div>
        <div class="p-3">
            <label for="completeBill" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">جستجو</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="completeBill" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  focus:outline-none" placeholder="جستجوی پیش فاکتور به اساس مشتری" required>
                <button onclick="searchForBill('complete')" type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">جستجو</button>
            </div>
        </div>
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
            <label for="users" class="block mb-2 text-md font-medium text-gray-900">کاربر:</label>
            <select onchange="setUserId(this.value)" name="user_id" id="users" class=" border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option>کاربر مد نظر خود را انتخاب کنید.</option>
                <option value="all">همه کاربران</option>
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
                                        <div onclick="selectDay(this)" data-day="<?= $counter ?>" data-month="<?= $index + 1 ?>" id="<?= $index . '-' . $counter . '-day' ?>" class="days border w-10 h-10 flex justify-center items-center text-md cursor-pointer hover:bg-gray-300"><?= $counter; ?></div>
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
    // Retrieve user_id from sessionStorage
    let user_id = <?= $_SESSION['user_id'] ?>;
    let now = moment().locale('en').format('YYYY-MM-DD');

    const year = (moment().locale('fa').format('YYYY'));
    const month = (moment().locale('fa').format('M'));
    const day = (moment().locale('fa').format('D'));

    let active_date = null;
    let active_user = null;

    // for styling the current day and month
    const current_month = Number(month) - 1;
    document.getElementById('month-' + current_month).checked = 'checked';
    document.getElementById(current_month + '-' + day + '-day').style.backgroundColor = 'red';
    document.getElementById(current_month + '-' + day + '-day').style.color = 'white';

    // Select all elements with the class '.accordion_condition' and toggle the accordion
    const accordions = document.querySelectorAll('.accordion_condition');
    const days = document.querySelectorAll('.days');
    accordions.forEach(function(accordion) {
        accordion.addEventListener('click', function(e) {
            accordions.forEach(function(accordion) {
                accordion.checked = false;
            });
            e.target.checked = 'checked'
        });
    });

    // Attach a click event listener to each day of month for generating specific date report
    days.forEach(function(day) {
        day.addEventListener('click', function(e) {
            unCheckDays();
            e.target.classList.add('selected_day');
        });
    });

    // Select users from the user filter option
    function setUserId(id) {
        user_id = id;
        unCheckDays();
        bootStrap();
    }

    // Select the specific day of the month for filtering the date
    function selectDay(element) {
        const selectedMonth = element.getAttribute('data-month');
        const selectedDay = element.getAttribute('data-day');
        now = moment.from(year + "/" + selectedMonth + "/" + selectedDay, 'fa', 'YYYY/MM/DD').format('YYYY/MM/DD');

        bootStrap();
    }

    // Get the specific user saved bills for the specific date
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
                appendCompleteFactorResults(factors)
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    // Attache the above retrieved data to its specific container
    function appendCompleteFactorResults(factors) {
        const completed_bill = document.getElementById('completed_bill');
        completed_bill.innerHTML = '';
        if (factors.length > 0) {
            for (const factor of factors) {
                completed_bill.innerHTML += `
                                <div class="card-container flex justify-between cursor-pointer h-24 relative border p-3 rounded shadow-sm flex-wrap mb-2">
                                    <div class="w-14 flex justify-center items-center">
                                        <img class="w-10 h-10 rounded-full" src="../../userimg/${factor.user_id}.jpg"/>
                                    </div>    
                                    <div class="flex-grow flex flex-col justify-between px-3">
                                        <div class="flex justify-between">
                                            <p class="text-md">
                                                شماره فاکتور:
                                                ${factor.bill_number}
                                            </p>
                                            <p class="text-md">
                                                تاریخ فاکتور:
                                                ${factor.bill_date}
                                            </p>
                                        </div>
                                        <div class="flex justify-between">
                                            <p class="text-md">
                                                مشتری: 
                                                ${factor.name ?? ''} ${factor.family ?? ''}
                                            </p>
                                            <p class="text-md">
                                                قیمت کل:
                                                ${formatAsMoney(factor.total)}
                                            </p>
                                        </div>
                                        <form id="form-${factor.id}" class="absolute bottom-2 left-1/2" method="post" action="./generateBill_new.php">
                                            <input type="hidden" name="BillId" value="${factor.id}">
                                        </form>
                                        <div onclick="EditFactorFormSubmission('form-${factor.id}')" class="edit-container absolute left-0 right-0 bottom-0 top-0 bg-gray-100 flex justify-center items-center">
                                            <ul class="flex gap-2">
                                                <li title="ویرایش فاکتور">
                                                    <img src="./public/img/editFactor.svg" class="hover:scale-125" />
                                                </li>
                                                <li title="ایجاد پیش فاکتور از این فاکتور">
                                                    <img src="./public/img/useFactorTemplate.svg" class="hover:scale-125" />
                                                </li>
                                            </ul>
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
                            <p class="text-md text-white">فاکتوری برای تاریخ مشخص شده درج نشده است.</p>
                        </div>`;
        }
    }

    // Get the specific user Incomplete bills for specific date
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
                appendIncompleteFactorResult(factors)
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    // Attache the above retrieved data to its specific container
    function appendIncompleteFactorResult(factors) {
        const incomplete_bill = document.getElementById('incomplete_bill');
        incomplete_bill.innerHTML = '';
        if (factors.length > 0) {
            for (const factor of factors) {
                incomplete_bill.innerHTML += `
                        <div id="card-${factor.id}" class="card-container flex justify-between cursor-pointer h-24 relative border p-3 rounded shadow-sm flex-wrap mb-2">
                            <div class="w-14 flex justify-center items-center">
                                <img class=" w-10 h-10 rounded-full" src ="../../userimg/${factor.user_id}.jpg"/>
                            </div>
                            <div class="flex-grow flex flex-col justify-between px-3">   
                                <div class="flex justify-between">
                                    <p class="text-md">
                                        مشتری: 
                                        ${factor.name ?? ''} ${factor.family ?? ''}
                                    </p>
                                    <p class="text-md">
                                        تاریخ فاکتور:
                                        ${factor.bill_date}
                                    </p>
                                </div>
                                <div class="flex justify-between">
                                    <p class="text-md">
                                        تعداد اقلام: 
                                        ${factor.quantity }
                                    </p>
                                    <p class="text-md">
                                        قیمت کل:
                                        ${formatAsMoney(factor.total)}
                                    </p>
                                    </div>
                                    <form id="form-${factor.id}" class="absolute bottom-2 left-1/2" method="post" action="./generateBill_new.php">
                                            <input type="hidden" name="BillId" value="${factor.id}">
                                    </form>
                                    <div class="edit-container absolute left-0 right-0 bottom-0 top-0 bg-gray-100 flex justify-center items-center">
                                        <ul class="flex gap-2">
                                            <li title="ویرایش فاکتور"  onclick="EditFactorFormSubmission('form-${factor.id}')">
                                                <img src="./public/img/editFactor.svg" class="hover:scale-125" />
                                            </li>
                                            <li title="حذف پیش فاکتور" onClick="confirmDelete('${factor.id}')">
                                                <img src="./public/img/deleteBill.svg" class="hover:scale-125" />
                                            </li>
                                        </ul>
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
                            <p class="text-md text-white">پیش فاکتوری برای تاریخ مشخص شده درج نشده است.</p>
                        </div>`;
        }
    }


    // Getting the bill id and send it for the editing
    // it works both for complete and incomplete bills 
    function EditFactorFormSubmission(formId) {
        document.getElementById(formId).submit();
    }

    // remove the selected styles from the day selected previously
    function unCheckDays() {
        days.forEach(function(day) {
            day.classList.remove('selected_day');
        });
    }

    // Start retrieving the data fro the user and specific date after initializing initial data
    function bootStrap() {
        active_date = now;
        getUserSavedBills();
        getUserIncompleteBills();
    }

    // create new Incomplete date for modification or assigning new bill
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
                form.action = './generateBill_new.php';

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

    let templateClone = null; // get a clone of bill card to have it if user cancelled deletion the bill

    // Display the confirmation message to ask whether confirms deletion or not
    function confirmDelete(factorId) {
        templateClone = document.getElementById('card-' + factorId).innerHTML;
        document.getElementById('card-' + factorId).innerHTML = `
                    <div class="w-full h-full flex flex-col justify-center items-center bg-rose-300">
                        <p class="text-white">آیا مطمئن هستید که میخواهید حذف صورت بگیرد ؟</p>
                        <div class="py-2">
                            <button onclick="deleteFactor('${factorId}')" class="px-4 text-white bg-red-700 rounded">بلی</button>
                            <button onclick="rollBack('${factorId}')" class="px-4 text-white bg-green-700 rounded">خیر</button>
                        </div>
                    </div>`;
    }

    // roll back the bill card after cancelling the deletion operation
    function rollBack(factorId) {
        document.getElementById('card-' + factorId).innerHTML = templateClone;
    }

    // Deletion confirmed and send ajax request to delete from the database the selected record
    function deleteFactor(factorId) {
        const params = new URLSearchParams();
        params.append('deleteFactor', 'deleteFactor');
        params.append('factorId', factorId);

        axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                if (response.data) {
                    document.getElementById('card-' + factorId).innerHTML = `
                    <div class="w-full h-full flex justify-center items-center bg-orange-500">
                        <p class="text-white">عملیات حذف موفقانه صورت گرفت</p>
                    </div>`;
                    setTimeout(() => {
                        document.getElementById('card-' + factorId).style.display = 'none';
                    }, 1000);
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    // helper function to display the bill total amount in money format
    function formatAsMoney(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ریال';
    }

    // Search among the completed bill or incomplete bills base on the customer name and last name
    function searchForBill(format) {
        let pattern = null;

        switch (format) {
            case 'incomplete':
                pattern = document.getElementById('incompleteBill').value;
                search(pattern, '0').then(function(factors) {
                    appendIncompleteFactorResult(factors);
                }).catch(function(error) {
                    console.log(error);
                });
                break;
            case 'complete':
                pattern = document.getElementById('completeBill').value;
                search(pattern, '1').then(function(factors) {
                    appendCompleteFactorResults(factors);
                }).catch(function(error) {
                    console.log(error);
                });
                break;
        }
    }

    function search(pattern, mode) {
        const params = new URLSearchParams();
        params.append('searchForBill', 'searchForBill');
        params.append('pattern', pattern);
        params.append('mode', mode);

        return axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                return response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    bootStrap();
</script>
<?php
require_once('./views/Layouts/footer.php');
