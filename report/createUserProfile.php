<?php
require_once('./views/Layouts/header.php');
?>
<div class="bg-white rounded-lg shadow-md m-5 w-1/2 mx-auto p-5">
    <div class="rtl flex items-center justify-between p-3">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="material-icons font-semibold text-cyan-600">person_add</i>
            ایجاد حساب کاربری
        </h2>
    </div>
    <div class="rtl">
        <form action="" method="post">

            <div class="grid grid-cols-6 gap-6">
                <input type="text" name="form" value="create" hidden>
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نام
                    </label>
                    <input name="partNumber" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" required id="serial" type="text" />
                    <p class="mt-2"></p>
                </div>

                <!-- Price -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نام خانوادگی
                    </label>
                    <input name="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="price" type="text" />
                    <p class="mt-2"> </p>
                </div>
                <!-- Weight -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        وزن جنس
                    </label>
                    <input name="weight" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="weight" type="text" />
                    <p class="mt-2"> </p>
                </div>
                <!-- Mobis -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        موبیز
                    </label>
                    <input name="mobis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="mobis" type="text" />
                    <p class="mt-2"> </p>
                </div>
                <!-- Korea section -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        کوریا
                    </label>
                    <input name="korea" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="korea" v-model="form.korea" type="text" />
                    <p class="mt-2"> </p>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require_once('./views/Layouts/footer.php');
