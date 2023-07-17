<?php
require_once('../../database/connect.php');
if (filter_has_var(INPUT_POST, 'pattern')) {
    $pattern = $_POST['pattern'];
    $sql = "SELECT * FROM callcenter.customer WHERE name LIKE '%" . $pattern . "%' OR  family LIKE '%" . $pattern . "%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
            $rates = mysqli_query($conn, $rates_sql);

            $id = $item['id'];
            $name = $item['name'];
            $family = $item['family'];
            $phone = $item['phone'];
?>
            <li onclick="selectCustomer(this)" data-customer-id="<?php echo $id ?>" data-customer-name="<?php echo $name ?>" data-customer-family="<?php echo $family ?>" title="انتخاب مشتری" class="odd:bg-indigo-100 rounded-sm p-2 hover:cursor-pointer flex justify-between">
                <span><?php echo $name . ' ' . $family ?></span>
                <span style="direction: ltr;"><?php echo $phone ?></span>
            </li>
<?php }
    }
} ?>