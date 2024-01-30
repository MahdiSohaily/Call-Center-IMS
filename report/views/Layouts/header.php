<?php
// Initialize the session
date_default_timezone_set("Asia/Tehran");
session_name("MyAppSession");
session_start();
require_once './database/connect.php';

// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["not_allowed"])) {
    // Check if the session has expired (current time > expiration time)
    if ((isset($_SESSION["expiration_time"]) && time() > $_SESSION["expiration_time"]) || authModified(CONN, $_SESSION['id'])) {
        // Session has expired, destroy it and log the user out
        session_unset();
        session_destroy();
        header("location: ../../1402/login.php"); // Redirect to the login page
        exit;
    }
} else {
    // User is not logged in, redirect them to the login page
    header("location: ../../1402/login.php");
    exit;
}

$current_page = explode(".", basename($_SERVER['PHP_SELF']))[0];

if (in_array($current_page, $_SESSION['not_allowed'])) {
    header("location: ../../1402/notAllowed.php"); // Redirect to the login page  header("location: login.php"); // Redirect to the login page
}

function authModified($con, $id)
{
    $sql = "SELECT modified FROM yadakshop1402.authorities WHERE user_id = $id";

    $result = $con->query($sql);

    $isModified = $result->fetch_assoc()['modified'];

    return $isModified;
}


require_once './config/config.php';

require_once('./views/Layouts/jdf.php');

