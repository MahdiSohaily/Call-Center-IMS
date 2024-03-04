<?php
require_once('./views/Layouts/header.php');
require_once './app/Controllers/DollarRateController.php';
?>
<div class="bg-white rounded-lg shadow-md m-5 w-1/2 mx-auto p-5">
    <div class="rtl flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            درصد تغییر قیمت دلار
            <i class="material-icons font-semibold text-orange-400">attach_money</i>
        </h2>
    </div>
    <div class="">
        <table class="rtl text-sm font-light min-w-full">
            <thead class="font-medium dark:border-neutral-500">
                <tr class="bg-violet-600">
                    <th scope="col" class="text-white p-2">
                        شماره
                    </th>
                    <th scope="col" class="text-white p-2">
                        در صد تغیر
                    </th>
                    <th scope="col" class="text-white p-2">
                        اعمال تا تاریخ
                    </th>
                    <th scope="col" class="text-white p-2">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody id="results">
                <tr class="odd:bg-gray-200">
                    <td class='p-2 rtl text-center'>
                        <?= $dollarRate['id'] ?>
                    </td>
                    <td class='p-2 rtl text-center'>
                        <?= $dollarRate['rate'] ?>
                    </td>
                    <td class='p-2 rtl text-center '>
                        <?= $dollarRate['created_at'] ?>
                    </td>
                    <td class='p-2 rtl text-center'>
                        <form class="w-full" action=<?= htmlspecialchars($_SERVER['PHP_SELF']) ?> method="post">
                            <?php if ($dollarRate['status']) : ?>
                                <input type="hidden" name="status" value='0'>
                                <button class=" shadow bg-red-500 hover:bg-red-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                                    غیر فعال سازی
                                </button>
                            <?php else : ?>
                                <input type="hidden" name="status" value='1'>
                                <button class=" shadow bg-green-500 hover:bg-green-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                                    فعال سازی
                                </button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="my-5  bg-white rounded-lg shadow-md w-1/2 mx-auto p-5">
    <div class="rtl">
        <div class="rtl flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                ویرایش درصد تغییر قیمت دلار
                <i class="material-icons font-semibold text-indigo-400">create</i>
            </h2>
        </div>
        <form class="w-full" action=<?= htmlspecialchars($_SERVER['PHP_SELF']) ?> method="post">
            <div class="flex flex-wrap mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                        درصد دلار
                    </label>
                    <input name="rate" value="<?= $dollarRate['rate'] ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="درصد دلار" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                        بازه اول
                    </label>
                    <input name="date" value="<?= $dollarRate['created_at'] ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="date" placeholder="اعمال تا تاریخ" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <button class=" shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                        ویرایش
                    </button>
                    <?php if ($status) : ?>
                        <p class="text-green-600 ">ویرایش موفقانه صورت گرفت.</p>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function deactivate(id) {
        const params = new URLSearchParams();
        params.append("deactivate", "deactivate");
        params.append("rate_id", rate_id);

        axios
            .post("./app/Controllers/GivenPriceAjax.php", params)
            .then(function(response) {
                if (response.data == true) {
                    form_success.style.bottom = "10px";
                    setTimeout(() => {
                        form_success.style.bottom = "-300px";
                    }, 2000);
                } else {
                    form_error.style.bottom = "10px";
                    setTimeout(() => {
                        form_error.style.bottom = "-300px";
                    }, 2000);
                }
            })
            .catch(function(error) {});
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
