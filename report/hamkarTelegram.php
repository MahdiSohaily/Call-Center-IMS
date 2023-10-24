<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GoodController.php');
?>
<div class="bg-white rounded-lg shadow-md m-5">
    <div class="rtl flex items-center justify-between p-3">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="material-icons font-semibold text-orange-400">security</i>
            مدیریت دسترسی کاربران
        </h2>
        <a href="./createUserProfile.php" class="bg-success text-white py-2 px-3 rounded-lg">ثبت کاربر جدید</a>
    </div>
    <div class="p-3 table-wrapper">
        <table class="table-fixed rtl min-w-full text-sm font-light">
            <thead id="blur" class="font-medium sticky top-20 dark:border-neutral-500" style="z-index: 99999999999999999999999999;">
                <tr class="bg-violet-600">
                    <th scope="col" class="text-white px-2 py-3">
                        شماره
                    </th>
                    <th scope="col" class="text-white px-2 py-3">
                        نام
                    </th>
                    <th scope="col" class="text-white px-2 py-3">
                        پروفایل
                    </th>
                    <th scope="col" class="text-white px-2 py-3">
                        هیوندا
                    </th>
                    <th scope="col" class="text-white px-2 py-3">
                        کیا
                    </th>

                    <th scope="col" class="text-white px-2 py-3">
                        عملیات
                    </th>
                </tr>
            </thead>
            <!-- <tbody id="results" class="divide-y divide-gray-300">
                <?php
                $counter = 1;
                foreach ($users as $user) :
                    $auth = json_decode($user['auth'], true);
                ?>
                    <tr class="even:bg-gray-200">
                        <td class='p-2 rtl'>
                            <?= $counter ?>
                        </td>
                        <td class='p-2 rtl'>
                            <?= $user['name'] . ' ' . $user['family'] ?>
                        </td>
                        <td class='p-2 rtl '>
                            <?= $user['username'] ?>
                        </td>

                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['usersManagement'] ? 'checked' : '' ?> data-authority="usersManagement" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['khorojkala-index'] ? 'checked' : '' ?> data-authority="khorojkala-index" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['vorodkala-index'] ? 'checked' : '' ?> data-authority="vorodkala-index" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['khorojkala-report'] ? 'checked' : '' ?> data-authority="khorojkala-report" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['vorodkala-report'] ? 'checked' : '' ?> data-authority="vorodkala-report" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['transfer_index'] ? 'checked' : '' ?> data-authority="transfer_index" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['transfer_report'] ? 'checked' : '' ?> data-authority="transfer_report" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['goodLimitReport'] ? 'checked' : '' ?> data-authority="goodLimitReport" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['goodLimitReportAll'] ? 'checked' : '' ?> data-authority="goodLimitReportAll" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['shomaresh-index'] ? 'checked' : '' ?> data-authority="shomaresh-index" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['telegramProcess'] ? 'checked' : '' ?> data-authority="telegramProcess" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['givePrice'] ? 'checked' : '' ?> data-authority="givePrice" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['showRates'] ? 'checked' : '' ?> data-authority="showRates" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['relationships'] ? 'checked' : '' ?> data-authority="relationships" data-user='<?= $user['id'] ?>'>
                        </td>
                        <td class='p-2 rtl'>
                            <input class="user-<?= $user['id'] ?>" onclick="updateUserAuthority(this)" type="checkbox" <?= $auth['defineExchangeRate'] ? 'checked' : '' ?> data-authority="defineExchangeRate" data-user='<?= $user['id'] ?>'>
                        </td>

                        <td class='p-2 rtl'>
                            <a href="./updateUserProfile.php?user=<?= $user['id'] ?>">
                                <i data-user="<?= $user['id'] ?>" class="material-icons cursor-pointer text-indigo-600 hover:text-indigo-800">edit</i>
                            </a>
                            <i onclick="deleteUser(this)" data-user="<?= $user['id'] ?>" class="material-icons cursor-pointer text-red-600 hover:text-red-800">do_not_disturb_on</i>
                        </td>
                    </tr>
                <?php
                    $counter++;
                endforeach; ?>
            </tbody> -->
        </table>
    </div>
</div>
<script>
    axios.post("http://localhost/telegram/", params)
        .then(function(response) {
            location.reload();
        })
        .catch(function(error) {

        });
</script>
<?php
require_once('./views/Layouts/footer.php');
