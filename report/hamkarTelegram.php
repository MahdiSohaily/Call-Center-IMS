<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/TelegramPartnerController.php');
?>
<style>
    #success,
    #error {
        opacity: 0;
        transition: all 0.5s linear;
    }

    #message_content {
        direction: ltr;
    }

    .hidden {
        display: none;
    }

    #success_edit,
    #success_create {
        opacity: 0;
        transition: all 0.5s linear;
    }
</style>
<div class="grid md:grid-cols-7 gap-2 rtl">
    <div class="col-span-5 my-5 mx-2 bg-white rounded-lg shadow-lg h-full">
        <div class="flex rtl bg-violet-600  rounded-t-lg p-2">
            <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab1')">
                ارسال پیام
            </button>
            <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab3'); displayLocalData();">
                لیست مخاطبین
            </button>
            <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab2'); getContacts();">
                بروزرسانی لیست مخاطبین
            </button>
            <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab4'); displayCategories();">
                مدیریت دسته بندی ها
            </button>
        </div>
        <div class="p-4 rtl">
            <div id="tab1" class="tab-content">
                <h1 class="text-xl py-2">ارسال پیام به گروه مخاطبین</h1>
                <form action="post" id="message" class="flex flex-column">
                    <textarea required class="border border-2 p-3" name="message_content" id="message_content" cols="20" rows="3" placeholder="متن پیام خود را وارد کنید..."></textarea>
                    <div class="py-3">
                        <label class="cursor-pointer pl-5" for="honda">
                            <input type="checkbox" class="category_identifier" onclick="updateCategory(this)" name="honda" id="honda">
                            هیوندا
                        </label>

                        <label class="cursor-pointer pl-5" for="kia">
                            <input type="checkbox" class="category_identifier" onclick="updateCategory(this)" name="kia" id="kia">
                            کیا
                        </label>
                        <label class="cursor-pointer pl-5" for="chines">
                            <input type="checkbox" class="category_identifier" onclick="updateCategory(this)" name="chines" id="chines">
                            چینی
                        </label>
                    </div>
                </form>
                <div class="my-3 flex gap-3">
                    <div class="flex-1">
                        <table class="w-full">
                            <tbody>
                                <tr>
                                    <td class="py-4">هیوندا</td>
                                    <td class="py-4">
                                        <div id="honda_result" class="flex flex-wrap"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4">کیا</td>
                                    <td class="py-4">
                                        <div id="kia_result" class="flex flex-wrap"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4">چینی</td>
                                    <td class="py-4">
                                        <div id="chines_result" class="flex flex-wrap"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <span class="cursor-pointer rounded-md bg-green-400 w-32 text-white px-3 py-2 text-center" onclick="sendMessage()">ارسال پیام</span>
                <p id="success" class="text-green-700 my-3">پیام ارسال شد !!</p>
                <p id="error" class="text-red-700 my-3"> لطفا متن پیام و دریافت کنندگان پیام را مشخص کنید!</p>
            </div>
            <div id="tab2" class="tab-content hidden">
                <div class="flex justify-between">
                    <h1 class="text-xl py-2">مخاطبین اخیر تلگرام</h1>
                    <span class="flex items-center cursor-pointer text-white bg-violet-600 rounded-md px-3" onclick="hardRefresh()">
                        بروزرسانی
                        <i class="material-icons ">sync</i>
                    </span>
                </div>
                <div class="my-3">
                    <table class="table-fixed rtl min-w-full text-sm font-light">
                        <thead class="font-medium sticky dark:border-neutral-500 bg-violet-200">
                            <tr>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    شماره
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    نام
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    نام کاربری
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    پروفایل
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    هیوندا
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    کیا
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    چینی
                                </th>
                            </tr>
                        </thead>
                        <tbody id="contact" class="divide-y divide-gray-300">
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab3" class="tab-content hidden">
                <h1 class="text-xl py-2">لیست مخاطبین موجود در سیستم</h1>
                <div class="my-3">
                    <table class="table-fixed rtl min-w-full text-sm font-light">
                        <thead class="font-medium sticky dark:border-neutral-500 bg-violet-200">
                            <tr>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    شماره
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    نام
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    نام کاربری
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    پروفایل
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    هیوندا
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    کیا
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    چینی
                                </th>
                            </tr>
                        </thead>
                        <tbody id="initial_data" class="divide-y divide-gray-300">
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab4" class="tab-content hidden">
                <div class="flex justify-between px-1">
                    <h1 class="text-xl py-2">دسته بندی های موجود</h1>
                    <div>
                        <form action="#" id="save_category">
                            <input class="border border-2 p-2 mx-2" type="text" name="category_name" id="category_name" placeholder="اسم کتگوری...">
                            <button class="text-white bg-green-600 py-2 px-4 rounded-md" onclick="createCategoryForm()">
                                افزودن
                            </button>

                        </form>
                        <form action="#" id="edit_category" class="hidden">
                            <input type="hidden" id="category_id" value="" />
                            <input class="border border-2 p-2 mx-2" type="text" name="category_name" id="edit_category_name" placeholder="اسم کتگوری...">
                            <button class="text-white bg-green-600 py-2 px-4 rounded-md" onclick="editCategoryForm()">
                                ویرایش
                            </button>
                        </form>
                        <p id="success_create" class="text-green-500 text-xs p-2">دسته بندی با موفقیت ثبت شد.</p>
                        <p id="success_edit" class="text-green-500 text-xs p-2">دسته بندی با موفقیت ویرایش شد.</p>
                    </div>
                </div>
                <div class="my-3">
                    <table class="table-fixed rtl min-w-full text-sm font-light">
                        <thead class="font-medium sticky dark:border-neutral-500 bg-violet-200">
                            <tr>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    شماره
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    اسم دسته بندی
                                </th>
                                <th scope="col" class="text-gray-900 p-3 text-center">
                                    عملیات
                                </th>
                            </tr>
                        </thead>
                        <tbody id="category_data" class="divide-y divide-gray-300">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-2 my-5 mx-2 container rounded-lg shadow-lg bg-gray-900 text-white p-4">
        <h1 class="text-2xl font-bold mb-4">Console Log</h1>
        <div class="bg-black p-4 border rounded border-gray-600 h-60 overflow-y-auto" id="logContainer">
            <?php
            $logFile = './app/Controllers/telegram_partner_log.txt';
            $lines = [];

            // Open the log file for reading
            if ($file = fopen($logFile, 'r')) {
                // Read each line and keep track of the last 10 lines
                while (($line = fgets($file)) !== false) {
                    $lines[] = $line;
                    if (count($lines) > 10) {
                        array_shift($lines); // Remove the first line to keep 10 lines
                    }
                }
                fclose($file);
            }
            ?>
            <!-- PHP generates JSON lines as HTML data attributes -->
            <?php foreach ($lines as $line) : ?>
                <div class="mb-2 line" data-line="<?= htmlspecialchars(json_encode(json_decode($line), JSON_UNESCAPED_UNICODE)) ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Reverse and display the log lines on the client side
        document.addEventListener('DOMContentLoaded', function() {
            const logContainer = document.getElementById('logContainer');
            const lines = logContainer.querySelectorAll('.line');

            // Reverse and display the lines
            for (let i = lines.length - 1; i >= 0; i--) {
                const lineData = JSON.parse(lines[i].getAttribute('data-line'));
                const lineText = Object.entries(lineData)
                    .map(([key, value]) => `${key}: ${value}`)
                    .join('<br>');

                const lineElement = document.createElement('div');
                lineElement.innerHTML = `<span class="text-red-600">$</span> <code class="text-green-600">${lineText}</code>`;
                logContainer.appendChild(lineElement);

                if (i > 0) {
                    const separatorElement = document.createElement('p');
                    separatorElement.classList.add('text-green-600');
                    separatorElement.innerText = '----------------------------------------------------------------';
                    logContainer.appendChild(separatorElement);
                }
            }

            // Remove the original lines
            lines.forEach(line => line.remove());
        });
    </script>

</div>

<script>
    const partners_json = null;

    function openTab(tabId) {
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => {
            if (tab.id === tabId) {
                tab.classList.remove('hidden');
            } else {
                tab.classList.add('hidden');
            }
        });
    }
</script>
<script src="./public/js/telegramPartner.js?v=<?= rand() ?>"></script>
<?php
require_once('./views/Layouts/footer.php');
