<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
$sql = "SELECT * FROM rates ORDER BY amount ASC";
$rates = $conn->query($sql);
?>
<div class="rtl max-w-2xl mx-auto py-20 sm:px-6 lg:px-8 bg-white rounded-lg shadow-sm mt-11">

    <form action="" method="post">
        <div class="">
            <input type="text" name="form" value="update" hidden>
            <div class="col-span-6 sm:col-span-4">
                <label for="customer" class="block font-medium text-sm text-gray-700">
                    مشتری
                </label>
                <input name="customer" class="border-1 border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 p-3
                focus:ring-indigo-500 rounded-md shadow-sm px-3" required id="customer" type="text" />
                <p class="mt-2"></p>
            </div>
            <!-- Korea section -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="code" value="کدهای مدنظر" />
                <textarea rows="7" id="code" v-model="form.code" type="text" required class="border-1 border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3" placeholder="لطفا کد های مود نظر خود را در خط های مجزا قرار دهید"></textarea>
                <InputError :message="form.errors.code" class="mt-2" />
            </div>
        </div>

        <div v-if="hasActions" class="flex items-center justify-end py-3 text-right sm:rounded-bl-md sm:rounded-br-md">
            <button type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجو
            </button>
        </div>
    </form>
</div>
<script>
</script>
<?php
require_once('./views/Layouts/footer.php');
