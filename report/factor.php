<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<script src="./public/js/persianDate.js"></script>
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

        <div id="selected_box" class="p-3" style="overflow-y: auto; height:300px">
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

        <div id="stock_result" class="p-3" style="overflow-y: auto; height:300px"></div>
    </div>
</div>

<script>
    const user_id = <?= $_SESSION['user_id'] ?>;

    function getUsers() {

    }

    function getUserSavedBills(user = user_id) {}

    function getUserIncompleteBills(user = user_id) {
        alert(user);
    }

    function bootStrap()
</script>
<?php
require_once('./views/Layouts/footer.php');
