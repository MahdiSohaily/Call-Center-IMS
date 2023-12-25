<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<style>
    label:after {
        content: '';
        position: absolute;
        right: 1em;
        color: #fff;
    }

    input:checked+label:after {
        content: '';
        line-height: .8em;
    }

    .accordion__content {
        max-height: 0em;
        transition: all 0.4s cubic-bezier(0.865, 0.14, 0.095, 0.87);
    }

    input[name='panel']:checked~.accordion__content {
        /* Get this as close to what height you expect */
        max-height: 50em;
    }

    .bordered_cell {
        border: 2px solid gray;
    }

    .month_days td:hover {
        border: 2px solid red !important;
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
        <div id="customer_results" style="overflow-y: auto; height:300px" class="p-3 overflow-y-auto">
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
        </div>

    </div>
</div>

<script>
    let user_id = <?= $_SESSION['user_id'] ?>;
    let now = moment().locale('en').format('YYYY-MM-DD');
    let active_date = null;
    let active_user = null;

    function setUserId(id) {
        user_id = id;

        bootStrap();
    }

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
                                            <td id="m-1"> فروردین</td>
                                            <td id="m-2"> اردیبهشت</td>
                                            <td id="m-3">خرداد</td>
                                        </tr>
                                        <tr>
                                            <td id="m-4">تیر</td>
                                            <td id="m-5">مرداد</td>
                                            <td id="m-6">شهریور</td>
                                        </tr>
                                        <tr>
                                            <td id="m-7">مهر</td>
                                            <td id="m-8">آبان</td>
                                            <td id="m-9"> آذر</td>
                                        </tr>
                                        <tr>
                                            <td id="m-10">دی</td>
                                            <td id="m-11">بهمن</td>
                                            <td id="m-12">اسفند</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="flex-grow p-2 " >
                                <table class="table month_days">
                                    <tr>
                                        <td id="day-1" class="text-center bordered_cell hover:text-green-700 align-center">1</td>
                                        <td id="day-2" class="text-center bordered_cell hover:text-green-700 align-center">2</td>
                                        <td id="day-3" class="text-center bordered_cell hover:text-green-700 align-center">3</td>
                                        <td id="day-4" class="text-center bordered_cell hover:text-green-700 align-center">4</td>
                                        <td id="day-5" class="text-center bordered_cell hover:text-green-700 align-center">5</td>
                                        <td id="day-6" class="text-center bordered_cell hover:text-green-700 align-center">6</td>
                                        <td id="day-7" class="text-center bordered_cell hover:text-green-700 align-center">7</td>
                                    </tr>
                                    <tr>
                                        <td id="day-8" class="text-center bordered_cell hover:text-green-700 align-center">8</td>
                                        <td id="day-9" class="text-center bordered_cell hover:text-green-700 align-center">9</td>
                                        <td id="day-10" class="text-center bordered_cell hover:text-green-700 align-center">10</td>
                                        <td id="day-11" class="text-center bordered_cell hover:text-green-700 align-center">11</td>
                                        <td id="day-12" class="text-center bordered_cell hover:text-green-700 align-center">12</td>
                                        <td id="day-13" class="text-center bordered_cell hover:text-green-700 align-center">13</td>
                                        <td id="day-14" class="text-center bordered_cell hover:text-green-700 align-center">14</td>
                                    </tr>
                                    <tr>
                                        <td id="day-15" class="text-center bordered_cell hover:text-green-700 align-center">15</td>
                                        <td id="day-16" class="text-center bordered_cell hover:text-green-700 align-center">16</td>
                                        <td id="day-17" class="text-center bordered_cell hover:text-green-700 align-center">17</td>
                                        <td id="day-18" class="text-center bordered_cell hover:text-green-700 align-center">18</td>
                                        <td id="day-19" class="text-center bordered_cell hover:text-green-700 align-center">19</td>
                                        <td id="day-20" class="text-center bordered_cell hover:text-green-700 align-center">20</td>
                                        <td id="day-21" class="text-center bordered_cell hover:text-green-700 align-center">21</td>
                                    </tr>
                                    <tr>
                                        <td id="day-22" class="text-center bordered_cell hover:text-green-700 align-center">22</td>
                                        <td id="day-23" class="text-center bordered_cell hover:text-green-700 align-center">23</td>
                                        <td id="day-24" class="text-center bordered_cell hover:text-green-700 align-center">24</td>
                                        <td id="day-25" class="text-center bordered_cell hover:text-green-700 align-center">25</td>
                                        <td id="day-26" class="text-center bordered_cell hover:text-green-700 align-center">26</td>
                                        <td id="day-27" class="text-center bordered_cell hover:text-green-700 align-center">27</td>
                                        <td id="day-28" class="text-center bordered_cell hover:text-green-700 align-center">28</td>
                                    </tr>
                                    <tr>
                                        <td id="day-29" class="text-center bordered_cell hover:text-green-700 align-center">29</td>
                                        <td id="day-30" class="text-center bordered_cell hover:text-green-700 align-center">30</td>
                                        <td id="day-31" class="text-center bordered_cell hover:text-green-700 align-center">31</td>
                                        <td id="day-32" class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-33" class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-34" class="text-center bordered_cell hover:text-green-700 align-center"></td>
                                        <td id="day-35" class="text-center bordered_cell hover:text-green-700 align-center"></td>
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

    function getUserIncompleteBills() {

    }

    function bootStrap() {
        active_date = now;


        getUserSavedBills();
        getUserIncompleteBills();
    }

    function sanitizeUsers(id) {

    }


    bootStrap();
    getUsers()
</script>
<?php
require_once('./views/Layouts/footer.php');
