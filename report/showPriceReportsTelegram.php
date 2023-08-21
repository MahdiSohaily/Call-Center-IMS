<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram</title>
</head>

<body>
    <script>
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Configure the request
        xhr.open("GET", "http://telegram.om-dienstleistungen.de/", true); // Change the URL to the external site's API endpoint

        // Set up a callback function to handle the response
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText;
                console.log("Response:", response);
                // You can do something with the response data here
            }
        };

        // Send the request
        xhr.send();
    </script>
</body>

</html>