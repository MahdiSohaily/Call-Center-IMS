<?php
$errors = false;
$success = false;
$selected_good = null;
$delete_success = false;

if (isset($_GET['form'], $_GET['id'])) {
    $id = $_GET['id'];
    $good_sql = "SELECT * FROM yadakshop1402.nisha WHERE id = '$id'";
    $good = mysqli_query($conn, $good_sql);
    $selected_good = mysqli_fetch_array($good);
}


// Create a new good IN THE 
if (isset($_POST['partNumber']) && ($_POST['form'] == 'create')) {
    $partNumber = strtoupper(trim($_POST['partNumber']));
    $price = $_POST['price'];
    $weight = $_POST['weight'];
    $mobis = $_POST['mobis'];
    $korea = $_POST['korea'];

    $sql = "INSERT INTO yadakshop1402.nisha (partnumber, price, weight, mobis, korea)
            VALUES ('" . $partNumber . "', '" . $price . "', '" . $weight . "', '" . $mobis . "', '" . $korea . "')";

    if ($conn->query($sql) === TRUE) {
        $success = "اطلاعات موفقانه در پایگاه داده ذخیره شد.";
    } else {
        $errors = "ذخیره سازی اطلاعات ناموفق بود";
    }
}

if (isset($_POST['partNumber']) && ($_POST['form'] == 'update')) {
    $partNumber = strtoupper(trim($_POST['partNumber']));
    $price = $_POST['price'];
    $weight = $_POST['weight'];
    $mobis = $_POST['mobis'];
    $korea = $_POST['korea'];

    $id = $_GET['id'];

    $sql = "UPDATE yadakshop1402.nisha SET partnumber= '" . $partNumber . "' , price = '" . $price . "',
             weight = '" . $weight . "' , mobis =  '" . $mobis . "', korea = '" . $korea . "' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $good_sql = "SELECT * FROM yadakshop1402.nisha WHERE id = '$id'";
        $good = mysqli_query($conn, $good_sql);
        $selected_good = mysqli_fetch_array($good);

        $success = "اطلاعات موفقانه ویرایش گردید.";
    } else {
        $errors = " ویرایش اطلاعات ناموفق بود";
    }
}
