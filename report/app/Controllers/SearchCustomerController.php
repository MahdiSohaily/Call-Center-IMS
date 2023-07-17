<?php
require_once('../../database/connect.php');
if (filter_var(INPUT_POST, 'pattern')) {
    $pattern = $_POST['pattern'];
    $sql = "SELECT * FROM callcenter.customer WHERE name LIKE '" . $pattern . "%' OR  family LIKE '" . $pattern . "%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
            $rates = mysqli_query($conn, $rates_sql);

            $name = $item['name'];
            $family = $item['family'];
            $phone = $item['phone'];
?>
            <li title="انتخاب مشتری" class="odd:bg-indigo-100 rounded-sm p-2 hover:cursor-pointer flex justify-between">
                <span>کاربر دستوری</span>
                <span style="direction: ltr;">+939333346016</span>
            </li>
<?php }
    }
} ?>