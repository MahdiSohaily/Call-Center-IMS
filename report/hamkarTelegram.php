<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GoodController.php');
?>
<div class="max-w-7xl my-5 mx-auto bg-white rounded-lg shadow-lg ">
    <div class="flex rtl bg-violet-600  rounded-t-lg p-2">
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab1')">لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab2')">بروزرسانی لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-300 ml-2 focus:outline-none" onclick="openTab('tab3')">ارسال پیام</button>
    </div>
    <div class="p-4 rtl">
        <div id="tab1" class="tab-content">
            <h1 class="text-xl py-2">لیست مخاطبین موجود در سیستم</h1>
            <div class="bg-indigo-100">
                content
            </div>
        </div>
        <div id="tab2" class="tab-content hidden">
            <h1 class="text-xl py-2">مخاطبین اخیر تلگرام</h1>
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
<script>
    const contact = document.getElementById('results_new');
    contact.innerHTML = `
            <tr>
                <td colspan="7" class="py-5">
                    <img class=' block w-10 mx-auto h-auto' src="./public/img/loading.png" />
                </td>
            </tr>
            `;
    axios.post("http://localhost/telegram/")
        .then(function(response) {

            displayData(response.data)
        })
        .catch(function(error) {

        });


    function displayData(data) {
        let template = ``;
        let counter = 1;
        for (let user of data) {
            template += `
            <tr class="even:bg-indigo-100" data-user=" ${user.chat_id}">
                <td class="p-2 text-center"> ${counter}</td>
                <td class="p-2 text-center"> ${user.title}</td>
                <td class="p-2 text-center" style="text-decoration:ltr"> ${user.username}</td>
                <td class="p-2 text-center"> <img class="userImage mx-2 mx-auto d-block" src='${user.profile_path}' /> </td>
                <td class="p-2 text-center"> <input type="checkbox" name="honda" /> </td>
                <td class="p-2 text-center"> <input type="checkbox" name="kia" /> </td>
                <td class="p-2 text-center"> <input type="checkbox" name="chaines" /> </td>
            </tr>`;
            counter += 1;
        }
        contact.innerHTML = template;
    }

    const tabs = document.querySelectorAll('[data-tab]');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const target = document.querySelector(tab.dataset.target);
            const panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => pane.classList.add('hidden'));
            target.classList.remove('hidden');
        });
    });
</script>
<?php
require_once('./views/Layouts/footer.php');
