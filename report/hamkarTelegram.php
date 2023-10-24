<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/TelegramPartnerController.php');
?>
<div class="max-w-7xl my-5 mx-auto bg-white rounded-lg shadow-lg ">
    <div class="flex rtl bg-violet-600  rounded-t-lg p-2">
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab1')">لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab2'); getContacts()">بروزرسانی لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab3')">ارسال پیام</button>
    </div>
    <div class="p-4 rtl">
        <div id="tab1" class="tab-content">
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
                    <tbody class="divide-y divide-gray-300">
                        <?php $index = 1;
                        foreach ($current_partners as $partner) : ?>
                            <tr class="even:bg-indigo-100" data-chat="<?= $partner['chat_id'] ?>" data-name=" <?= $partner['name'] ?>" data-username="<?= $partner['username'] ?>" data-profile="<?= $partner['profile'] ?>">
                                <td class="p-2 text-center"> <?= $index; ?> </td>
                                <td class="p-2 text-center"> <?= $partner['name'] ?></td>
                                <td class="p-2 text-center" style="text-decoration:ltr"> <?= $partner['username'] ?></td>
                                <td class="p-2 text-center"> <img class="userImage mx-2 mx-auto d-block" src='<?= $partner['profile'] ?>' /> </td>
                                <td class="p-2 text-center"> <input class="cursor-pointer <?= 'user-' . $partner['chat_id'] ?> " data-user="<?= $partner['chat_id'] ?>" type="checkbox" name="honda" onclick="updateContactGroup(this)" /> </td>
                                <td class="p-2 text-center"> <input class="cursor-pointer <?= 'user-' . $partner['chat_id'] ?> " data-user="<?= $partner['chat_id'] ?>" type="checkbox" name="kia" onclick="updateContactGroup(this)" /> </td>
                                <td class="p-2 text-center"> <input class="cursor-pointer <?= 'user-' . $partner['chat_id'] ?> " data-user="<?= $partner['chat_id'] ?>" type="checkbox" name="chaines" onclick="updateContactGroup(this)" /> </td>
                            </tr>
                        <?php
                            $index += 1;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
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
                    <tbody id="results_new" class="divide-y divide-gray-300">
                    </tbody>
                </table>
            </div>
        </div>
        <div id="tab3" class="tab-content hidden">
            <h1 class="text-xl py-2">ارسال پیام به گروه مخاطبین</h1>
            <div class="bg-indigo-100">
                content
            </div>
        </div>
    </div>
</div>

<script>
    const contact = document.getElementById('results_new');
    let isLoadedTelegramContacts = false;

    function updateContactGroup(element) {
        // the target URL to send the ajax request
        const address = "./app/Controllers/TelegramPartnerControllerAjax.php";

        const user = element.getAttribute("data-user");

        const authorityList = document.querySelectorAll(".user-" + user);

        const data = {};

        for (const node of authorityList) {
            const authority = node.getAttribute("name");
            const isChecked = node.checked;
            data[authority] = isChecked;
        }

        const params = new URLSearchParams();
        params.append("operation", "update");
        params.append("user", user);
        params.append("data", JSON.stringify(data));

        axios.post(address, params)
            .then(function(response) {
                console.log(response.data);
            })
            .catch(function(error) {

            });
    }


    function hardRefresh() {
        isLoadedTelegramContacts = false;
        getContacts();
    }

    function getContacts() {
        if (!isLoadedTelegramContacts) {
            contact.innerHTML = `
            <tr>
                <td colspan="7" class="py-5">
                    <img class=' block w-10 mx-auto h-auto' src="./public/img/loading.png" />
                </td>
            </tr>
            `;
            axios.post("http://localhost/telegram/")
                .then(function(response) {

                    displayTelegramData(response.data);
                    isLoadedTelegramContacts = true;
                })
                .catch(function(error) {

                });
        }
    }

    function displayTelegramData(data) {
        let template = ``;
        let counter = 1;
        for (let user of data) {
            template += `
            <tr class="even:bg-indigo-100" 
                data-chat=" ${user.chat_id}"
                data-name=" ${user.title}"
                data-username=" ${user.username}"
                data-profile=" ${user.profile_path}"
                >
                <td class="p-2 text-center"> ${counter}</td>
                <td class="p-2 text-center"> ${user.title}</td>
                <td class="p-2 text-center" style="text-decoration:ltr"> ${user.username}</td>
                <td class="p-2 text-center"> <img class="userImage mx-2 mx-auto d-block" src='${user.profile_path}' /> </td>
                <td class="p-2 text-center"> <input class="cursor-pointer" type="checkbox" name="honda" /> </td>
                <td class="p-2 text-center"> <input class="cursor-pointer" type="checkbox" name="kia" /> </td>
                <td class="p-2 text-center"> <input class="cursor-pointer" type="checkbox" name="chaines" /> </td>
            </tr>`;
            counter += 1;
        }
        contact.innerHTML = template;
    }

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
<?php
require_once('./views/Layouts/footer.php');
