<?php
require_once('./views/Layouts/header.php');
$sql = "SELECT * FROM callcenter.customer";
$cars = mysqli_query($conn, $sql);
?>
<div class="rtl max-w-2xl mx-auto py-20 sm:px-6 lg:px-8 bg-white rounded-lg shadow-sm mt-11">

    <form target="_blank" action="giveOrderedPrice.php" method="post">
        <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
        <input type="text" name="user" value="<?php echo  $_SESSION["id"] ?>" hidden>
        <div class="">
            <div class="col-span-12 sm:col-span-4 mb-3">
                <label for="cars">
                    مشتری مد نظر
                </label>
                <select name="customer" id="cars" type="cars" multiple class="p-2 border-1 text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <?php
                    if (mysqli_num_rows($cars) > 0) {
                        while ($item = mysqli_fetch_assoc($cars)) {
                    ?>
                            <option value="<?php echo $item['id'] ?>" class="flex justify-between text-sm">
                                <span style="direction: rtl;"><?php echo $item['name'] . ' ' . $item['family'] ?></span>
                                <span style="direction: rtl;"><?php echo $item['phone']  ?></span>
                            </option>

                    <?php }
                    } ?>
                </select>
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
    // This function helps to set the selected items from select elements when we load a predefined relationship
    function setSelectedItems(id, cars) {
        for (var option of document.getElementById(id).options) {
            if (cars.includes(option.value)) {
                option.selected = 'true';
                option.style.color = 'red';
            }
        }
    }

    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('#cars').select2({
            matcher: matchCustom,
            maximumSelectionLength: 1,
            formatSelectionTooBig: function(limit) {
                return 'شما باید تنها یک مورد انتخاب کنید';
            }
        });

    });

    // This function helps to display only the matching results when user types a keyword (Slecte 2 plugin)
    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
            return null;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (data.text.indexOf(params.term) > -1) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.text += '';

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }
</script>
<?php
require_once('./views/Layouts/footer.php');
