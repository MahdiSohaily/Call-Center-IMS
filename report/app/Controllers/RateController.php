<?php
$errors = false;
$success = false;
$selected_rate = null;

if (isset($_GET['form'], $_GET['id'])) {
    $id = $_GET['id'];
    $rate_sql = "SELECT * FROM rates WHERE id = '$id'";
    $rate = mysqli_query($conn, $rate_sql);
    $selected_rate = mysqli_fetch_array($rate);
}

// Create a new good IN THE 
if (isset($_POST['rate_price']) && ($_POST['form'] == 'create')) {
    $rate_price = $_POST['rate_price'];
    $status = $_POST['status'];
    $selected = $_POST['selected'] ?? null;

    $selected = $selected == 'on' ? 1 : 0;

    $sql = "INSERT INTO rates (amount, status, selected)
            VALUES ('" . $rate_price . "', '" . $status . "', '" . $selected . "')";

    if ($conn->query($sql) === TRUE) {
        $success = "اطلاعات موفقانه در پایگاه داده ذخیره شد.";
    } else {
        $errors = "ذخیره سازی اطلاعات ناموفق بود";
    }
}

if (isset($_POST['rate_price']) && ($_POST['form'] == 'update')) {
    $rate_price = $_POST['rate_price'];
    $status = $_POST['status'];

    $id = $_GET['id'];

    $sql = "UPDATE rates SET amount= '" . $rate_price . "' , status = '" . $status . "' WHERE id = '" . $id . "'";

    if ($conn->query($sql) === TRUE) {
        $rate_sql = "SELECT * FROM rates WHERE id = '$id'";
        $rate = mysqli_query($conn, $rate_sql);
        $selected_rate = mysqli_fetch_array($rate);

        $success = "اطلاعات موفقانه ویرایش گردید.";
    } else {
        $errors = " ویرایش اطلاعات ناموفق بود";
    }
}
