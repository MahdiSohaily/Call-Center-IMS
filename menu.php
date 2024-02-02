<html>

<body>

    <div class="top-bar">
        <div class="link">
            <li><a href="cartable.php">کارتابل <i class="fas fa-layer-group"></i></a></li>
            <li><a href="cartable-personal.php">کارتابل شخصی <i class="fas fa-layer-group"></i><i class="fas fa-user"></i></a></li>
            <li><a href="index.php">صفحه اصلی <i class="fas fa-home"></i></a></li>

            <li><a href="customer-list.php">لیست مشتریان <i class="fas fa-users"></i></a></li>

            <!-- <li><a href="inquery-list.php">قیمت های داده شده <i class="fas fa-arrow-up"></i><i class="fas fa-dollar-sign"></i></a></li> -->

            <li><a href="last-calling-time.php">آخرین مکالمات <i class="fas fa-feather-alt"></i></a></li>

            <li><a href="bazar.php">تماس عمومی <i class="fas fa-phone-volume"></i></a></li>
            <li><a href="bazar2.php">تماس با بازار <i class="fas fa-phone-volume"></i></a></li>
            <li><a href="estelam-list.php">قیمت های گرفته شده <i class="fas fa-arrow-down"></i><i class="fas fa-dollar-sign"></i></a></li>
            <li><a href="shomarefaktor.php">شماره فاکتور <i class="fas fa-feather-alt"></i></a></li>

            <li><a target="_blank" href="../callcenter/report/givePrice.php">قیمت دهی دستوری <i class="fas fa-stamp"></i></a></li>
            <li><a target="_blank" href="../callcenter/report/GivenPriceHistory.php">تاریخچه<i class="fas fa-history"></i></a></li>
            <li><a target="_blank" href="../1402/">سامانه قیمت <i class="fas fa-dollar-sign"></i></a></li>
            <style>
                .hidden {
                    display: none !important;
                }

                .blue-bell {
                    color: dodgerblue;
                }
            </style>

        </div>
        <div style="display: flex; justify-content: center; align-items: center; gap:5px; padding-top:5px" class="user-box">
            <div class=" flex items-top p-2">
                <a style="background-color: transparent; border: none; box-shadow:none" id="active" class="hidden" href="./report/notification.php">
                    <i class="material-icons hover:cursor-pointer notify ">notifications_active</i>
                </a>
                <a style="background-color: transparent; border: none; box-shadow:none" id="deactive" class="" href="./report/notification.php">
                    <i class="material-icons hover:cursor-pointer blue-bell">notifications</i>
                </a>
            </div>
            <img title="<?php echo $_SESSION['username'] ?>" class="userImage mx-2" src="../userimg/<?php echo $_SESSION['id'] ?>.jpg" alt="userimage">
            <a title="خروج از سیستم" href="../1402/logout.php"><i class="fas fa-power-off"></i>
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
                    console.log(response.data);
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
    </script>