<?php
require_once './php/function.php';
require_once './config/database.php';
mysqli_set_charset($con, "utf8");
require_once './php/jdf.php';
// Global Variable to change the page title base on the page accordingly
$title = '';
?>
<!DOCTYPE html>
<html lang="fe" style="margin-top: 0 !important;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="./public/img/YadakShop.png"> -->
    <meta name="description" content="This is a simple CMS for tracing goods based on thier serail or part number.">
    <meta name="author" content="Mahdi Rezaei">
    <?php
    // Swith the page title based on the files name
    switch (basename($_SERVER['PHP_SELF'])) {
        case 'cartable.php':
            $title = "کارتابل";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
        case 'cartable-personal.php':
            $title = "کارتابل شخصی";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
        case 'shomarefaktor.php':
            $title = "شماره فاکتور";
            $rand = rand();
            echo ' <link rel="shortcut icon" href="./img/bill.png">';
            echo "<link rel='stylesheet' href='./css/factor/factorStyles.css?v=$rand' type='text/css' media='all' />";
            break;
        case 'main.php':
            $rand = rand();
            $title = "اطلاعات مشتری";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            echo "<link rel='stylesheet' href='./css/main/mainStyle.css?v=$rand' type='text/css' media='all' />";
            break;
        case 'customer-list.php':
            $title = "لیست مشتریان";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
        case 'last-calling-time.php':
            $title = "آخرین مکالمات";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
        case 'index.php':
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            $title = "صفحه اصلی";
            break;
        case 'inquery-list.php':
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            $title = "قیمت های داده شده";
            break;
        case 'tv.php':
            $title = "تلویزیون";
            break;
        case 'estelam-list.php':
            $title = "قیمت های گرفته شده";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
        case 'hamkarTelegram.php':
            $title = "همکار تلگرام";
            echo ' <link rel="shortcut icon" href="./public/img/o-t.png">';
            break;

        default:
            $title = "صفحه اصلی";
            echo '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
            break;
    }
    ?>
    <title><?php echo $title ?></title>
    <!-- Start Add on pulgins style -->
    <link rel="stylesheet" href="./css/jquery.tagselect.css?v=<?php echo (rand()) ?>" media="all" />
    <link rel="stylesheet" href="./css/jquery.tagselect2.css?v=<?php echo (rand()) ?>" media="all" />
    <link rel="stylesheet" href="./css/persianDatepicker.css" />

    <!-- Icons style -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Our custome style -->
    <link rel="stylesheet" href="./css/style.css?v=<?php echo (rand()) ?>" media="all" />

    <!-- Addon plugins JS files -->
    <script src="js/jquery.min.js?v=<?php echo (rand()) ?>"></script>
    <script src="js/save.js?v=<?php echo (rand()) ?>"></script>
    <script src="./public/js/axios.js"></script>
    <script src="js/jquery.tagselect.js?v=<?php echo (rand()) ?>"></script>
    <script src="js/jquery.tagselect2.js?v=<?php echo (rand()) ?>"></script>
    <script src="js/font.min.js"></script>
    <script src="js/copy.js"></script>
    <script src="js/persianDatepicker.min.js"></script>
    <script src="./report/public/js/index.js"></script>

    <!-- Our custome defined JS file -->
    <script src="js/my.js?v=<?php echo (rand()) ?>"></script>
    <script src="./public/js/copyToClipboard.js"></script>


    <script>
        history.scrollRestoration = "manual"
    </script>

    <style>
        /* main navbar styles */
        .main-nav {
            position: fixed;
            top: 0;
            right: -300px;
            bottom: 0;
            width: 250px;
            transition: all 500ms ease-in-out;
            z-index: 1000;
        }

        .open {
            right: 0;
        }

        .link-s {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            text-decoration: none;
            color: white;
            margin-right: 0.5rem;
            padding: 0.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custome-tooltip {
            position: absolute;
            display: none;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            border-radius: 5px;
            background-color: seagreen;
            width: 200px;
            z-index: 100000000;
        }

        .custom-table td {
            vertical-align: super;
        }

        .custome-alert {
            color: white !important;
            position: fixed;
            bottom: -100px;
            right: 50%;
            transform: translateX(-50%);
            transition: all 1s ease;
            padding: 10px;
        }

        .custome-alert.success {
            background-color: green;
        }

        .custome-alert.error {
            background-color: red;
        }

        .notify {
            position: relative;
            animation-name: wave;
            animation-duration: 0.5s;
            animation-iteration-count: infinite;
            color: red;
        }

        @keyframes wave {
            0% {
                transform: rotate(-20deg);
            }

            50% {
                transform: rotate(20deg);
            }

            100% {
                transform: rotate(-20deg);
            }

        }

        .hidden {
            display: none !important;
        }

        .blue-bell {
            color: dodgerblue;
        }
    </style>

    <script>
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
</head>

<body>
    <div>
        <div class="min-h-screen ">
            <nav id="nav" ref="nav" class="main-nav bg-white shadow-lg flex flex-col justify-between overflow-auto">
                <i id="close" onclick="toggleNav()" class="material-icons absolute m-3  left-0 hover:cursor-pointer">close</i>
                <ul class="rtl flex flex-wrap flex-col pt-5 mt-5">
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="index.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">account_balance</i>
                        صفحه اصلی
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../1402/">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                        سامانه قیمت
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="cartable-personal.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">assignment_ind</i>
                        کارتابل شخصی
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="customer-list.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">assignment</i>
                        لیست مشتریان
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="last-calling-time.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">call_end</i>
                        آخرین مکالمات
                    </a>

                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white 
                    focus:outline-none transition duration-150 ease-in-out" href="./report/index.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">search</i>
                        جستجوی اجناس
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="./report/showGoods.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">local_mall</i>
                        اجناس
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="./report/showRates.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">show_chart</i>
                        نرخ های ارز
                    </a>
                    <a style="font-size: 12px;" class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="./report/relationships.php">
                        <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">sync</i>
                        تعریف رابطه اجناس
                    </a>
                    <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="./report/usersManagement.php">
                        <i class="px-2 material-icons hover:cursor-pointer">verified_user</i>
                        مدیریت کاربران
                    </a>
                    <a class="cursor-pointer inline-flex items-center py-3 pr-6 text-sm 
                    font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white
                     focus:outline-none transition duration-150 ease-in-out" href="./report/defineExchangeRate.php">
                        <i class="px-2 material-icons hover:cursor-pointer">attach_money</i>
                        تعریف دلار جدید
                    </a>
                </ul>
                <!-- Authentication -->
                <a style="font-size:12px" class="rtl cursor-pointer inline-flex items-center py-3 pr-6 text-sm font-medium leading-5 text-gray-500 hover:bg-indigo-500 hover:text-white focus:outline-none transition duration-150 ease-in-out" href="../1402/logout.php">
                    <i style="font-size:14px" class="px-2 material-icons hover:cursor-pointer">power_settings_new</i>
                    خروج از حساب
                </a>
            </nav>
            <script>
                const toggleNav = () => {
                    const nav = document.getElementById("nav");
                    if (nav.classList.contains("open")) {
                        nav.classList.remove("open");
                    } else {
                        nav.classList.add("open");
                    }
                };
            </script>
            <!-- Page Content -->
            <main class="lg:pt-20 pt-32">
                <div id="topNav" class="flex justify-between bg-gray-200 fixed w-full shadow-lg" style="top: 0; z-index:100">
                    <i class="p-2 right-0 material-icons hover:cursor-pointer fixed my-3" onclick="toggleNav()">menu</i>
                    <ul class="flex flex-wrap mr-20 py-3">
                        <li class="my-2">
                            <a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" href="cartable.php">
                                کارتابل
                            </a>
                        </li>
                        <li class="my-2">
                            <a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" href="bazar.php">
                                تماس عمومی
                            </a>
                        </li>
                        <li class="my-2"><a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" href="bazar2.php">
                                تماس با بازار
                            </a>
                        </li>
                        <li class="my-2"><a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="./report/hamkarTelegram.php">
                                <i class="fas fa-phone-volume"></i>
                                همکار تلگرام
                            </a>
                        </li>
                        <li class="my-2">
                            <a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" href="estelam-list.php">
                                قیمت های گرفته شده
                            </a>
                        </li>
                        <li class="my-2">
                            <a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" href="shomarefaktor.php">
                                شماره فاکتور
                            </a>
                        </li>
                        <li class="my-2">
                            <a style="font-size: 10px;" target="_self" class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" href="./report/givePrice.php">
                                قیمت دهی دستوری
                            </a>
                        </li>
                        <li class="my-2">
                            <a style="font-size: 10px;" class="px-4 py-2 bg-violet-600 lg:ml-2 ml-1 rounded-md text-white text-xs" target="_blank" href="../callcenter/report/GivenPriceHistory.php">
                                تاریخچه
                            </a>
                        </li>
                        <li class="my-2">
                            <a class="px-4 py-2 bg-violet-600 ml-2 rounded-md text-white text-xs" target="_blank" href="../callcenter/report/telegramProcess.php">
                                تلگرام
                            </a>
                        </li>
                    </ul>

                    <div class=" flex flex-wrap items-top p-2 my-3">
                        <i onclick="toggleTV()" class="material-icons hover:cursor-pointer text-gray-500">branding_watermark</i>
                        <?php
                        $profile = '../userimg/default.png';
                        if (file_exists("../userimg/" . $_SESSION['id'] . ".jpg")) {
                            $profile = "../userimg/" . $_SESSION['id'] . ".jpg";
                        }
                        ?>
                        <img class="userImage mx-2" src="<?= $profile ?>" alt="userimage">
                        <a id="active" class="hidden" href="./report/notification.php">
                            <i class="material-icons hover:cursor-pointer notify ">notifications_active</i>
                        </a>
                        <a id="deactive" class="" href="./report/notification.php">
                            <i class="material-icons hover:cursor-pointer text-indigo-500">notifications</i>
                        </a>
                    </div>
                </div>
                <script>
                    const active = document.getElementById('active');
                    const deactive = document.getElementById('deactive');

                    setInterval(() => {
                        const params = new URLSearchParams();
                        params.append('check_notification', 'check_notification');
                        axios
                            .post("./report/app/Controllers/notificationAjaxController.php", params)
                            .then(function(response) {
                                if (response.data > 0) {
                                    active.classList.remove('hidden');
                                    deactive.classList.add('hidden');
                                } else {
                                    deactive.classList.remove('hidden');
                                    active.classList.add('hidden');
                                }
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                    }, 30000);

                    function toggleTV() {
                        const params = new URLSearchParams();
                        params.append('toggle', 'toggle');
                        axios
                            .post("./tvController.php", params)
                            .then(function(response) {
                                alert(response.data);
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                    }
                </script>