<?php
require_once './layout/heroHeader.php';
$sql = "SELECT e.*, u.id As user_id, s.name AS seller_name
FROM estelam AS e
JOIN yadakshop1402.users AS u ON e.user = u.id
JOIN yadakshop1402.seller AS s ON e.seller = s.id
ORDER BY e.time DESC
LIMIT 600";

// Prepare the statement
$stmt = $pdo->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


function displayTimePassed($timePassed)
{
    $create = date($timePassed);
    $now = new DateTime(); // current date time
    $date_time = new DateTime($create); // date time from string

    $current_day = date_format($now, 'd');
    $data_day = date_format($date_time, 'd');

    $diff = $current_day - $data_day;

    if ($diff == 0) {
        $text = "امروز";
    } else {
        $text = "  $diff روز قبل";
    }

    return  $text;
}
?>
<style>
    #deleteModal {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
    }
</style>

<div id="deleteModal" class="flex items-center justify-center">
    <div id="modalContent" style="width: 530px;" class="bg-white rounded-md shadow-ld w-54 p-5 flex flex-col items-center justify-center">
        <i class="material-icons text-4xl text-orange-600">warning</i>
        <h4 class=" text-2xl mb-3 font-bold">حذف معلومات</h4>
        <p class="text-center my-4">
            آیا مطمئن هستید میخواهید اطاعات انتخاب شده را حذف نمایید؟
            <br>
            اطلاعات مورد نظر بعد از حذف در درسترس نخواهد بود!
        </p>
        <div class="py-5">
            <button onclick="confirmDelete()" class="border-4 border-red-500/75 rounded-lg bg-red-500 text-white py-2 px-5">تایید و حذف</button>
            <button onclick="closeModal('deleteModal')" class=" border-4 border-indigo-500/75 rounded-lg bg-indigo-500 text-white py-2 px-5">انصراف</button>
        </div>
    </div>
</div>
<div class="">
    <div class="flex">
        <h2 class="title">آخرین قیمت های گرفته شده از بازار</h2>
        <div class="px-24 flex items-center gap-2">
            <label for="search">جستجو</label>
            <input class="border py-2 px-3" type="text" name="search" id="search-bazar" onkeyup="searchBazar(this.value)">
            <i class="material-icons text-red-500 hover:cursor-pointer" onclick="searchByCustomer(this)" data-customer=''>close</i>
        </div>
    </div>
    <div class="box-keeper">
        <table class="min-w-full">
            <tr class="bg-violet-700">
                <th class="text-right px-3 text-white py-2">کد فنی</th>
                <th class="text-right px-3 text-white py-2">فروشنده</th>
                <th class="text-right px-3 text-white py-2">قیمت</th>
                <th class="text-right px-3 text-white py-2">کاربر ثبت کننده</th>
                <th class="text-right px-3 text-white py-2">زمان ثبت</th>
                <th class="text-right px-3 text-white py-2">عملیات</th>
            </tr>
            <tbody id="results">
                <?php
                $currentGroup = null;
                $bgColors = ['rgb(224 231 255)', 'rgb(236 254 255)']; // Array of background colors for date groups
                $bgColorIndex = 0;

                foreach ($results as $row) :
                    $id = $row['id'];
                    $time = $row['time'];
                    $partNumber = $row['codename'];
                    $sellerName = $row['seller_name'];
                    $price = $row['price'];
                    $userId = $row['user_id'];

                    // Explode the time value to separate date and time
                    $dateTime = explode(' ', $time);
                    $date = $dateTime[0];

                    // Check if the group has changed
                    if ($date !== $currentGroup) :
                        // Update the current group
                        $currentGroup = $date;

                        // Get the background color for the current group
                        $bgColor = $bgColors[$bgColorIndex % count($bgColors)];
                        $bgColorIndex++;
                ?>
                        <!-- // Display a row for the new group with the background color -->
                        <tr class="bg-rose-400">
                            <td class="p-3" colspan="6"><?php echo displayTimePassed($date) . ' - ' . jdate('Y/m/d', strtotime($date)) ?></td>
                        </tr>
                    <?php
                    endif;

                    // Display the row for current entry with the same background color as the group
                    ?>
                    <tr id="row-<?php echo $id ?>" style="background-color:<?php echo $bgColor ?>">
                        <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $partNumber ?>'><?php echo $partNumber ?></td>
                        <td class="px-4 hover:cursor-pointer text-rose-400" onclick="searchByCustomer(this)" data-customer='<?php echo $sellerName ?>'><?php echo $sellerName ?></td>
                        <td><?php echo $price ?></td>
                        <td>
                            <img class="w-8 mt-1 rounded-full" src='<?php echo "../userimg/$userId.jpg" ?>' alt="" srcset="">
                        </td>
                        <td><?php $timeString = $dateTime[1]; // Example time string
                            $adjustment = "-1 hour"; // Adjustment to subtract one hour

                            // Create a DateTime object from the time string
                            $time = DateTime::createFromFormat("H:i:s", $timeString);

                            // Adjust the time by subtracting one hour
                            $time->modify($adjustment);

                            // Format the adjusted time to display only the hour and minute
                            $formattedTime = $time->format("H:i");

                            echo $formattedTime; // Output: 14:30
                            ?>
                        </td>
                        <td>
                            <i onclick="editItem(this)" data-item='<?php echo $id ?>' class="material-icons hover:cursor-pointer text-indigo-600">edit</i>
                            <i onclick="deleteItem(this)" data-item='<?php echo $id ?>' class="material-icons hover:cursor-pointer text-red-600">delete</i>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>';
    </div>
