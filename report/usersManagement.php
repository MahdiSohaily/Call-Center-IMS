<?php
require_once('./views/Layouts/header.php');
require_once './app/Controllers/UserManagementController.php';
?>
<div class="bg-white rounded-lg shadow-md m-5">
    <div class="rtl flex items-center justify-between p-3">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="material-icons font-semibold text-orange-400">security</i>
            مدیریت دسترسی کاربران
        </h2>
    </div>
    <div class="p-3">
        <table class="rtl min-w-full text-left text-sm font-light">
            <thead class="font-medium dark:border-neutral-500">
                <tr class="bg-violet-600">
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        کاربر
                    </th>
                    <th scope="col" class="px-3 py-3 text-white">
                        دلار پایه
                    </th>
                    <th scope="col" class="px-3 py-3 text-white">
                        +10%
                    </th>
                    <th scope="col" class="px-3 py-3 text-white">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody id="results">
                <?php foreach ($users as $user) : ?>
                    <tr class="transition duration-300 ease-in-out hover:bg-neutral-200">
                        <td class='rtl text-center px-3 py-3'>
                            <?= $user['name'] . ' ' . $user['family'] ?>
                        </td>
                        <td class='rtl '>
                            <?= $user['username'] ?>
                        </td>

                        <td class='rtl  text-center px-3 py-3'>
                        </td>

                        <td class='rtl w-24'>

                        </td>
                        <td class='rtl px-3 py-3 kg'>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
require_once('./views/Layouts/footer.php');
