<?php
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GivenPriceControllerTelegram.php');
require_once './utilities/helper.php';
?>
<style>
    .full {
        height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>
<div id="full" class="full">

</div>
<script>
    const container = document.getElementById('full');

    container.innerHTML = `
                            <h1 class="text-6xl text-gray-600">لطفا صبور باشید</h1>
                            <br>
                            <img src="./public/img/loading.png" class="w-20">
                            `;

    axios
        .get("http://telegram.om-dienstleistungen.de/")
        .then(function(response) {
            console.log(response.data);

            if (typeof response.data === 'object' && Object.keys(response.data).length !== 0) {
                console.log('Here we are');
                try {
                    const jsonInput = document.createElement('input');
                    jsonInput.type = 'hidden';
                    jsonInput.name = 'jsonData';
                    jsonInput.value = JSON.stringify(response.data);

                    // Append the input field to the form
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = './showPriceReportsTelegram.php'; // Leave empty to post to the same page
                    form.appendChild(jsonInput);

                    // Append the form to the body and submit it
                    document.body.appendChild(form);
                    form.submit();
                } catch (error) {
                    console.log(error);
                }
            } else {
                container.innerHTML = `
                            <h1 class="text-4xl text-gray-600">پیام جدیدی موجود نیست</h1>
                            <br>
                            <h3 class="text-3xl text-gray-600">لطفا بعدا تلاش نمایید</h3>
                            `;
            }
        })
        .catch(function(error) {});
</script>
<?php

require_once('./views/Layouts/footer.php');