</div>
<script>
    const input = document.getElementById('search-bazar');
    const deleteModal = document.getElementById('deleteModal');
    let toBeDeleted = null;

    function searchByCustomer(element) {
        const customer_name = element.getAttribute('data-customer');
        searchBazar(customer_name);
        input.value = customer_name;
    }

    function searchBazar(pattern) {
        let superMode = 0;
        const resultBox = document.getElementById("results");

        pattern = pattern.replace(/\s/g, "");
        pattern = pattern.replace(/-/g, "");
        pattern = pattern.replace(/_/g, "");

        resultBox.innerHTML = `<tr class=''>
                <td colspan='14' class='py-10 text-center'> 
                    <img class=' block w-10 mx-auto h-auto' src='./report/public/img/loading.png' alt='loading'>
                    </td>
            </tr>`;
        var params = new URLSearchParams();
        params.append('pattern', pattern);

        axios.post("./estelam-list-ajax.php", params)
            .then(function(response) {
                resultBox.innerHTML = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function editItem(element) {
        const id = element.getAttribute('data-item');
    }

    function deleteItem(element) {
        const id = element.getAttribute('data-item');

        deleteModal.style.display = 'flex';
        toBeDeleted = id;
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function confirmDelete() {
        var params = new URLSearchParams();
        params.append('toBeDelete', toBeDeleted);
        params.append('operation', 'delete');

        axios.post("./estelam-operations-list-ajax.php", params)
            .then(function(response) {
                document.getElementById('modalContent').innerHTML = `<i class="material-icons text-6xl text-green-600 mb-4">check_circle</i>
                                                                    <h4 class=" text-2xl mb-3 font-bold">عملیات موفقیت آمیز</h4>
                                                                    <p class="text-center my-4">
                                                                        حذف اطلاعات موفقانه صورت گرفت!
                                                                    </p>‍‍`;
                setTimeout(() => {
                    document.getElementById('modalContent').innerHTML = `<i class="material-icons text-4xl text-orange-600">warning</i>
        <h4 class=" text-2xl mb-3 font-bold">حذف معلومات</h4>
        <p class="text-center my-4">
            آیا مطمئن هستید میخواهید اطاعات انتخاب شده را حذف نمایید؟
            <br>
            اطلاعات مورد نظر بعد از حذف در درسترس نخواهد بود!
        </p>
        <div class="py-5">
            <button onclick="confirmDelete()" class="border-4 border-red-500/75 rounded-lg bg-red-500 text-white py-2 px-5">تایید و حذف</button>
            <button onclick="closeModal('deleteModal')" class=" border-4 border-indigo-500/75 rounded-lg bg-indigo-500 text-white py-2 px-5">انصراف</button>
        </div>`;
                    deleteModal.style.display = 'none';
                    document.getElementById('row-' + toBeDeleted).remove();
                }, 1000)

            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>
<?php
require_once './layout/heroFooter.php';
