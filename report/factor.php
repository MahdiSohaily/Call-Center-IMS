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
</style>
<script src="./public/js/jalaliMoment.js"></script>
<div class="rtl min-h-screen grid grid-cols-1 md:grid-cols-3 gap-7 lg:gap-9  px-4 mb-4">
    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img class="w-7 h-7" src="./public/img/incomplete.svg" alt="customer icon">
                پیش فاکتور ها
            </h2>
        </div>
        <div class="border-t border-gray-200"></div>
        <div id="unCompleted_bill" class="p-3 overflow-y-auto">
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
                <?php
                foreach ($users as $user) : ?>
                    <option <?= $user['id'] === $_SESSION['user_id'] ? 'checked' : '' ?> value="<?= $user['id'] ?>"><?= $user['name'] . " " . $user['family'] ?></option>
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



    function getUsers() {
        const params = new URLSearchParams();
        params.append('getUsers', 'getUsers');

        axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                let other = response.data.filter(function(user) {
                    return user.id != user_id
                });

                let me = response.data.filter(function(user) {
                    return user.id == user_id
                });

                let users = [...me, ...other];

                const result_container = document.getElementById('users_list');
                result_container.innerHTML = '';

                for (const user of users) {
                    result_container.innerHTML += `
                    <div class="cursor-pointer">
                        <input type="checkbox" name="panel" id="panel-${user.id}" class="hidden">
                        <label onclick="setUserId(${user.id})" for="panel-${user.id}" class="cursor-pointer relative block bg-black text-white p-4 shadow border-b border-grey flex items-center">
                            <img class="w-12 h-12 rounded ml-2" src="../../userimg/${user.id}.jpg"/>
                            <p class="font-medium pr-1">${user.name}</p>
                            <p class="font-medium pr-1">${user.family}</p>
                        </label>
                        <div class="accordion__content overflow-hidden bg-grey-lighter">
                            <h2 class="accordion__header pt-4 pl-4 border-b pb-2">انتخاب زمان مشخص</h2>
                            <div class="flex flex-wrap">
                                <div class="flex-grow p-2 " >
                                    <table class="table">
                                        <tr>
                                            <td style="` + (month == 1 ? 'background-color:red; color:white' : '') + `" class="text-center">فروردین</td>
                                            <td style="` + (month == 2 ? 'background-color:red; color:white' : '') + `" class="text-center">اردیبهشت</td>
                                            <td style="` + (month == 3 ? 'background-color:red; color:white' : '') + `" class="text-center">خرداد</td>
                                        </tr>
                                        <tr>
                                            <td style="` + (month == 4 ? 'background-color:red; color:white' : '') + `" class="text-center">تیر</td>
                                            <td style="` + (month == 5 ? 'background-color:red; color:white' : '') + `" class="text-center">مرداد</td>
                                            <td style="` + (month == 6 ? 'background-color:red; color:white' : '') + `" class="text-center">شهریور</td>
                                        </tr>
                                        <tr>
                                            <td style="` + (month == 7 ? 'background-color:red; color:white' : '') + `" class="text-center">مهر</td>
                                            <td style="` + (month == 8 ? 'background-color:red; color:white' : '') + `" class="text-center">آبان</td>
                                            <td style="` + (month == 9 ? 'background-color:red; color:white' : '') + `" class="text-center">آذر</td>
                                        </tr>
                                        <tr>
                                            <td style="` + (month == 10 ? 'background-color:red; color:white' : '') + `" class="text-center">دی</td>
                                            <td style="` + (month == 11 ? 'background-color:red; color:white' : '') + `" class="text-center">بهمن</td>
                                            <td style="` + (month == 12 ? 'background-color:red; color:white' : '') + `" class="text-center">اسفند</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="flex-grow p-2 " >
                                <table class="table month_days">
                                    <tr>
                                        <td id="day-1"  style="` + (day == 1 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">1</td>
                                        <td id="day-2"  style="` + (day == 2 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">2</td>
                                        <td id="day-3"  style="` + (day == 3 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">3</td>
                                        <td id="day-4"  style="` + (day == 4 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">4</td>
                                        <td id="day-5"  style="` + (day == 5 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">5</td>
                                        <td id="day-6"  style="` + (day == 6 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">6</td>
                                        <td id="day-7"  style="` + (day == 7 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">7</td>
                                    </tr>
                                    <tr>
                                        <td id="day-8"  style="` + (day == 8 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">8</td>
                                        <td id="day-9"  style="` + (day == 9 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">9</td>
                                        <td id="day-10"  style="` + (day == 10 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">10</td>
                                        <td id="day-11"  style="` + (day == 11 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">11</td>
                                        <td id="day-12"  style="` + (day == 12 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">12</td>
                                        <td id="day-13"  style="` + (day == 13 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">13</td>
                                        <td id="day-14"  style="` + (day == 14 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">14</td>
                                    </tr>
                                    <tr>
                                        <td id="day-15"  style="` + (day == 15 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">15</td>
                                        <td id="day-16"  style="` + (day == 16 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">16</td>
                                        <td id="day-17"  style="` + (day == 17 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">17</td>
                                        <td id="day-18"  style="` + (day == 18 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">18</td>
                                        <td id="day-19"  style="` + (day == 19 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">19</td>
                                        <td id="day-20"  style="` + (day == 20 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">20</td>
                                        <td id="day-21"  style="` + (day == 21 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">21</td>
                                    </tr>
                                    <tr>
                                        <td id="day-22"  style="` + (day == 22 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">22</td>
                                        <td id="day-23"  style="` + (day == 23 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">23</td>
                                        <td id="day-24"  style="` + (day == 24 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">24</td>
                                        <td id="day-25"  style="` + (day == 25 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">25</td>
                                        <td id="day-26"  style="` + (day == 26 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">26</td>
                                        <td id="day-27"  style="` + (day == 27 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">27</td>
                                        <td id="day-28"  style="` + (day == 28 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">28</td>
                                    </tr>
                                    <tr>
                                        <td id="day-29"  style="` + (day == 29 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">29</td>
                                        <td id="day-30"  style="` + (day == 30 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">30</td>
                                        <td id="day-31"  style="` + (day == 31 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center">31</td>
                                        <td id="day-32"  style="` + (day == 32 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-33"  style="` + (day == 33 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-34"  style="` + (day == 34 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-35"  style="` + (day == 35 ? 'background-color:red; color:white' : '') + ` class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }

            })
            .catch(function(error) {
                console.log(error);
            });
    }

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
                    <div class="flex flex-column justify-between cursor-pointer h-24 relative border p-3 rounded shadow-sm flex-wrap mb-2" >
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
                    </div>
                    `;
                    }
                } else {
                    completed_bill.innerHTML = `<div class="flex justify-between">
                        <p>فاکتوری ثبت نشده است.</p>
                    </div>`;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function getUserUnCompleteBills() {
        const unCompleted_bill = document.getElementById('unCompleted_bill');

        const params = new URLSearchParams();
        params.append('getUserUnCompleteBills', 'getUserUnCompleteBills');
        params.append('user', user_id);
        params.append('date', now);

        unCompleted_bill.innerHTML = '';

        axios.post("./app/Controllers/BillManagement.php", params)
            .then(function(response) {
                const factors = response.data;
                if (factors.length > 0) {
                    for (const factor of factors) {
                        unCompleted_bill.innerHTML += `
                            <div class="flex flex-column justify-between cursor-pointer h-24 relative border p-3 rounded shadow-sm flex-wrap mb-2" >
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
                            </div>
                            `;
                    }
                } else {
                    unCompleted_bill.innerHTML = `<div class="flex justify-between">
                <p>فاکتوری ثبت نشده است.</p>
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

    function ucCheckDays() {
        days.forEach(function(day) {
            day.classList.remove('selected_day');
        });
    }

    function bootStrap() {
        active_date = now;
        getUserSavedBills();
        getUserUnCompleteBills();
    }

    function sanitizeUsers(id) {

    }

    bootStrap();
    // getUsers()
</script>
<?php
require_once('./views/Layouts/footer.php');
