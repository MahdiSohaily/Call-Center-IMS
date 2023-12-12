<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
$sql = "SELECT * FROM cars";
$cars = $conn->query($sql);

$status_sql = "SELECT * FROM status";
$status = $conn->query($status_sql);
?>
<style>
    fieldset {
        background-color: lightgray;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    legend {
        font-size: 18px;
        font-weight: bold;
    }

    .bill_icon {
        width: 30px;
        height: 30px;
        max-width: 30px !important;
        cursor: pointer;
    }
</style>
<div style="min-height: 500px !important;" class="rtl h-1/3 grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8  px-4 mb-4">
    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/customer.svg" alt="customer icon">
                انتخاب مشتری
            </h2>
        </div>
        <div class="flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); search(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
        </div>
        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        <div id="search_result" class="p-3">
            <!-- Search Results are going to be appended here -->
        </div>
    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/barcode.svg" alt="customer icon">
                انتخاب کد فنی
            </h2>
        </div>
        <div class="flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); search(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder="کد فنی قطعه مورد نظر را وارد کنید..." />
        </div>
        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        <p id="select_box_error" class="px-3 tiny-text text-red-500 hidden">
            لیست اجناس انتخاب شده برای افزودن به رابطه خالی بوده نمیتواند!
        </p>
        <div id="selected_box" class="p-3">
            <!-- selected items are going to be added here -->
        </div>
    </div>

    <div class="bg-white min-h-full rounded-lg shadow-md">
        <div class="p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <img src="./public/img/inventory.svg" alt="inventory icon">
                انتخاب کالای موجود
            </h2>
        </div>

        <div class="flex justify-center px-3">
            <input onkeyup="convertToEnglish(this); search(this.value)" type="text" name="serial" id="serial" class="rounded-md py-3 px-3 w-full border-1 text-sm border-gray-300 focus:outline-none text-gray-500" min="0" max="30" placeholder=" اسم کامل مشتری را وارد نمایید ..." />
        </div>

        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="p-3">
        </div>
        <div id="output"></div>
    </div>
</div>
<div class="rtl h-70 px-4">
    <div class="bg-white rounded-lg shadow-md py-4">
        <div class="container mx-auto">
            <table class="min-w-full border border-gray-300 text-gray-400">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="py-2 px-4 border-b text-white">ردیف</th>
                        <th class="py-2 px-4 border-b text-white">کد فنی</th>
                        <th class="py-2 px-4 border-b text-white">نام قطعه</th>
                        <th class="py-2 px-4 border-b text-white"> تعداد</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت کل</th>
                        <th class="py-2 px-4 border-b w-12 h-12 font-medium">
                            <img class="bill_icon" src="./public/img/setting.svg" alt="settings icon">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows -->
                    <tr>
                        <td class="py-2 px-4 border-b">Data 1</td>
                        <td class="py-2 px-4 border-b">Data 2</td>
                        <td class="py-2 px-4 border-b">Data 3</td>
                        <td class="py-2 px-4 border-b">Data 4</td>
                        <td class="py-2 px-4 border-b">Data 5</td>
                        <td class="py-2 px-4 border-b">Data 6</td>
                        <td class="py-2 px-4 border-b w-12 h-12 font-medium">
                            <img class="bill_icon" src="./public/img/subtract.svg" alt="subtract icon">
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    // Container Global Variables
    let serial = null;
    let pattern_id = null;
    selected_goods = [];
    let relation_active = false;

    //Global Elements Container 
    const selected_box = document.getElementById('selected_box');
    const resultBox = document.getElementById("search_result");
    const error_message = document.getElementById('select_box_error');
    const duplicate_relation = document.getElementById('duplicate_relation');
    const form_success = document.getElementById('form_success');
    const form_error = document.getElementById('form_error');

    // search for goods to define their relationship
    function search(pattern) {
        serial = pattern;
        if (pattern.length > 6) {
            error_message.classList.add('hidden');
            duplicate_relation.classList.add('hidden');
            pattern = pattern.replace(/[^a-zA-Z0-9\s]/g, "");
            pattern = pattern.replace(/-/g, "");
            pattern = pattern.replace(/_/g, "");

            resultBox.innerHTML = `<tr class=''>
                                        <div class='w-full h-96 flex justify-center items-center'>
                                            <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                        </div>
                                    </tr>`;
            var params = new URLSearchParams();
            params.append('search_goods_for_relation', 'search_goods_for_relation');
            params.append('pattern', pattern);

            if (pattern.length > 6) {
                axios.post("./app/Controllers/RelationshipAjaxController.php", params)
                    .then(function(response) {
                        resultBox.innerHTML = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            } else {
                resultBox.innerHTML = "کد فنی وارد شده فاقد اعتبار است";
            }
        } else {
            resultBox.innerHTML = "";
        }
    };

    // A function to add a good to the relation box
    function add(element) {
        duplicate_relation.classList.add('hidden');
        const id = element.getAttribute("data-id");
        const partNumber = element.getAttribute("data-partnumber");
        selected_goods = selected_goods.filter((good) => {
            return good.id !== id;
        });

        selected_goods.push({
            id: id,
            partNumber: partNumber
        });
        error_message.classList.add('hidden');
        remove(id);
        displaySelectedGoods();
    };

    // A function to remove added goods from relation box
    function remove(id) {
        const item = document.getElementById("search-" + id);
        if (item) {
            item.remove();
        }
    }

    // A function to remove an specific item from selected items list
    function remove_selected(id) {
        selected_goods = selected_goods.filter((item) => {
            return item.id != id;
        });
        displaySelectedGoods();
    };

    //A function to clear all selected items
    function clearAll() {
        selected_goods = [];
        relation_active = false;
        displaySelectedGoods();
        duplicate_relation.classList.add('hidden');


        var form = document.getElementById('myForm');

        for (var i = 0; i < form.elements.length; i++) {
            var element = form.elements[i];

            // Check if the element is an input element (text, password, email, etc.)
            if (element.tagName === 'INPUT' && element.type !== 'button' && element.type !== 'submit') {
                element.value = ''; // Set the value to an empty string
            } else if (element.tagName === 'TEXTAREA') {
                element.value = ''; // Clear textarea values
            } else if (element.tagName === 'SELECT') {
                element.selectedIndex = -1; // Clear selected option in a dropdown
            }
        }

        $('#cars').select2(null);
    }

    // A function to display the selected goods in the relation box
    function displaySelectedGoods() {
        let template = '';
        for (const good of selected_goods) {
            template += `
            <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 border-gray-300">
                <p class="text-sm font-semibold text-gray-600">
                    ` + good.partNumber + `
                </p>
                    <i data-id="` + good.id + `" data-partNumber="` + good.partNumber + `" onclick="remove_selected(` +
                good.id + `)"
                            class="material-icons add text-red-600 cursor-pointer rounded-circle hover:bg-gray-200">do_not_disturb_on
                    </i>
                </div>
            `;
        }
        selected_box.innerHTML = template;
    }

    // this function is used to get all the selected items using Javascript and push them into an array
    // and prepare them for sending to the server
    function getSelectedItems(id) {
        let selected = [];
        for (var option of document.getElementById(id).options) {
            if (option.selected) {
                selected.push(option.value);
            }
        }

        return selected;
    }

    // A function to create the relationship
    function createRelation() {
        duplicate_relation.classList.add('hidden');
        // Accessing the form fields to get thier value for an ajax store operation
        const relation_name = document.getElementById('relation_name').value;
        const mode = document.getElementById('mode').value;
        const price = null;
        const cars = getSelectedItems('cars');
        const status = document.getElementById('status').value;
        const description = document.getElementById('description').value;
        const original = document.getElementById('original').value;
        const fake = document.getElementById('fake').value;

        const original_all = document.getElementById('original_all').value;
        const fake_all = document.getElementById('fake_all').value;

        // Defining a params instance to be attached to the axios request
        const params = new URLSearchParams();
        params.append('store_relation', 'store_relation');
        params.append('relation_name', relation_name);
        params.append('price', price);
        params.append('cars', JSON.stringify(cars));
        params.append('status', status);
        params.append('description', description);
        params.append('original', original);
        params.append('fake', fake);

        params.append('original_all', original_all);
        params.append('fake_all', fake_all);

        // Side effects data
        params.append('mode', mode);
        params.append('pattern_id', pattern_id);
        params.append('selected_goods', JSON.stringify(selected_goods));
        params.append('serial', serial);


        if (selected_goods.length > 0) {
            axios.post("./app/Controllers/RelationshipAjaxController.php", params)
                .then(function(response) {
                    if (response.data == true) {
                        form_success.classList.remove('hidden');
                        setTimeout(() => {
                            form_success.classList.add('hidden');
                            location.reload();
                        }, 2000)
                    } else {
                        form_error.classList.remove('hidden');
                        setTimeout(() => {
                            form_error.classList.add('hidden');
                            location.reload();
                        }, 2000)
                    }
                })
                .catch(function(error) {

                });
        } else {
            error_message.classList.remove('hidden');
        }


    }

    // A function to load all the relationships for the selected relationship
    function load(element) {
        const pattern = element.getAttribute("data-pattern");
        if (!relation_active) {
            duplicate_relation.classList.add('hidden');
            relation_active = true;
            pattern_id = pattern;

            const params = new URLSearchParams();
            params.append('load_relation', 'load_relation');
            params.append('pattern', pattern_id);

            axios.post("./app/Controllers/RelationshipAjaxController.php", params)
                .then(function(response) {
                    // VALUES OF THE ORIGINAL GOODS FOR SPECIFIC INVENTORY
                    let original = 0;
                    let fake = 0;

                    // VALUES OF THE GOODS FOR THE OVER ALL INVENTORIES
                    let original_all = 0;
                    let fake_all = 0;

                    if (response.data[0] !== null) {
                        original = response.data[0]['original'];
                        fake = response.data[0]['fake'];

                        original_all = response.data[0]['original_all'];
                        fake_all = response.data[0]['fake_all'];
                    }
                    document.getElementById('original').value = original;
                    document.getElementById('fake').value = fake;

                    document.getElementById('original_all').value = original_all;
                    document.getElementById('fake_all').value = fake_all;

                    push_data(response.data[1]);
                    displaySelectedGoods();
                    load_pattern_ifo(pattern_id);
                })
                .catch(function(error) {

                });
        } else {
            duplicate_relation.classList.remove('hidden');
        }
    }

    //This function helps to add all relations of a relationship into the selected items list
    const push_data = (data) => {
        for (const item of data) {
            remove(item.id);
            selected_goods.push({
                id: item.id,
                partNumber: item.partNumber
            });
        }
    };

    // This function is used to load an existing relationship information and fill out form fields with information
    const load_pattern_ifo = (id) => {

        // Accessing the form fields to get thier value for an ajax store operation
        const relation_name = document.getElementById('relation_name');
        const mode = document.getElementById('mode');
        const price = document.getElementById('price');
        const cars = getSelectedItems('cars');
        const status = document.getElementById('status');
        const description = document.getElementById('description');

        const params = new URLSearchParams();
        params.append('load_pattern_ifo', 'load_pattern_ifo');
        params.append('pattern', pattern_id);

        axios.post("./app/Controllers/RelationshipAjaxController.php", params)
            .then(function(response) {
                const pattern_info = (response.data.pattern);
                const pattern_info_cars = (response.data.cars);

                relation_name.value = pattern_info.name;
                mode.value = 'update';
                // price.value = pattern_info.price;
                description.value = pattern_info.description;

                setSelectedItems('cars', pattern_info_cars);
                setSelectedItems('status', pattern_info.status_id);

                $('#cars').select2(pattern_info_cars);

            })
            .catch(function(error) {
                console.log(error);
            });
    };

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
            matcher: matchCustom
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
