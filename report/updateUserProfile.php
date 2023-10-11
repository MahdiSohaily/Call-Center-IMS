<?php
require_once('./views/Layouts/header.php');
$user_id = $_GET['user'];
$user = getUser($conn, $user_id);

if ($user) {

?>
    <div class="bg-white rounded-lg shadow-md m-5 w-1/2 mx-auto p-5">
        <div class="rtl flex items-center justify-between p-3">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="material-icons font-semibold text-cyan-600">person_add</i>
                ایجاد حساب کاربری
            </h2>
        </div>
        <div class="rtl">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <label class="block font-medium text-sm text-gray-700">
                            نام
                        </label>
                        <input name="name" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="serial" type="text" />
                        <p class="mt-2"></p>
                    </div>
                    <!-- Price -->
                    <div class="col-span-6 sm:col-span-4">
                        <label class="block font-medium text-sm text-gray-700">
                            نام خانوادگی
                        </label>
                        <input name="family" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="price" type="text" />
                        <p class="mt-2"> </p>
                    </div>
                    <!-- Weight -->
                    <div class="col-span-6 sm:col-span-4">
                        <label class="block font-medium text-sm text-gray-700">
                            نام کاربری
                            <span class="text-red-500">*</span>
                        </label>
                        <input required name="username" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="weight" type="text" />
                        <p class="mt-2"> </p>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label class="block font-medium text-sm text-gray-700">
                            پروفایل
                        </label>
                        <input name="profile" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="profile" type="file" />
                        <p class="mt-2"> </p>
                    </div>
                    <!-- Mobis -->
                    <div class="col-span-6 sm:col-span-4 relative">
                        <label class="block font-medium text-sm text-gray-700">
                            رمزعبور
                            <span class="text-red-500">*</span>
                        </label>
                        <i onclick="togglePass(this)" class="material-icons cursor-pointer" style="position: absolute; left:5px; top: 50%">remove_red_eye</i>
                        <input required name="password" minlength="5" maxlength="20" class="border-1 mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2" id="mobis" type="password" />
                    </div>
                    <!-- Korea section -->
                    <div class="col-span-6 sm:col-span-4">
                        <label class="block font-medium text-sm text-gray-700">
                            نوعیت حساب کاربری
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="type" class="border-1 p-2 text-sm border-gray-300 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" id="status">
                            <option value="1" class="text-sm">
                                تماس با بازار </option>

                            <option value="2" class="text-sm">
                                حسابداری </option>
                            <option value="3" class="text-sm">
                                انبار </option>

                            <option value="4" class="text-sm">
                                مدیر </option>

                        </select>
                        <p class="mt-2"> </p>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <button class="button bg-green-700 text-white py-2 px-3 rounded-lg hover:bg-green-600" type="submit">ایجاد حساب کاربری</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePass(element) {
            const target = element.nextElementSibling;
            const inputType = target.type;

            if (inputType === 'password') {
                target.type = 'test';
                return;
            }

            target.type = 'password';
        }
    </script>
<?php
} else {
}
require_once('./views/Layouts/footer.php');

if (isset($_POST['name']) && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $family = $_POST['family'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $roll = 10;

    $authority = '{
        "usersManagement":false,
        "khorojkala-index":false,
        "vorodkala-index":false,
        "khorojkala-report":false,
        "vorodkala-report":false,
        "transfer_index":false,
        "transfer_report":false,
        "goodLimitReport":false,
        "goodLimitReportAll":false,
        "shomaresh-index":false,
        "telegramProcess":false,
        "givePrice":false,
        "showRates":false,
        "relationships":false,
        "defineExchangeRate":false,
        "createUserProfile":false,
    }';
    switch ($type) {
        case '1':
            $authority = '{
                "usersManagement":false,
                "khorojkala-index":false,
                "vorodkala-index":false,
                "khorojkala-report":true,
                "vorodkala-report":true,
                "transfer_index":false,
                "transfer_report":false,
                "goodLimitReport":false,
                "goodLimitReportAll":false,
                "shomaresh-index":false,
                "telegramProcess":false,
                "givePrice":true,
                "showRates":false,
                "relationships":false,
                "defineExchangeRate":false,
                "createUserProfile":false,
            }';
            break;
        case '2':
            $authority = '
            {
                "usersManagement":false,
                "khorojkala-index":true,
                "vorodkala-index":true,
                "khorojkala-report":true,
                "vorodkala-report":true,
                "transfer_index":false,
                "transfer_report":false,
                "goodLimitReport":false,
                "goodLimitReportAll":false,
                "shomaresh-index":false,
                "telegramProcess":false,
                "givePrice":false,
                "showRates":false,
                "relationships":false,
                "defineExchangeRate":false,
                "createUserProfile":false,
            }';
            break;
        case '3':
            $authority = '
            {
                "usersManagement":false,
                "khorojkala-index":true,
                "vorodkala-index":true,
                "khorojkala-report":true,
                "vorodkala-report":true,
                "transfer_index":true,
                "transfer_report":true,
                "goodLimitReport":true,
                "goodLimitReportAll":true,
                "shomaresh-index":true,
                "telegramProcess":false,
                "givePrice":false,
                "showRates":false,
                "relationships":false,
                "defineExchangeRate":false,
                "createUserProfile":false,
            }';
            break;
        case '4':
            $authority = '{
                "usersManagement":true,
                "khorojkala-index":true,
                "vorodkala-index":true,
                "khorojkala-report":true,
                "vorodkala-report":true,
                "transfer_index":true,
                "transfer_report":true,
                "goodLimitReport":true,
                "goodLimitReportAll":true,
                "shomaresh-index":true,
                "telegramProcess":true,
                "givePrice":true,
                "showRates":true,
                "relationships":true,
                "defineExchangeRate":true,
                "createUserProfile":true,
            }';
            $roll = 1;
            break;
    }
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE yadakshop1402.users SET username = '$username', password = '$hash_pass', roll = '$roll',
            internal= '', ip= '', name = '$name', family = '$family', isLogin = '0') 
            WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        uploadFile($last_id, $_FILES['profile']);
        $success = true;
    }
}


function uploadFile($last_id, $file)
{
    $allowed = ['png', 'jpg', 'jpeg'];


    $type = explode('/', $file['type'])[1];
    if (!in_array($type, $allowed)) {
        return false;
    }

    $targetDirectory = "../../userimg/"; // Directory where you want to store the uploaded files
    $targetFile = $targetDirectory . $last_id . "." . $type;

    // Check if the file already exists
    if (file_exists($targetFile)) {
        echo "File already exists.";
        return false;
    }
    // Upload the file
    if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
}

function getUser($conn, int $id)
{
    $sql = "SELECT * FROM yadakshop1402.users WHERE id = $id";
    $result = $conn->query($sql);

    return $result->fetch_assoc();
}
