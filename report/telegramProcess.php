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
<div class="full">
    <h1 class="text-6xl text-gray-600">لطفا صبور باشید</h1>
    <br>
    <img src="./public/img/loading.png" class="w-20">
</div>
<script>
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open("GET", "http://telegram.om-dienstleistungen.de/", true); // Change the URL to the external site's API endpoint

    // Set up a callback function to handle the response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            if (response.length > 2) {
                // Create a hidden input field to store the JSON data
                const jsonInput = document.createElement('input');
                jsonInput.type = 'hidden';
                jsonInput.name = 'jsonData';
                jsonInput.value = JSON.stringify(JSON.parse(response));

                // Append the input field to the form
                const form = document.createElement('form');
                form.method = 'post';
                form.action = './showPriceReportsTelegram.php'; // Leave empty to post to the same page
                form.appendChild(jsonInput);

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        }
    };

    // Send the request
    xhr.send();
</script>
<?php

require_once('./views/Layouts/footer.php');
