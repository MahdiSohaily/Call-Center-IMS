<?php
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GivenPriceController.php');
require_once './utilities/helper.php';
?>

<script>
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open("GET", "http://telegram.om-dienstleistungen.de/", true); // Change the URL to the external site's API endpoint

    // Set up a callback function to handle the response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            console.log(response.length);
            if (response.length > 2) {
                console.log('we are sending');
                // Create a hidden input field to store the JSON data
                const jsonInput = document.createElement('input');
                jsonInput.type = 'hidden';
                jsonInput.name = 'jsonData';
                jsonInput.value = JSON.stringify(response);

                // Append the input field to the form
                const form = document.createElement('form');
                form.method = 'post';
                form.action = ''; // Leave empty to post to the same page
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
if (isset($_POST['jsonData'])) {
    $jsonData = $_POST['jsonData'];

    // Decode the JSON data
    $decodedData = json_decode($jsonData, true); // Decodes as an associative array

    // Process the decoded JSON data
    // ...

    // Return a response (optional)
    $response = "Data received and processed!";
    echo $response;
}
require_once('./views/Layouts/footer.php');
