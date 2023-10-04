<?php
require_once './header.php';



$q = $_GET['q'];


$sql = "SELECT * FROM shomarefaktor WHERE id=$q";

$result = mysqli_query(dbconnect(), $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $shomare = $row['shomare'];
        $kharidar = $row['kharidar'];
        $user = $row['user'];





?>
        <table class="customer-list jadval-shomare">
            <tr>
                <th>شماره فاکتور</th>
                <th>خریدار</th>
                <th>کاربر</th>

            </tr>

            <tr>

                <td>
                    <div class="jadval-shomare-blue"><?php echo $shomare ?></div>
                </td>
                <td>
                    <div class="jadval-shomare-kharidar"><?php echo $kharidar ?></div>
                </td>
                <td><img class="user-img" src="../userimg/<?php echo $user ?>.jpg" /></td>




            <tr>
                <form class="shomare-faktor-form-edit" action="php/shomare-faktor-form-edit-save.php" method="get" autocomplete="off">

                    <td> <input value="<?php echo $q ?>" type="hidden" name="id"></td>
                    <td> خریدار <input style="border:1px solid gray" class="e-f-kharidar" name="kharidar" type="text" value="<?php echo $kharidar ?>"></td>
                    <td> کاربر <select style="border:1px solid gray" class="e-f-userlist" name="user" data="<?php echo $user ?>">

                            <?php include("php/alluserdata.php") ?> </select></td>


                    <div class="bottom-bar">
                        <input type="submit" value="ذخیره" id="sabt-edit-shomare-faktor">

                        <div class="error-edit-shomare"></div>
                    </div>



                </form>

            </tr>
        </table>

        <p class="shomare-faktor-under">شماره فاکتور قابلیت حذف شدن <strong>ندارد</strong>.
            <br>
            شماره فاکتور را میتوانید به کاربر و یا خریدار دیگری نسبت دهید یا در قسمت خریدار علت عدم استفاده از آن را بنویسید.
            <br>
            <strong>هر گونه تغییر</strong> باید به مسئول مربوطه اطلاع داده شود.
        </p>


<?php

    }
}

require_once './layout/heroFooter.php';
