<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
$sql = "SELECT * FROM rates ORDER BY amount ASC";
$rates = $conn->query($sql);
?>
<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div>
            <div class="p-6 lg:p-8 flex justify-center">
                <input type="text" name="serial" id="serial" class="rounded-md p-3 text-center w-96 border-2 bg-gray-100" min="0" max="30" onkeyup="convertToEnglish(this); search(this.value)" placeholder="... کد فنی قطعه را وارد کنید" />
            </div>
            <div class="flex justify-center items-center pb-6">
                <label for="mode" class="px-2">جستجوی پیشرفته</label>
                <input type="checkbox" name="super" id="mode" class="rounded-md " />
            </div>
            <div class="bg-gray-100 bg-opacity-25">
                <div class="max-w-7xl overflow-x-auto mx-auto">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="font-medium dark:border-neutral-500">
                            <tr class="bg-green-700">
                                <th scope="col" class="px-3 py-3 bg-black text-white w-52 text-center">
                                    شماره فنی
                                </th>
                                <th scope="col" class="px-3 py-3 text-white w-20">
                                    دلار پایه
                                </th>
                                <th scope="col" class="px-3 py-3 text-white border-black border-r-2">
                                    +10%
                                </th>
                                <?php
                                if ($rates->num_rows > 0) {
                                    // output data of each row
                                    while ($rate = $rates->fetch_assoc()) {
                                        echo "<th class='" . $rate['status'] . " px-3 py-3 text-white text-center ' scope='col'>" . $rate['amount'] . "</th>";
                                    }
                                }
                                ?>
                                <th scope="col" class="px-3 py-3 text-white w-32 text-center">
                                    عملیات
                                </th>
                                <th scope="col" class="px-3 py-3 text-white">
                                    وزن
                                </th>
                            </tr>
                        </thead>
                        <tbody id="results">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let result = null;

    const search = (val) => {
        let pattern = val;
        let superMode = 0;
        const resultBox = document.getElementById("results");

        if (document.getElementById("mode").checked) {
            superMode = 1;
        }

        if (
            (pattern.length > 4 && superMode == 1) ||
            (pattern.length > 6 && superMode == 0)
        ) {
            pattern = pattern.replace(/\s/g, "");
            pattern = pattern.replace(/-/g, "");
            pattern = pattern.replace(/_/g, "");

            resultBox.innerHTML = `<tr class=''>
                <td colspan='14' class='py-10 text-center'> 
                    <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='loading'>
                    </td>
            </tr>`;
            var params = new URLSearchParams();
            params.append('pattern', pattern);
            params.append('superMode', superMode);

            axios.post("./app/Controllers/SearchController.php", params)
                .then(function(response) {
                    resultBox.innerHTML = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
            resultBox.innerHTML = "";
        }
    };
</script>
<?php
require_once('./views/Layouts/footer.php');
