<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
require_once('./app/Controllers/GoodController.php');

$page = 0;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$amount = $page * 10;

$sql = "SELECT * FROM yadakshop1402.nisha OFFSET LIMIT 10 OFFSET $amount";
$goods = $conn->query($sql);

?>
<div>
    <div class="max-w-7xl overflow-x-auto mx-auto pt-11 pb-3 flex justify-between">
        <a href="./registerGood.php" class="bg-indigo-500 hover:bg-indigo-400 rounded-md text-white px-4 flex justify-center items-center">
            ثبت جنس جدید
            <i class="px-2 material-icons hover:cursor-pointer">add_circle_outline</i>
        </a>
        <input onkeyup="convertToEnglish(this)" type="text" name="serial" id="serial" class="rtl rounded-md py-2 w-96 border-2 bg-gray-100 px-3" min="0" max="30" @keyup="search($event.target.value, rates)" placeholder="جستجو به اساس شماره فنی ..." />
    </div>
    <div class="bg-gray-100 bg-opacity-25">
        <div class="max-w-7xl overflow-x-auto mx-auto">
            <table class="rtl min-w-full text-left text-sm font-light">
                <thead class="font-medium dark:border-neutral-500">
                    <tr class="bg-green-700">
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            شماره فنی
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            قیمت
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            وزن
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            موبیز
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center">
                            کورآ
                        </th>
                        <th scope="col" class="px-3 py-3 text-white text-center w-24">
                            عملیات
                        </th>
                    </tr>
                </thead>
                <tbody id="results">
                    <?php if ($goods->num_rows > 0) {

                        while ($row = $goods->fetch_assoc()) {
                    ?>
                            <tr v-if="goods.length > 0" v-for="item in goods" class="transition duration-300 ease-in-out bg-neutral-200 hover:bg-neutral-100">
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $row['partnumber'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $row['price'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $row['weight'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $row['mobis'] ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-center font-bold">
                                    <?php echo $row['korea'] ?>
                                </td>
                                <td class="whitespace-nowrap w-24">
                                    <div class="flex justify-center gap-1 items-center px-2">
                                        <a class="cursor-pointer" href="./registerGood.php?form=update&id=<?php echo $row['id'] ?>">
                                            <i class="material-icons text-blue-500 hover:text-blue-700">create</i>
                                        </a>
                                        <a class="cursor-pointer" type="submit" onclick="confirmDeletion(this)" data-id="<?php echo $row['id'] ?>">
                                            <i :data-id="item.id" class="material-icons text-red-600 hover:text-red-800">delete_forever</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else { ?>

                        <tr v-else class="transition duration-300 ease-in-out bg-neutral-200">
                            <td colspan="6" class="whitespace-nowrap px-3 py-3 text-center text-red-500 font-bold">
                                <i class="material-icons text-red-500">mood_bad</i>
                                <br />
                                !متاسفانه چیزی برای نمایش در پایگاه داده
                                موجود نیست
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div v-if="goods.length > 0" class="flex justify-center py-3">
                <ul class="flex">
                    <?php
                    if ($page !== 0) {
                    ?>
                        <li class="bg-blue-400 hover:bg-blue-300 mx-2 flex justify-center items-center px-3 py-2 rounded-md cursor-pointer" id="prev" @click="page('prev')">
                            <a href="./showGoods.php?page=<?php echo $page - 1 ?>">
                                <i class="material-icons text-white">fast_rewind</i>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="bg-blue-400 hover:bg-blue-300 mx-2 flex justify-center items-center px-3 p-2 rounded-md cursor-pointer" id="next" @click="page('next')">
                        <a href="./showGoods.php?page=<?php echo $page + 1 ?>">
                            <i class="material-icons text-white">fast_forward</i>
                        </a>
                    </li>
                </ul>
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

    function confirmDeletion(e) {
        const element = e;
        const deleteItem = element.getAttribute('data-id');

        var params = new URLSearchParams();

        params.append('delete_id', deleteItem);
        params.append('Delete_Good', 'Delete_Good');

        let text = "آیا مطمئن هستید که میخواهید عملیات حذف را انجام دهید؟";
        if (confirm(text) == true) {
            axios.post("./app/Controllers/GoodDeleteController.php", params)
                .then(function(response) {
                    if (response.data) {
                        location.reload();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

    }
</script>
<?php
require_once('./views/Layouts/footer.php');
