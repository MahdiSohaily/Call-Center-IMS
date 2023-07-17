<?php
require_once('./views/Layouts/header.php');
?>
<div class="rtl max-w-2xl mx-auto py-20 sm:px-6 lg:px-8 bg-white rounded-lg shadow-sm mt-11">

    <form target="_blank" action="giveOrderedPrice.php" method="post">
        <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
        <input type="text" name="user" value="<?php echo  $_SESSION["id"] ?>" hidden>
        <div class="">
            <div class="col-span-12 sm:col-span-4 mb-3 relative">
                <label for="customer">
                    مشتری مد نظر
                    :
                    ( <span id="customer_info">کاربر دستوری</span> )
                </label>
                <input onkeyup="search(this.value)" type="text" name="customer" id="customer" class="p-2 border-1 text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                <ul id="search_result" style="min-height: 150px;" class=" border bg-white rounded-lg my-2 shadow-md p-2 absolute min-w-full">
                    <li title="انتخاب مشتری" class="odd:bg-indigo-100 rounded-sm p-2 hover:cursor-pointer flex justify-between">
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
                <textarea rows="7" id="code" name="code" required class="border-1 border-gray-300 ltr mt-1 shadow-sm block w-full rounded-md border-gray-300 p-3" placeholder="لطفا کد های مود نظر خود را در خط های مجزا قرار دهید"></textarea>
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
    const search = (val) => {
        let pattern = val;
        let superMode = 0;
        const resultBox = document.getElementById("search_result");

        pattern = pattern.replace(/-_\s/g, "");

        resultBox.innerHTML = `<li class=''>
                                    <img class='block w-7 mx-auto h-auto' src='./public/img/loading.png' alt='loading'>
                                </li>`;
        var params = new URLSearchParams();
        params.append('pattern', pattern);

        axios.post("./app/Controllers/SearchCustomerController.php", params)
            .then(function(response) {
                resultBox.innerHTML = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    };
</script>
<?php
require_once('./views/Layouts/footer.php');
