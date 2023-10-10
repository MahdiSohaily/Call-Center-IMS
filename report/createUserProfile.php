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
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نام
                    </label>
                    <input name="partNumber" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="serial" type="text" />
                    <p class="mt-2"></p>
                </div>
                <!-- Price -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نام خانوادگی
                    </label>
                    <input name="price" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="price" type="text" />
                    <p class="mt-2"> </p>
                </div>
                <!-- Weight -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نام کاربری
                        <span class="text-red-500">*</span>
                    </label>
                    <input required name="weight" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="weight" type="text" />
                    <p class="mt-2"> </p>
                </div>
                <!-- Mobis -->
                <div class="col-span-6 sm:col-span-4 relative">
                    <label class="block font-medium text-sm text-gray-700">
                        رمزعبور
                        <span class="text-red-500">*</span>
                    </label>
                    <i onclick="togglePass(this)" class="material-icons cursor-pointer" style="position: absolute; left:5px; top: 50%">remove_red_eye</i>
                    <input required name="mobis" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="mobis" type="password" />
                </div>
                <!-- Korea section -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700">
                        نوعیت حساب کاربری
                        <span class="text-red-500">*</span>
                    </label>
                    <select type="status" class="border-1 p-2 text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" id="status">
                        <option value="1" class="text-sm">
                            تماس با بازار </option>

                        <option value="2" class="text-sm">
                            حسابداری </option>
                        <option value="2" class="text-sm">
                            انبار </option>

                        <option value="5" class="text-sm">
                            مدیر </option>

                    </select>
                    <p class="mt-2"> </p>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <button class="button bg-green-700 text-white py-2 px-3 rounded-lg hover:bg-green-600" type="submit">ایجاد حساب کاربری</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePass(element) {
        const target = element.nextElementSibling;
        const inputType = target.type;

        if (inputType === 'password') {
            target.type = 'test';
            return;
        }

        target.type = 'password';
    }
</script>

<?php
require_once('./views/Layouts/footer.php');
