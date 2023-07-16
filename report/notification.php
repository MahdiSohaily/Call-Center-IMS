<?php
require_once('./views/Layouts/header.php');
require_once('./database/connect.php');
require_once('./app/Controllers/notificationController.php');
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6 bg-gray-100 bg-opacity-25 mt-20 rtl ">
    <?php if ($notifications['admin']) { ?>
        <div class="max-w-7xl mb-5">
            <h1 class="text-2xl py-3 rtl" v-if="adminNotification">قیمت های پرسیده شده</h1>
            <table class="rtl min-w-full text-left text-sm font-light">
                <thead class="font-medium dark:border-neutral-500">
                    <tr class="bg-green-700">
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            شماره فنی
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            مشتری
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            کاربر
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            وضعیت
                        </th>
                        <?php if ($data['name'] === 'مریم') { ?>

                            <th scope="col" class="px-3 py-3 text-white text-center w-24">
                                عملیات
                            </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="results">
                    <?php if (count($notifications['adminNotification']) > 0) {

                        foreach ($notifications['adminNotification'] as $notification) {
                    ?>
                            <tr class="transition duration-300 ease-in-out bg-neutral-200 hover:bg-neutral-100">
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $notification['code'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $notification['customer_name'] . ' ' . $notification['customer_family'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <img class="userImage mx-2" src="../../userimg/<?php echo $notification['user_id'] ?>.jpg" alt="userimage">
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $notification['status'] === 'pending' ? 'در حال انتظار' : 'بررسی شده' ?>
                                </td>
                                <?php if ($data['name'] === 'مریم') { ?>
                                    <td class="whitespace-nowrap w-24">
                                        <div class="flex justify-center gap-1 items-center px-2">
                                            <form action="./showPriceReports.php" method="post" title="قیمت دهی">
                                                <input name="givenPrice" type="text" value="givenPrice" id="form" hidden>
                                                <input name="user" type="text" value="<?php echo $_SESSION['user_id'] ?>" id="form" hidden>
                                                <input name="customer" value="<?php echo $notification['customer_id'] ?>" type="number" hidden />
                                                <input name="code" value="<?php echo $notification['code'] ?>" type="text" hidden />
                                                <input name="notification" value="<?php echo $notification['id'] ?>" type="text" hidden />
                                                <button type="submit">
                                                    <i class="material-icons text-blue-500 hover:text-blue-700 hover:cursor-pointer">archive</i>
                                                </button>
                                            </form>
                                            <a title="نادیده گرفتن">
                                                <i onclick="weDontHave(this)" data-id="<?php echo $notification['id'] ?>" data-code="<?php echo $notification['code'] ?>" data-customer="<?php echo $notification['customer_id'] ?>" class="bold material-icons text-red-500 hover:text-red-700 hover:cursor-pointer">block</i>
                                            </a>
                                            <a title="نداریم">
                                                <i onclick="weDontHave(this)" data-id="<?php echo $notification['id'] ?>" data-code="<?php echo $notification['code'] ?>" data-customer="<?php echo $notification['customer_id'] ?>" class="bold material-icons text-red-500 hover:text-red-700 hover:cursor-pointer">close</i>
                                            </a>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr v-else class="transition duration-300 ease-in-out bg-neutral-200">
                            <td colspan="14" class="whitespace-nowrap px-3 py-3 text-center text-red-500 font-bold">
                                <i class="material-icons text-red-500">mood_bad</i>
                                <br />
                                !متاسفانه چیزی برای نمایش در پایگاه داده
                                موجود نیست
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <div class="max-w-7xl ">
        <h1 class="text-2xl py-3 rtl">جواب های دریافتی</h1>
        <table class="rtl min-w-full text-left text-sm font-light">
            <thead class="font-medium dark:border-neutral-500">
                <tr class="bg-green-700">
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        شماره فنی
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        مشتری
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        کاربر
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        قیمت داده شده </th>
                    <th scope="col" class="px-3 py-3 text-white text-center w-24">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody id="results">
                <?php if (count($notifications['answeredNotifications']) > 0) {
                    foreach ($notifications['answeredNotifications'] as $notification) { ?>
                        <tr class="transition duration-300 ease-in-out bg-neutral-200 hover:bg-neutral-100">
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['code'] ?>
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['customer_name'] ?>
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <img class="userImage mx-2" src="../../userimg/<?php echo $notification['user_id'] ?>.jpg" alt="userimage">
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['price'] ?>
                            </td>
                            <td class="whitespace-nowrap w-24">
                                <div class="flex justify-center gap-1 items-center px-2">
                                    <a>
                                        <i onclick="markUsRead(this)" data-id="<?php echo $notification['id'] ?>" class="material-icons text-blue-500 hover:text-blue-700 hover:cursor-pointer">remove_red_eye</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr class="transition duration-300 ease-in-out bg-neutral-200">
                        <td colspan="14" class="whitespace-nowrap px-3 py-3 text-center text-red-500 font-bold">
                            <i class="material-icons text-red-500">mood_bad</i>
                            <br />
                            !متاسفانه چیزی برای نمایش در پایگاه داده
                            موجود نیست
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="max-w-7xl ">
        <h1 class="text-2xl py-3 rtl">مشاهده شده</h1>
        <table class="rtl min-w-full text-left text-sm font-light">
            <thead class="font-medium dark:border-neutral-500">
                <tr class="bg-green-700">
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        شماره فنی
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        مشتری
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        کاربر
                    </th>
                    <th scope="col" class="px-3 py-3 text-white text-center">
                        قیمت داده شده
                    </th>
                </tr>
            </thead>
            <tbody id="results">
                <?php if (count($notifications['previousNotifications']) > 0) {
                    foreach ($notifications['previousNotifications'] as $notification) { ?>
                        <tr class="transition duration-300 ease-in-out bg-neutral-200 hover:bg-neutral-100">
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['code'] ?>
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['customer_name'] ?>
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <img class="userImage mx-2" src="../../userimg/<?php echo $notification['user_id'] ?>.jpg" alt="userimage">
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                <?php echo $notification['price'] ?>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr v-else class="transition duration-300 ease-in-out bg-neutral-200">
                        <td colspan="13" class="whitespace-nowrap px-3 py-3 text-center text-red-500 font-bold">
                            <i class="material-icons text-red-500">mood_bad</i>
                            <br />
                            !متاسفانه چیزی برای نمایش در پایگاه داده
                            موجود نیست
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    let result = null;

    function weDontHave(element) {
        const id = element.getAttribute('data-id');
        const code = element.getAttribute('data-code');
        const customer = element.getAttribute('data-customer');

        // Defining a params instance to be attached to the axios request
        const params = new URLSearchParams();
        params.append('weDontHave', 'weDontHave');
        params.append('id', id);
        params.append('code', code);
        params.append('customer', customer);

        axios.post("./app/Controllers/notificationAjaxController.php", params)
            .then(function(response) {
                location.reload();
            })
            .catch(function(error) {

            });
    }

    function markUsRead(element) {
        const id = element.getAttribute('data-id');
        const params = new URLSearchParams();
        params.append('markUsRead', 'markUsRead');
        params.append('id', id);
        axios.post("./app/Controllers/notificationAjaxController.php", params)
            .then(function(response) {
                location.reload();
            })
            .catch(function(error) {

            });
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
