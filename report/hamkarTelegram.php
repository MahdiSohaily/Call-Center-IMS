<?php
require_once('./config/config.php');
require_once('./database/connect.php');
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GoodController.php');
?>
<div class="max-w-7xl my-5 mx-auto bg-white rounded-lg shadow-lg ">
    <div class="flex rtl bg-gray-600  rounded-t-lg p-2">
        <button class="px-4 py-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-300 ml-2 focus:outline-none" onclick="openTab('tab1')">لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-300 ml-2 focus:outline-none" onclick="openTab('tab2')">بروزرسانی لیست مخاطبین</button>
        <button class="px-4 py-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-300 ml-2 focus:outline-none" onclick="openTab('tab3')">ارسال پیام</button>
    </div>
    <div class="p-4 rtl">
        <div id="tab1" class="tab-content">
            <p>Content for Tab 1 goes here.</p>
        </div>
        <div id="tab2" class="tab-content hidden">
            <p>Content for Tab 2 goes here.</p>
        </div>
        <div id="tab3" class="tab-content hidden">
            <p>Content for Tab 2 goes here.</p>
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
    // axios.post("http://localhost/telegram/")
    //     .then(function(response) {
    //         location.reload();
    //     })
    //     .catch(function(error) {

    //     });

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
