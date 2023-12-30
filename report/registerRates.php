<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/RateController.php');

$form = 'create';

if (isset($_GET['form'])) {
    $form = $_GET['form'];
}

?>
<div>
    <div class="rtl max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <?php if (isset($_GET['id']) && $form === 'update') {
        ?>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 flex justify-between">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">
                            ویرایش نرخ ارز </h3>

                        <p class=" mt-1 text-sm text-gray-600">
                            برای ویرایش نرخ ارز انتخاب شده اطلاعات ذیل را به دقت ویرایش نمایید. </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="" method="post">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-6 gap-6">
                                <input type="text" name="form" value="update" hidden>
                                <div class="col-span-6 sm:col-span-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        نرخ ارز
                                    </label>
                                    <input name="rate_price" value="<?php echo $selected_rate['amount'] ?>" class="border mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" required id="serial" type="text" />
                                    <p class="mt-2"></p>
                                </div>

                                <!-- Price -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label class="block font-medium text-sm text-gray-700" for="status">
                                        <span>شاخص نرخ ارز</span></label>
                                    <select name="status" class="border mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="status">
                                        <option <?php if ($selected_rate['status'] === 'A') echo 'selected' ?> class="A" value="A">A</option>
                                        <option <?php if ($selected_rate['status'] === 'B') echo 'selected' ?> class="B" value="B">B</option>
                                        <option <?php if ($selected_rate['status'] === 'C') echo 'selected' ?> class="C" value="C">C</option>
                                        <option <?php if ($selected_rate['status'] === 'D') echo 'selected' ?> class="D" value="D">D</option>
                                        <option <?php if ($selected_rate['status'] === 'E') echo 'selected' ?> class="E" value="E">E</option>
                                        <option <?php if ($selected_rate['status'] === 'F') echo 'selected' ?> class="F" value="F">F</option>
                                        <option <?php if ($selected_rate['status'] === 'G') echo 'selected' ?> class="G" value="G">G</option>
                                        <option <?php if ($selected_rate['status'] === 'N') echo 'selected' ?> class="N" value="N">N</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-if="hasActions" class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"><i class="px-2 material-icons hover:cursor-pointer">import_export</i> ویرایش </button>
                            <?php
                            if ($success) {
                            ?>
                                </br>
                                <p class="text-green-400"><?php echo $success ?></p>
                            <?php
                            }
                            ?>

                            <?php
                            if ($errors) {
                            ?>
                                <br>
                                <p class="text-orange-400"><?php echo $errors ?></p>
                            <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>

        <?php
        } else { ?>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 flex justify-between">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">
                            ثبت نرخ ارز
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            برای ثبت نرخ ارز جدید در سیستم لطفا فورم ذیل را با دقت پر نمایید.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="" method="post">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-6 gap-6">
                                <input type="text" name="form" value="create" hidden>
                                <div class="col-span-6 sm:col-span-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        نرخ ارز
                                    </label>
                                    <input name="rate_price" class="border mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" required id="serial" type="text" />
                                    <p class="mt-2"></p>
                                </div>

                                <!-- Price -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label class="block font-medium text-sm text-gray-700" for="status">
                                        <span>شاخص نرخ ارز</span></label>
                                    <select name="status" class="border mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2" id="status">
                                        <option class="A" value="A">A</option>
                                        <option class="B" value="B">B</option>
                                        <option class="C" value="C">C</option>
                                        <option class="D" value="D">D</option>
                                        <option class="E" value="E">E</option>
                                        <option class="F" value="F">F</option>
                                        <option class="G" value="G">G</option>
                                        <option selected class="N" value="N">N</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-4 flex items-stretch">
                                    <input class="mx-2" type="checkbox" name="selected" id="selected">
                                    <label for="selected" class="text-sm cursor-pointer">نمایش قیمت برای این نرخ ارز در صفحه قیمت های داده شده </label>
                                </div>
                            </div>
                        </div>

                        <div v-if="hasActions" class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <button type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="px-2 material-icons hover:cursor-pointer">save</i>
                                ذخیره سازی
                            </button>
                            <?php
                            if ($success) {
                            ?>
                                </br>
                                <p class="text-green-400"><?php echo $success ?></p>
                            <?php
                            }
                            ?>

                            <?php
                            if ($errors) {
                            ?>
                                <br>
                                <p class="text-orange-400"><?php echo $errors ?></p>
                            <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        <?php
        } ?>
    </div>
</div>
<?php
require_once('./views/Layouts/footer.php');
