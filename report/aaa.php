<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .accordion-content {
            display: block;
        }

        .accordion-header {
            background-color: #f1f1f1;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="accordion">
        <div class="accordion-header">Section 1</div>
        <div class="accordion-content">
            Content of section 1
        </div>
        <div class="accordion-header">Section 2</div>
        <div class="accordion-content">
            Content of section 2
        </div>
        <div class="accordion-header">Section 3</div>
        <div class="accordion-content">
            Content of section 3
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const accordionHeaders = document.querySelectorAll(".accordion-header");

            accordionHeaders.forEach(header => {
                header.addEventListener("click", function() {
                    const content = this.nextElementSibling;
                    if (content.style.display === "block") {

                        content.style.display = "none";
                    } else {
                        content.style.display = "block";
                    }
                });
            });
        });
    </script>
</body>

</html>