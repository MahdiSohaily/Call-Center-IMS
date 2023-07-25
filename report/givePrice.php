<?php
require_once('./views/Layouts/header.php');
?>
<div class="rtl max-w-2xl mx-auto py-20 sm:px-6 lg:px-8 bg-white rounded-lg shadow-sm mt-11">

    <form target="_blank" action="giveOrderedPrice.php" method="post">
        <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
        <input type="text" name="user" value="<?php echo  $_SESSION["id"] ?>" hidden>
        <input type="text" name="customer" value="1" id="target_customer" hidden>
        <div class="">
            <div class="col-span-12 sm:col-span-4 mb-3 relative">
                <label for="customer">
                    مشتری مد نظر
                    :
                    ( <span id="customer_info">کاربر دستوری</span> )
                </label>
                <input onkeyup="searchCustomer(this.value)" type="text" name="search_input" id="customer" class="p-2 border-1 text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                <ul id="search_result" style="max-height: 350px; overflow: auto;" class="hidden border bg-white rounded-lg my-2 shadow-md p-2 absolute min-w-full">
                    <li onclick="selectCustomer()" title="انتخاب مشتری" class="odd:bg-indigo-100 rounded-sm p-2 hover:cursor-pointer flex justify-between">
                        <span>کاربر دستوری</span>
                        <span style="direction: ltr;">+939333346016</span>
                    </li>
                </ul>
            </div>
            <!-- Korea section -->
            <div class="col-span-6 sm:col-span-4">
                <label for="code" class="block font-medium text-sm text-gray-700">
                    کدهای مدنظر
                </label>
                <textarea onkeyup="filterCode(this)" rows="7" id="code" name="code" required class="border-1 border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3" placeholder="لطفا کد های مود نظر خود را در خط های مجزا قرار دهید"></textarea>
            </div>
        </div>

        <div v-if="hasActions" class="flex items-center justify-end py-3 text-right sm:rounded-bl-md sm:rounded-br-md">
            <button type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجو
            </button>
        </div>
    </form>
</div>
<script>
    $('#code').keyup(delay(function(e) {
        console.log('Time elapsed!', this.value);
    }, 500));

    function filterCode(element) {
        if (element.value) {
            var explodedCodes = element.value.split("\n");

            var result = explodedCodes.map(function(code) {
                if (code.length > 0) {
                    var stringWithBracketsAndColon = code;

                    // Remove everything between square brackets
                    var removedText = stringWithBracketsAndColon.replace(/\[[^\]]*\]/g, "");

                    if (removedText.includes(":")) {
                        var parts = removedText.split(":");
                        var rightSide = parts[1].trim();
                        rightSide = rightSide.replace(/[^a-zA-Z0-9]/g, "");
                        return rightSide;
                    } else {
                        return removedText.replace(/[^a-zA-Z0-9]/g, "");
                    }
                }
            });
            const regex = /[a-zAZ]{4}/;
            result = result.filter((item) => {
                return item.length > 7 && !regex.test(item);
            });

            element.value = result.join("\n");
        }
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