date_default_timezone_set("Asia/Tehran");
$_SESSION["user_id"] = $_SESSION["id"];
?>
<!DOCTYPE html>
<html lang="fe">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is a simple CMS for tracing goods based on thier serail or part number.">
    <meta name="author" content="Mahdi Rezaei">
    <?php
    switch (basename($_SERVER['PHP_SELF'])) {
        case 'index.php':
            $title = "جستجوی اجناس";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'GivenPriceHistory.php':
            $title = "تاریخچه";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'givePrice.php':
            $title = "قیمت دهی دستوری";
            echo ' <link rel="shortcut icon" href="./public/img/ordered.png">';
            break;
        case 'showGoods.php':
            $title = "لیست اجناس";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'showRates.php':
            $title = "لیست نرخ های ارز";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'relationships.php':
            $title = "تعریف رابطه اجناس";
            break;
        case 'notification.php':
            $title = "نوتیفیکشن ها";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'giveOrderedPriceNew.php':
            $title = "قیمت دستوری";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'showPriceReports.php':
            $title = "اطلاعات کد فنی";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
        case 'hamkarTelegram.php':
            $title = "همکار تلگرام";
            echo ' <link rel="shortcut icon" href="../public/img/o-t.png">';
            break;
        case 'telegramProcess.php':
        case 'showPriceReportsTelegram.php':
            $title = "تلگرام قیمت";
            echo ' <link rel="shortcut icon" href="../public/img/b-t.png">';
            break;
        case 'generateBill_new.php':
            $title = "ویرایش فاکتور";
            echo ' <link rel="shortcut icon" href="./public/img/bill_generate.svg">';
            break;
        case 'factor_new.php':
            $title = "مدیریت فاکتور";
            echo ' <link rel="shortcut icon" href="./public/img/bill_generate.svg">';
            break;
        case 'defineExchangeRate.php':
            $title = "تعریف افزایش دلار";
            echo ' <link rel="shortcut icon" href="./public/img/dollar.svg">';
            break;
        default:
            $title = "سامانه یدک شاپ";
            echo ' <link rel="shortcut icon" href="./public/img/report.png">';
            break;
    }
    ?>
    <title><?php echo $title ?></title>

    <link href="./public/css/material_icons.css?v=<?= rand() ?>" rel="stylesheet">
    <script src="./public/js/index.js?v=<?= rand() ?>"></script>
    <link rel="stylesheet" href="./public/css/styles.css?v=<?= rand() ?>">
    <script src="./public/js/axios.js"></script>
    </script>
    <script src="./public/js/jquery.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./public/css/select2.css?v=<?= rand() ?>">
    <script src="./public/js/select2.js?v=<?= rand() ?>"></script>
    <script src="./public/js/copy.js?v=<?= rand() ?>"></script>
    <script>
        const seekExist = (e) => {
            const element = e;
            if (element.hasAttribute("data-key")) {
                const partNumber = element.getAttribute('data-key');
                const brand = element.getAttribute('data-brand');

                const target = document.getElementById(partNumber + '-' + brand)
                target.style.display = 'block';
            }
        }

        const closeSeekExist = (e) => {
            const element = e;
            if (element.hasAttribute("data-key")) {
                const partNumber = element.getAttribute('data-key');
                const brand = element.getAttribute('data-brand');

                const target = document.getElementById(partNumber + '-' + brand)
                target.style.display = 'none';
            }

        }

        function showToolTip(element) {
            partnumber = element.getAttribute('data-part');
            targetElement = document.getElementById(partnumber + '-google');

            targetElement.style.display = 'flex';
            targetElement.style.gap = '5px';
        }

        function hideToolTip(element) {
            partnumber = element.getAttribute('data-part');
            targetElement = document.getElementById(partnumber + '-google');

            targetElement.style.display = 'none';
            targetElement.style.gap = '5px';
        }

        function convertToPersian(element) {
            // Define a mapping of English keyboard keys to Persian characters
            const persianCharMap = {
                'a': 'ش',
                'b': 'ذ',
                'c': 'ز',
                'd': 'ی',
                'e': 'ث',
                'f': 'ب',
                'g': 'ل',
                'h': 'ا',
                'i': 'ه',
                'j': 'ت',
                'k': 'ن',
                'l': 'م',
                'm': 'پ',
                'n': 'د',
                'o': 'خ',
                'p': 'ح',
                'q': 'ض',
                'r': 'ق',
                's': 'س',
                't': 'ف',
                'u': 'ع',
                'v': 'ر',
                'w': 'ص',
                'x': 'ط',
                'y': 'غ',
                'z': 'ظ',
                ',': 'و',
                "'": 'گ',
                ";": 'ک',
                "]": 'چ',
                '1': '۱',
                '2': '۲',
                '3': '۳',
                '4': '۴',
                '5': '۵',
                '6': '۶',
                '7': '۷',
                '8': '۸',
                '9': '۹',
                '0': '۰'
            };
            const customInput = element;
            let customText = '';
            const inputText = customInput.value.toLowerCase();
            for (let i = 0; i < inputText.length; i++) {
                const char = inputText[i];
                if (char in persianCharMap) {
                    customText += persianCharMap[char];
                } else {
                    customText += char;
                }
            }
            customInput.value = customText;
        }

        function convertToEnglish(element) {
            const englishCharMap = {
                'ش': 'a',
                'ذ': 'b',
                'ز': 'c',
                'ی': 'd',
                'ث': 'e',
                'ب': 'f',
                'ل': 'g',
                'ا': 'h',
                'ه': 'i',
                'ت': 'j',
                'ن': 'k',
                'م': 'l',
                'پ': 'm',
                'د': 'n',
                'خ': 'o',
                'ح': 'p',
                'ض': 'q',
                'ق': 'r',
                'س': 's',
                'ف': 't',
                'ع': 'u',
                'ر': 'v',
                'ص': 'w',
                'ط': 'x',
                'غ': 'y',
                'ظ': 'z',
                'و': ':',
                'گ': "'",
                'ک': ";",
                'چ': "]",
                '۱': '1',
                '۲': '2',
                '۳': '3',
                '۴': '4',
                '۵': '5',
                '۶': '6',
                '۷': '7',
                '۸': '8',
                '۹': '9',
                '۰': '0'
            };

            const customInput = element;
            let customText = '';
            const inputText = customInput.value.toLowerCase();
            for (let i = 0; i < inputText.length; i++) {
                const char = inputText[i];
                if (char in englishCharMap) {
                    customText += englishCharMap[char];
                } else {
                    customText += char;
                }
            }
            customInput.value = customText;
        }
    </script>
    <script src="./public/js/usersManagement.js?v=<?= rand() ?>"></script>
</head>

