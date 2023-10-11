<?php
require_once '../../database/connect.php';

if (isset($_POST['name']) && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $family = $_POST['family'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $id = $_POST['id'];
    $roll = 10;

    $authority = [
        "usersManagement" => false,
        "khorojkala-index" => false,
        "vorodkala-index" => false,
        "khorojkala-report" => false,
        "vorodkala-report" => false,
        "transfer_index" => false,
        "transfer_report" => false,
        "goodLimitReport" => false,
        "goodLimitReportAll" => false,
        "shomaresh-index" => false,
        "telegramProcess" => false,
        "givePrice" => false,
        "showRates" => false,
        "relationships" => false,
        "defineExchangeRate" => false,
        "createUserProfile" => false,
    ];
    switch ($type) {
        case '1':
            $authority = [
                "usersManagement" => false,
                "khorojkala-index" => false,
                "vorodkala-index" => false,
                "khorojkala-report" => true,
                "vorodkala-report" => true,
                "transfer_index" => false,
                "transfer_report" => false,
                "goodLimitReport" => false,
                "goodLimitReportAll" => false,
                "shomaresh-index" => false,
                "telegramProcess" => false,
                "givePrice" => true,
                "showRates" => false,
                "relationships" => false,
                "defineExchangeRate" => false,
                "createUserProfile" => false,
            ];
            break;
        case '2':
            $authority = [
                "usersManagement" => false,
                "khorojkala-index" => true,
                "vorodkala-index" => true,
                "khorojkala-report" => true,
                "vorodkala-report" => true,
                "transfer_index" => false,
                "transfer_report" => false,
                "goodLimitReport" => false,
                "goodLimitReportAll" => false,
                "shomaresh-index" => false,
                "telegramProcess" => false,
                "givePrice" => false,
                "showRates" => false,
                "relationships" => false,
                "defineExchangeRate" => false,
                "createUserProfile" => false,
            ];
            break;
        case '3':
            $authority = [
                "usersManagement" => false,
                "khorojkala-index" => true,
                "vorodkala-index" => true,
                "khorojkala-report" => true,
                "vorodkala-report" => true,
                "transfer_index" => true,
                "transfer_report" => true,
                "goodLimitReport" => true,
                "goodLimitReportAll" => true,
                "shomaresh-index" => true,
                "telegramProcess" => false,
                "givePrice" => false,
                "showRates" => false,
                "relationships" => false,
                "defineExchangeRate" => false,
                "createUserProfile" => false,
            ];
            break;
        case '4':
            $roll = 1;
            $authority = [
                "usersManagement" => true,
                "khorojkala-index" => true,
                "vorodkala-index" => true,
                "khorojkala-report" => true,
                "vorodkala-report" => true,
                "transfer_index" => true,
                "transfer_report" => true,
                "goodLimitReport" => true,
                "goodLimitReportAll" => true,
                "shomaresh-index" => true,
                "telegramProcess" => true,
                "givePrice" => true,
                "showRates" => true,
                "relationships" => true,
                "defineExchangeRate" => true,
                "createUserProfile" => true,
            ];
            break;
    }
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    try {
        $result = false;
        $conn->begin_transaction();
        try {
            if (!empty($password)) {

                $sql = "UPDATE yadakshop1402.users SET username ='$username', password = '$hash_pass', roll = $roll,
                    name = '$name', family = '$family' WHERE id = '$id'";
            } else {
                $sql = "UPDATE yadakshop1402.users SET username ='$username',roll = $roll,
                name = '$name', family = '$family' WHERE id = '$id'";
            }
            $result = $conn->query($sql);
        } catch (\Throwable $th) {
            echo $th;
        }

        if ($result === TRUE) {
            $last_id = $conn->insert_id;
            isset($_FILES['profile']) ?? uploadFile($last_id, $_FILES['profile']);
            $success = true;
        }
        $conn->commit();
    } catch (\Throwable $th) {
        throw $th;
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
