<?php
require_once('./views/Layouts/header.php');
?>
<div class="rtl w-5/6 mx-auto py-20 sm:px-6 lg:px-8 bg-white rounded-lg shadow-sm mt-11 d-flex">
    <div class="grow px-4 mb-4">
        <!-- Korea section -->
        <div class="col-span-6 sm:col-span-4">
            <label for="code" class="block font-medium text-sm text-gray-700">
                قیمت کد های ارائه شده
            </label>
            <textarea onclick="copyOperation(this.value)" id="results_box" readonly rows="10" class="border border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3"></textarea>
        </div>
    </div>
    <form id="partNumbers" class="grow px-4" target="_blank" action="giveOrderedPrice.php" method="post">
        <!-- Korea section -->
        <div class="col-span-6 sm:col-span-4">
            <label for="code" class="block font-medium text-sm text-gray-700">
                کدهای مدنظر
            </label>
            <textarea onchange="filterCode(this)" rows="10" id="code" name="code" required class="border border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3" placeholder="لطفا کد های مود نظر خود را در خط های مجزا قرار دهید"></textarea>
        </div>

        <div v-if="hasActions" class="flex items-center py-3 text-right sm:rounded-bl-md sm:rounded-br-md">
            <button type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجو
            </button>
        </div>
    </form>
</div>
<div id="copied_message" class="flex justify-center text-green-500 p-4">
    <span>
        ! قیمت ها موفقانه کپی شد
    </span>

</div>
<script>
    document.getElementById('copied_message').style.display = 'none';
    const textArea = document.getElementById('code');
    const form = document.getElementById('partNumbers');
    const results_box = document.getElementById('results_box');
    textArea.focus();

    function filterCode(element) {
        const message = element.value;
        if (!message) {
            return '';
        }

        const codes = message.split("\n");

        const filteredCodes = codes.map(function(code) {
            code = code.replace(/\[[^\]]*\]/g, '');

            const parts = code.split(/[:,]/, 2);

            // Check if parts[1] contains a forward slash
            if (parts[1] && parts[1].includes('/')) {
                // Remove everything after the forward slash
                parts[1] = parts[1].split('/')[0];
            }

            const rightSide = (parts[1] || '').replace(/[^a-zA-Z0-9 ]/g, '').trim();

            return rightSide ? rightSide : code.replace(/[^a-zA-Z0-9 ]/g, '').trim();
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

        element.value = nonConsecutiveCodes.map(function(item) {
            return item.split(' ')[0];
        }).join("\n") + "\n";
    }


    form.addEventListener("submit", function(event) {
        event.preventDefault();
        const code = document.getElementById('code').value;
        const params = new URLSearchParams();
        params.append('codes', code);
        results_box.value = '';
        axios
            .post("./app/Controllers/getPartNumbersPrice.php", params)
            .then(function(response) {
                data = response.data;

                for (const item of data) {
                    results_box.value += item;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    })

    function copyOperation(text) {
        const textArea = document.getElementById('results_box');
        textArea.value = text;
        textArea.select();
        document.execCommand('copy');
        document.getElementById('copied_message').style.display = 'flex';
        setTimeout(function() {
            document.getElementById('copied_message').style.display = 'none';
        }, 2000);
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
