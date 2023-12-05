<?php
require_once('./views/Layouts/header.php');
?>
<div class="rtl w-5/6 mx-auto bg-white p-5 rounded-lg d-flex">
    <div class="grow px-4">
        <!-- Korea section -->
        <div class="col-span-6 sm:col-span-4">
            <label for="code" class="block font-medium text-sm text-gray-700">
                قیمت کد های ارائه شده
            </label>
            <textarea readonly rows="10"  class="border-1 border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3"></textarea>
        </div>
    </div>
    <form class="grow px-4" target="_blank" action="giveOrderedPrice.php" method="post">
        <!-- Korea section -->
        <div class="col-span-6 sm:col-span-4">
            <label for="code" class="block font-medium text-sm text-gray-700">
                کدهای مدنظر
            </label>
            <textarea onchange="filterCode(this)" rows="10" id="code" name="code" required class="border-1 border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3" placeholder="لطفا کد های مود نظر خود را در خط های مجزا قرار دهید"></textarea>
        </div>

        <div v-if="hasActions" class="flex items-center py-3 text-right sm:rounded-bl-md sm:rounded-br-md">
            <button type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجو
            </button>
        </div>
    </form>

</div>
<script>
    function filterCode(element) {
        const message = element.value;
        if (!message) {
            return '';
        }

        const codes = message.split("\n");

        const filteredCodes = codes.map(function(code) {
            code = code.replace(/\[[^\]]*\]/g, '');
            const parts = code.split(/[:,]/, 2);
            const rightSide = (parts[1] || '').replace(/[^a-zA-Z0-9 ]/g, ' ').trim();
            return rightSide ? rightSide : code.replace(/[^a-zA-Z0-9 ]/g, ' ').trim();
        }).filter(Boolean);

        const finalCodes = filteredCodes.filter(function(item) {
            const data = item.split(" ");
            if (data[0].length > 4) {
                return item;
            }
        });

        const mappedFinalCodes = finalCodes.map(function(item) {
            const parts = item.split(' ');
            if (parts.length >= 2) {
                const partOne = parts[0];
                const partTwo = parts[1];
                if (!/[a-zA-Z]{4,}/i.test(partOne) && !/[a-zA-Z]{4,}/i.test(partTwo)) {
                    return partOne + partTwo;
                }
            }
            return parts[0];
        });

        const nonConsecutiveCodes = mappedFinalCodes.filter(function(item) {
            const consecutiveChars = /[a-zA-Z]{4,}/i.test(item);
            return !consecutiveChars;
        });

        console.log(nonConsecutiveCodes);

        element.value = nonConsecutiveCodes.map(function(item) {
            return item.split(' ')[0];
        }).join("\n") + "\n";
    }

    const searchCustomer = (val) => {
        let pattern = val;
        let superMode = 0;
        const resultBox = document.getElementById("search_result");
        pattern = pattern.replace(/-_\s/g, "");
        var params = new URLSearchParams();
        params.append('pattern', pattern);
        if (pattern.length > 3) {
            resultBox.classList.remove("hidden");
            resultBox.innerHTML = `<li class=''>
                                    <img class='block w-7 mx-auto h-auto' src='./public/img/loading.png' alt='loading'>
                                </li>`;

            axios.post("./app/Controllers/SearchCustomerController.php", params)
                .then(function(response) {
                    resultBox.innerHTML = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    };

    const selectCustomer = (element) => {
        id = element.getAttribute('data-customer-id');
        name = element.getAttribute('data-customer-name');
        family = element.getAttribute('data-customer-family');

        document.getElementById('customer_info').innerHTML = name + " " + family;
        document.getElementById('customer').value = name + " " + family;



        document.getElementById('target_customer').value = id;
        document.getElementById('search_result').classList.add("hidden");
    }

    // Get the current page URL query string
    const queryString = window.location.search;

    // Remove the leading '?phone=' from the query string
    const phoneValue = queryString.replace('?phone=', '');

    // Get the text area element
    const textArea = document.getElementById('code');

    // Set the phone value as the value of the text area with cursor in a new line
    if (phoneValue) {
        textArea.value += phoneValue + '\n';
    }
    textArea.focus();
</script>
<?php
require_once('./views/Layouts/footer.php');
