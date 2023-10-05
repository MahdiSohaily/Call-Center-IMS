<?php
require_once('./views/Layouts/header.php');
?>
<div class="bg-white rounded-lg shadow-md m-5">
    <div class="rtl flex items-center justify-between p-3">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="material-icons font-semibold text-orange-400">security</i>
            مدیریت دسترسی کاربران
        </h2>
    </div>
    <div class="p-3">
        <table class="min-w-full text-left text-sm font-light">
            <thead class="font-medium dark:border-neutral-500">
                <tr class="bg-green-700">
                    <th scope="col" class="px-3 py-3 bg-black text-white w-52 text-center">
                        شماره فنی
                    </th>
                    <th scope="col" class="px-3 py-3 text-white w-20">
                        دلار پایه
                    </th>
                    <th scope="col" class="px-3 py-3 text-white border-black border-r-2">
                        +10%
                    </th>
                    <th scope="col" class="px-3 py-3 text-white w-32 text-center">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody id="results">

            </tbody>
        </table>
    </div>
</div>
<?php
require_once('./views/Layouts/footer.php');