<body id="wrapper" class="font-sans antialiased min-h-screen bg-gray-50">
    <nav id="nav" ref="nav" class="main-nav bg-white shadow-lg flex flex-col justify-between">
        <i id="close" onclick="toggleNav()" class="material-icons absolute m-3 left-0 hover:cursor-pointer">close</i>
        <ul class="rtl flex flex-wrap flex-col pt-5 mt-5 ">
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium 
                    leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none
                     transition duration-150 ease-in-out" href="../index.php">
                <i class="px-2 material-icons hover:cursor-pointer">account_balance</i>
                صفحه اصلی
            </a>
            <a class="cursor-pointer inline-flex 
                    items-center py-3 pr-6 text-sm font-medium leading-5 
                    text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none
                     transition duration-150 ease-in-out" href="../../1402/">
                <i class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                سامانه قیمت
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 
                    pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 
                    hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../cartable-personal.php">
                <i class="px-2 material-icons hover:cursor-pointer">assignment_ind</i>
                کارتابل شخصی
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../customer-list.php">
                <i class="px-2 material-icons hover:cursor-pointer">assignment</i>
                لیست مشتریان
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="../last-calling-time.php">
                <i class="px-2 material-icons hover:cursor-pointer">call_end</i>
                آخرین مکالمات
            </a>

            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="./index.php">
                <i class="px-2 material-icons hover:cursor-pointer">search</i>
                جستجوی اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="./showGoods.php">
                <i class="px-2 material-icons hover:cursor-pointer">local_mall</i>
                اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm
                     font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                     focus:outline-none transition duration-150 ease-in-out" href="./showRates.php">
                <i class="px-2 material-icons hover:cursor-pointer">show_chart</i>
                نرخ های ارز
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="./relationships.php">
                <i class="px-2 material-icons hover:cursor-pointer">sync</i>
                تعریف رابطه اجناس
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="./usersManagement.php">
                <i class="px-2 material-icons hover:cursor-pointer">verified_user</i>
                مدیریت کاربران
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="./defineExchangeRate.php">
                <i class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                تعریف دلار جدید
            </a>
            <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="./price_check.php">
                <i class="px-2 material-icons hover:cursor-pointer">call_end</i>
                بررسی قیمت کدفنی
            </a>
        </ul>
        <!-- Authentication -->
        <a class="rtl cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../../1402/logout.php">
            <i class="px-2 material-icons hover:cursor-pointer">power_settings_new</i>
            خروج از حساب
        </a>
    </nav>
    <!-- Page Content -->
    <main class="pt-28 min-h-screen">
        <div id="side_nav" class="rtl flex justify-between bg-gray-200 fixed w-full shadow-lg top-0" style="z-index:100">
            <i class="p-2 right-0 material-icons hover:cursor-pointer fixed my-3" onclick="toggleNav()">menu</i>
            <ul class="flex flex-wrap mr-20 py-3">
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../cartable.php">
                        <i class="fas fa-layer-group"></i>
                        کارتابل
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../bazar.php">
                        <i class="fas fa-phone-volume"></i>
                        تماس عمومی
                    </a>
                </li>
                <li class="my-2"><a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../bazar2.php">
                        <i class="fas fa-phone-volume"></i>
                        تماس با بازار
                    </a>
                </li>
                <li class="my-2"><a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="./hamkarTelegram.php">
                        <i class="fas fa-phone-volume"></i>
                        همکار تلگرام
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../estelam-list.php">
                        <i class="fas fa-arrow-down"></i>
                        <i class="fas fa-dollar-sign"></i>
                        قیمت های گرفته شده
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="../shomarefaktor.php">
                        <i class="fas fa-feather-alt"></i>
                        شماره فاکتور
                    </a>
                </li>
                <li class="my-2">
                    <a target="_blank" class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="./givePrice.php">
                        <i class="fas fa-feather-alt"></i>
                        قیمت دهی دستوری
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" target="_blank" href="./GivenPriceHistory.php">
                        <i class="fas fa-history"></i>
                        تاریخچه
                    </a>
                </li>
                <li class="my-2">
                    <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" target="_blank" href="./telegramProcess.php">
                        <i class="fas fa-history"></i>
                        تلگرام
                    </a>
                </li>
            </ul>

            <div class="my-2 flex flex-wrap items-top p-2">
                <i onclick="toggleTV()" class="material-icons hover:cursor-pointer text-gray-500">branding_watermark</i>
                <?php
                $profile = '../../userimg/default.png';
                if (file_exists("../../userimg/" . $_SESSION['id'] . ".jpg")) {
                    $profile = "../../userimg/" . $_SESSION['id'] . ".jpg";
                }
                ?>
                <img class="userImage mx-2" src="<?= $profile ?>" alt="userimage">
                <a id="active" class="hidden" href="./notification.php">
                    <i class="material-icons hover:cursor-pointer notify ">notifications_active</i>
                </a>
                <a id="deactive" class="" href="./notification.php">
                    <i class="material-icons hover:cursor-pointer text-indigo-500">notifications</i>
                </a>
            </div>
        </div>